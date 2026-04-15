@extends('layouts.admin')

@section('title', 'Manajemen Pengguna')
@section('page-title', 'Manajemen Pengguna')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <!-- Header -->
    <div class="p-6 border-b border-gray-200">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
            <div>
                <h3 class="text-xl font-bold text-gray-900">Daftar Pengguna</h3>
                <p class="text-gray-600">Kelola akun pengguna sistem SIBHP</p>
            </div>
            <button onclick="openModal()" 
                    class="mt-4 md:mt-0 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition flex items-center">
                <i class="fas fa-user-plus mr-2"></i>
                Tambah Pengguna
            </button>
        </div>
    </div>

    <!-- Tabel -->
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                 <tr>
                    <th class="text-left py-3 px-4 text-gray-600 font-medium text-sm">No</th>
                    <th class="text-left py-3 px-4 text-gray-600 font-medium text-sm">Nama</th>
                    <th class="text-left py-3 px-4 text-gray-600 font-medium text-sm">Email</th>
                    <th class="text-left py-3 px-4 text-gray-600 font-medium text-sm">Bidang</th>
                    <th class="text-left py-3 px-4 text-gray-600 font-medium text-sm">NIP</th>
                    <th class="text-left py-3 px-4 text-gray-600 font-medium text-sm">Role</th>
                    <th class="text-left py-3 px-4 text-gray-600 font-medium text-sm">Aksi</th>
                 </tr>
            </thead>
            <tbody id="usersTable">
                @foreach($users as $index => $user)
                <tr class="border-b border-gray-100 hover:bg-gray-50">
                    <td class="py-3 px-4">{{ $index + 1 }}</td>
                    <td class="py-3 px-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-white text-xs"></i>
                            </div>
                            <span class="font-medium">{{ $user->nama }}</span>
                        </div>
                    </td>
                    <td class="py-3 px-4">{{ $user->email }}</td>
                    <td class="py-3 px-4">{{ $user->bidang }}</td>
                    <td class="py-3 px-4">{{ $user->nip }}</td>
                    <td class="py-3 px-4">
                        @if($user->role === 'admin')
                            <span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full">
                                <i class="fas fa-shield-alt mr-1"></i>Admin
                            </span>
                        @else
                            <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full">
                                <i class="fas fa-user mr-1"></i>Pengguna
                            </span>
                        @endif
                    </td>
                    <td class="py-3 px-4">
                        <div class="flex items-center space-x-2">
                            <button onclick="editUser({{ $user->id }})" 
                                    class="w-8 h-8 bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 flex items-center justify-center"
                                    title="Edit">
                                <i class="fas fa-edit text-sm"></i>
                            </button>
                            @if($user->id != Auth::id())
                            <button onclick="deleteUser({{ $user->id }}, '{{ $user->nama }}')" 
                                    class="w-8 h-8 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 flex items-center justify-center"
                                    title="Hapus">
                                <i class="fas fa-trash text-sm"></i>
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    @if($users->hasPages())
    <div class="p-4 border-t border-gray-200">
        {{ $users->links('vendor.pagination.tailwind') }}
    </div>
    @endif
</div>

