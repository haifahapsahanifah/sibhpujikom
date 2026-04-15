<?php
// app/Http/Controllers/Admin/PermintaanController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PermintaanBarang;
use App\Models\DetailPermintaanBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PermintaanController extends Controller
{
    public function index(Request $request)
    {
        $query = PermintaanBarang::with(['user', 'details']);
        
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('nomor_surat', 'LIKE', "%{$request->search}%")
                  ->orWhere('divisi', 'LIKE', "%{$request->search}%")
                  ->orWhereHas('user', function($uq) use ($request) {
                      // Check nama if available, fallback to name depending on schema
                      $uq->where('nama', 'LIKE', "%{$request->search}%");
                      // If table has 'name' column instead/also, we check it here
                      // ->orWhere('name', 'LIKE', "%{$request->search}%")
                  });
            });
        }
        
        $permintaans = $query->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        $stats = [
            'total' => PermintaanBarang::count(),
            'menunggu_admin' => PermintaanBarang::where('status', 'menunggu_admin')->count(),
            'menunggu_user' => PermintaanBarang::where('status', 'menunggu_user')->count(),
            'disetujui' => PermintaanBarang::where('status', 'disetujui')->count(),
            'ditolak' => PermintaanBarang::where('status', 'ditolak')->count(),
            'selesai' => PermintaanBarang::where('status', 'selesai')->count(),
        ];

        return view('admin.permintaan.index', compact('permintaans', 'stats'));
    }

    public function menunggu(Request $request)
    {
        $query = PermintaanBarang::with(['user', 'details'])
            ->where('status', 'menunggu_admin');
            
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('nomor_surat', 'LIKE', "%{$request->search}%")
                  ->orWhere('divisi', 'LIKE', "%{$request->search}%")
                  ->orWhereHas('user', function($uq) use ($request) {
                      $uq->where('nama', 'LIKE', "%{$request->search}%");
                  });
            });
        }
        
        $permintaans = $query->orderBy('created_at', 'asc')
            ->paginate(10)
            ->withQueryString();

        return view('admin.permintaan.menunggu', compact('permintaans'));
    }

    public function show($id)
    {
        $permintaan = PermintaanBarang::with(['user', 'details', 'admin'])
            ->findOrFail($id);
        
        return view('admin.permintaan.detail', compact('permintaan'));
    }

    public function approve(Request $request, $id)
    {
        $request->validate([
            'details' => 'required|array',
            'details.*.disetujui_jumlah' => 'required|integer|min:0',
            'details.*.status' => 'required|in:disetujui,disesuaikan',
            'details.*.catatan_admin' => 'nullable|string',
        ]);

        $permintaan = PermintaanBarang::with('details')
            ->where('status', 'menunggu_admin')
            ->findOrFail($id);

        DB::beginTransaction();
        try {
            $hasApproved = false;
            
            foreach ($request->details as $detailId => $data) {
                $detail = $permintaan->details->find($detailId);
                if ($detail) {
                    $detail->update([
                        'disetujui_jumlah' => $data['disetujui_jumlah'],
                        'disetujui_satuan' => $detail->satuan,
                        'status' => $data['status'],
                        'catatan_admin' => $data['catatan_admin'] ?? null,
                    ]);
                    
                    if ($data['disetujui_jumlah'] > 0) {
                        $hasApproved = true;
                    }
                }
            }

            if ($hasApproved) {
                $permintaan->update([
                    'status' => 'menunggu_user',
                    'disetujui_admin_at' => now(),
                    'disetujui_admin_by' => Auth::id(),
                ]);
                
                $message = 'Permintaan telah disetujui dengan penyesuaian. Menunggu konfirmasi user.';
            } else {
                $permintaan->update([
                    'status' => 'ditolak',
                    'ditolak_at' => now(),
                    'alasan_ditolak' => 'Tidak ada barang yang disetujui',
                ]);
                $message = 'Permintaan ditolak karena tidak ada barang yang disetujui.';
            }

            DB::commit();

            // Notify user
            \Illuminate\Support\Facades\Notification::send($permintaan->user, new \App\Notifications\StatusPermintaanNotification($permintaan, $permintaan->status));

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'redirect' => route('admin.permintaan.menunggu')
                ]);
            }

            return redirect()->route('admin.permintaan.menunggu')
                ->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Approval error: ' . $e->getMessage());
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menyetujui permintaan: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Gagal menyetujui permintaan: ' . $e->getMessage());
        }
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'alasan' => 'required|string|min:10',
        ]);

        $permintaan = PermintaanBarang::where('status', 'menunggu_admin')
            ->findOrFail($id);

        $permintaan->update([
            'status' => 'ditolak',
            'ditolak_at' => now(),
            'alasan_ditolak' => $request->alasan,
        ]);

        // Notify user
        \Illuminate\Support\Facades\Notification::send($permintaan->user, new \App\Notifications\StatusPermintaanNotification($permintaan, 'ditolak'));

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Permintaan berhasil ditolak.',
                'redirect' => route('admin.permintaan.menunggu')
            ]);
        }

        return redirect()->route('admin.permintaan.menunggu')
            ->with('success', 'Permintaan berhasil ditolak.');
    }

    public function getDetailJson($id)
    {
        try {
            Log::info('Fetching permintaan detail for ID: ' . $id);
            
            $permintaan = PermintaanBarang::with(['user', 'details', 'admin'])
                ->findOrFail($id);
            
            return response()->json([
                'success' => true,
                'permintaan' => [
                    'id' => $permintaan->id,
                    'nomor_surat' => $permintaan->nomor_surat,
                    'divisi' => $permintaan->divisi,
                    'tanggal_dibutuhkan' => $permintaan->tanggal_dibutuhkan ? $permintaan->tanggal_dibutuhkan->format('Y-m-d') : date('Y-m-d'),
                    'prioritas' => $permintaan->prioritas,
                    'status' => $permintaan->status,
                    'catatan' => $permintaan->catatan,
                    'alasan_ditolak' => $permintaan->alasan_ditolak,
                    'disetujui_admin_at' => $permintaan->disetujui_admin_at ? $permintaan->disetujui_admin_at->format('Y-m-d H:i:s') : null,
                    'disetujui_user_at' => $permintaan->disetujui_user_at ? $permintaan->disetujui_user_at->format('Y-m-d H:i:s') : null,
                    'created_at' => $permintaan->created_at->format('Y-m-d H:i:s'),
                    'user' => [
                        'id' => $permintaan->user->id,
                        'nama' => $permintaan->user->nama ?? $permintaan->user->name,
                    ],
                    'admin' => $permintaan->admin ? [
                        'nama' => $permintaan->admin->nama ?? $permintaan->admin->name,
                    ] : null,
                    'details' => $permintaan->details->map(function($detail) {
                        // Ambil nama satuan dengan aman
                        $satuanName = 'pcs';
                        if ($detail->satuan) {
                            if (is_string($detail->satuan) && $this->isJson($detail->satuan)) {
                                $satuanData = json_decode($detail->satuan, true);
                                $satuanName = $satuanData['name'] ?? $satuanData['satuan'] ?? 'pcs';
                            } else {
                                $satuanName = is_object($detail->satuan) ? ($detail->satuan->name ?? 'pcs') : $detail->satuan;
                            }
                        }
                        
                        return [
                            'id' => $detail->id,
                            'kode_barang' => $detail->kode_barang,
                            'nama_barang' => $detail->nama_barang,
                            'spesifikasi' => $detail->spesifikasi,
                            'pengajuan_jumlah' => $detail->pengajuan_jumlah,
                            'satuan' => $satuanName,
                            'keperluan' => $detail->keperluan,
                            'disetujui_jumlah' => $detail->disetujui_jumlah,
                            'disetujui_satuan' => $detail->disetujui_satuan ?? $satuanName,
                            'status' => $detail->status,
                            'catatan_admin' => $detail->catatan_admin,
                        ];
                    }),
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error in getDetailJson: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    private function isJson($string) {
        if (!is_string($string)) return false;
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }

    public function cetakStruk($id)
    {
        $permintaan = PermintaanBarang::with(['user', 'details', 'admin'])->findOrFail($id);

        if ($permintaan->status == 'disetujui') {
            $permintaan->update([
                'status' => 'selesai',
            ]);
            
            // Notify user
            \Illuminate\Support\Facades\Notification::send($permintaan->user, new \App\Notifications\StatusPermintaanNotification($permintaan, 'selesai'));
        }

        $isAdminMode = true;
        return view('admin.permintaan.struk', compact('permintaan', 'isAdminMode'));
    }
}