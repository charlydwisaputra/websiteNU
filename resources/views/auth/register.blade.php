<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar - DIGDAYA NU</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @include('components.theme-script')
</head>
<body class="font-sans antialiased text-gray-900 bg-slate-50 flex flex-col justify-center min-h-screen selection:bg-emerald-500 selection:text-white py-10">
    
    <div class="max-w-md w-full mx-auto px-4">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-black text-[#087A4B] tracking-tight">DIGDAYA NU</h1>
            <p class="text-xs text-gray-500 mt-1">DIGITALISASI DATA & LAYANAN</p>
        </div>

        <div class="bg-white p-8 rounded-3xl shadow-xl shadow-black/5 border border-gray-100">
            <div class="text-center mb-6">
                <h2 class="text-xl font-bold text-gray-900">Daftar Akun Baru</h2>
                <p class="text-xs text-gray-500 mt-1">Bergabunglah dengan platform digital organisasi</p>
            </div>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="mb-4">
                    <label class="block font-medium text-sm text-gray-700 mb-1">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" class="w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-xl shadow-sm px-4 py-2.5 border text-sm" placeholder="Nama lengkap Anda">
                    @error('name')
                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block font-medium text-sm text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autocomplete="username" class="w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-xl shadow-sm px-4 py-2.5 border text-sm" placeholder="nama@email.com">
                    @error('email')
                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block font-medium text-sm text-gray-700 mb-1">Daftar Sebagai</label>
                    <select name="role" id="roleSelect" onchange="toggleAdminCode()" class="w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-xl shadow-sm px-4 py-2.5 border text-sm bg-white">
                        <option value="anggota" {{ old('role') == 'anggota' ? 'selected' : '' }}>Anggota</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                    @error('role')
                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4 {{ old('role') == 'admin' ? '' : 'hidden' }}" id="adminCodeWrapper">
                    <label class="block font-medium text-sm text-gray-700 mb-1">Password Khusus Admin</label>
                    <input type="password" name="admin_secret_code" placeholder="Masukkan kode rahasia admin" class="w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-xl shadow-sm px-4 py-2.5 border text-sm">
                    @error('admin_secret_code')
                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block font-medium text-sm text-gray-700 mb-1">Password</label>
                    <div class="relative">
                        <input type="password" name="password" id="passwordInput" required autocomplete="new-password" class="w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-xl shadow-sm px-4 py-2.5 border text-sm pr-16" placeholder="Minimal 8 karakter">
                        <button type="button" onclick="togglePassword()" class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-gray-500 hover:text-gray-700 font-medium">Tampilkan</button>
                    </div>
                    @error('password')
                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="block font-medium text-sm text-gray-700 mb-1">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" required autocomplete="new-password" class="w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-xl shadow-sm px-4 py-2.5 border text-sm" placeholder="Ulangi password">
                    @error('password_confirmation')
                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="w-full bg-[#087A4B] hover:bg-[#065C39] text-white font-bold py-3 px-4 rounded-xl shadow-lg transition duration-200 text-sm">
                    Daftar Akun Baru
                </button>

                <div class="text-center mt-6">
                    <p class="text-xs text-gray-500">Sudah memiliki akun? <a href="{{ route('login') }}" class="text-[#087A4B] hover:underline font-semibold">Masuk di sini</a></p>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleAdminCode() {
            const role = document.getElementById('roleSelect').value;
            const wrapper = document.getElementById('adminCodeWrapper');
            if (role === 'admin') {
                wrapper.classList.remove('hidden');
            } else {
                wrapper.classList.add('hidden');
            }
        }

        function togglePassword() {
            const input = document.getElementById('passwordInput');
            if (input.type === 'password') {
                input.type = 'text';
            } else {
                input.type = 'password';
            }
        }
    </script>
</body>
</html>