<!-- Modal -->
<div id="userModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-md">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-900" id="modalTitle">Tambah Pengguna</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="userForm" onsubmit="submitForm(event)">
                @csrf
                <input type="hidden" id="user_id" name="user_id">
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap *</label>
                        <input type="text" id="nama" name="nama" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <div id="error-nama" class="text-red-500 text-sm mt-1"></div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                        <input type="email" id="email" name="email" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <div id="error-email" class="text-red-500 text-sm mt-1"></div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Username *</label>
                        <input type="text" id="username" name="username" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <div id="error-username" class="text-red-500 text-sm mt-1"></div>
                    </div>
                    
                    <div id="passwordField">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password *</label>
                        <input type="password" id="password" name="password"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <div id="error-password" class="text-red-500 text-sm mt-1"></div>
                        <p class="text-xs text-gray-500 mt-1">Minimal 6 karakter</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">NIP *</label>
                        <input type="text" id="nip" name="nip" required
                               maxlength="18" minlength="18"
                               pattern="[0-9]{18}"
                               title="NIP harus terdiri dari 18 digit angka"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               oninput="validateNIP(this)">
                        <div id="error-nip" class="text-red-500 text-sm mt-1"></div>
                        <p class="text-xs text-gray-500 mt-1">NIP harus 18 digit angka</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Bidang *</label>
                        <input type="text" id="bidang" name="bidang" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <div id="error-bidang" class="text-red-500 text-sm mt-1"></div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Role *</label>
                        <select id="role" name="role" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Pilih Role</option>
                            <option value="admin">Administrator</option>
                            <option value="pengguna">Pengguna</option>
                        </select>
                        <div id="error-role" class="text-red-500 text-sm mt-1"></div>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 mt-6 pt-4 border-t">
                    <button type="button" onclick="closeModal()" 
                            class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                        Batal
                    </button>
                    <button type="submit" id="submitBtn"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let currentUserId = null;
    const modal = document.getElementById('userModal');
    const passwordField = document.getElementById('passwordField');
    const userForm = document.getElementById('userForm');
    
    // Validate NIP
    function validateNIP(input) {
        // Remove non-digit characters
        input.value = input.value.replace(/\D/g, '');
        
        const errorElement = document.getElementById('error-nip');
        if (input.value.length !== 18 && input.value.length > 0) {
            errorElement.textContent = 'NIP harus 18 digit angka';
            input.classList.add('border-red-500');
        } else if (input.value.length === 18) {
            errorElement.textContent = '';
            input.classList.remove('border-red-500');
            input.classList.add('border-green-500');
        } else {
            errorElement.textContent = '';
            input.classList.remove('border-red-500', 'border-green-500');
        }
    }
    
    // Validate all required fields before submission
    function validateForm() {
        let isValid = true;
        const requiredFields = ['nama', 'email', 'username', 'nip', 'bidang', 'role'];
        
        // Check if password is required (for new user)
        if (!currentUserId) {
            requiredFields.push('password');
        }
        
        requiredFields.forEach(field => {
            const element = document.getElementById(field);
            if (element && !element.value.trim()) {
                const errorElement = document.getElementById(`error-${field}`);
                if (errorElement) {
                    errorElement.textContent = `${field.charAt(0).toUpperCase() + field.slice(1)} harus diisi`;
                }
                isValid = false;
            }
        });
        
        // Validate NIP length
        const nip = document.getElementById('nip');
        if (nip && nip.value.length !== 18) {
            document.getElementById('error-nip').textContent = 'NIP harus 18 digit angka';
            isValid = false;
        }
        
        // Validate password length for new user
        if (!currentUserId) {
            const password = document.getElementById('password');
            if (password && password.value.length < 6) {
                document.getElementById('error-password').textContent = 'Password minimal 6 karakter';
                isValid = false;
            }
        }
        
        // Validate email format
        const email = document.getElementById('email');
        if (email && email.value.trim()) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email.value)) {
                document.getElementById('error-email').textContent = 'Format email tidak valid';
                isValid = false;
            }
        }
        
        return isValid;
    }
    
    // Open modal function
    function openModal(userId = null) {
        resetErrors();
        resetFieldStyles();
        currentUserId = userId;
        
        if (userId) {
            document.getElementById('modalTitle').textContent = 'Edit Pengguna';
            document.getElementById('submitBtn').textContent = 'Update';
            passwordField.style.display = 'none';
            
            // Fetch user data
            fetchUserData(userId);
        } else {
            document.getElementById('modalTitle').textContent = 'Tambah Pengguna';
            document.getElementById('submitBtn').textContent = 'Simpan';
            passwordField.style.display = 'block';
            document.getElementById('password').required = true;
            userForm.reset();
            document.getElementById('user_id').value = '';
        }
        
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
    
    // Reset field styles
    function resetFieldStyles() {
        const inputs = document.querySelectorAll('#userForm input, #userForm select');
        inputs.forEach(input => {
            input.classList.remove('border-red-500', 'border-green-500');
        });
    }
    
    // Close modal
    function closeModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        userForm.reset();
        resetErrors();
        resetFieldStyles();
        currentUserId = null;
    }
    
    // Reset error messages
    function resetErrors() {
        const errorElements = document.querySelectorAll('[id^="error-"]');
        errorElements.forEach(el => el.textContent = '');
    }
    
    // Fetch user data for edit
    async function fetchUserData(userId) {
        try {
            const response = await fetch(`/admin/users/${userId}/edit`);
            if (response.ok) {
                const user = await response.json();
                document.getElementById('user_id').value = user.id;
                document.getElementById('nama').value = user.nama;
                document.getElementById('email').value = user.email;
                document.getElementById('username').value = user.username;
                document.getElementById('nip').value = user.nip;
                document.getElementById('bidang').value = user.bidang;
                document.getElementById('role').value = user.role;
            }
        } catch (error) {
            console.error('Error fetching user:', error);
            alert('Gagal mengambil data pengguna');
        }
    }
    
    // Edit user
    function editUser(userId) {
        openModal(userId);
    }
    
    // Delete user
    async function deleteUser(userId, userName) {
        if (!confirm(`Apakah Anda yakin ingin menghapus pengguna "${userName}"?`)) {
            return;
        }
        
        try {
            const response = await fetch(`/admin/users/${userId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            });
            
            const result = await response.json();
            
            if (result.success) {
                alert('Pengguna berhasil dihapus');
                window.location.reload();
            } else {
                alert(result.error || 'Gagal menghapus pengguna');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menghapus pengguna');
        }
    }
    
    // Form submission
    async function submitForm(event) {
        event.preventDefault();
        
        // Validate form before submission
        if (!validateForm()) {
            return;
        }
        
        const formData = new FormData(userForm);
        const url = currentUserId ? `/admin/users/${currentUserId}` : '/admin/users';
        const method = currentUserId ? 'PUT' : 'POST';
        
        // Convert FormData to object
        const data = {};
        formData.forEach((value, key) => {
            data[key] = value;
        });
        
        // If editing and password is empty, remove it
        if (currentUserId && !data.password) {
            delete data.password;
        }
        
        try {
            const response = await fetch(url, {
                method: method,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            });
            
            const result = await response.json();
            
            if (result.success) {
                alert(result.message);
                window.location.reload();
            } else {
                // Display validation errors
                if (result.errors) {
                    resetErrors();
                    for (const [field, messages] of Object.entries(result.errors)) {
                        const errorElement = document.getElementById(`error-${field}`);
                        if (errorElement) {
                            errorElement.textContent = messages[0];
                        }
                    }
                } else {
                    alert(result.error || 'Terjadi kesalahan');
                }
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menyimpan data');
        }
    }
    
    // Close modal when clicking outside
    modal.addEventListener('click', function(event) {
        if (event.target === modal) {
            closeModal();
        }
    });
</script>
@endsection