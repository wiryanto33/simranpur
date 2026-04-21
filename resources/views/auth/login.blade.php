<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - SIMRANPUR</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-[#F0F2F0]">
    <div class="flex items-center justify-center min-h-screen px-4 bg-[#1B3A2D]/5 sm:px-6 lg:px-8 bg-cover bg-center" style="background-image: url('https://cdn.pixabay.com/photo/2021/11/02/09/27/tank-6762624_1280.jpg'); background-blend-mode: overlay; background-color: rgba(240,242,240,0.85);">
        <div class="w-full max-w-md p-8 space-y-8 bg-white shadow-xl rounded-xl ring-1 ring-gray-900/5">
            <div class="text-center">
                <div class="flex items-center justify-center mx-auto w-24 h-24 mb-4">
                    <img src="{{ asset('logo/marinir.png') }}" alt="Logo Marinir" class="object-contain w-full h-full drop-shadow-lg">
                </div>
                <h2 class="mt-6 text-3xl font-extrabold text-gray-900">SIMRANPUR</h2>
                <p class="mt-2 text-sm font-semibold tracking-wider text-[#C8A84B] uppercase">Korps Marinir TNI AL</p>
                <p class="mt-1 text-sm text-gray-500">Sistem Informasi Pemeliharaan Kendaraan Tempur</p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-6">
                @csrf
                <div class="-space-y-px rounded-md shadow-sm">
                    <div>
                        <label for="email" class="sr-only">Email address</label>
                        <input id="email" name="email" type="email" autocomplete="email" required class="relative block w-full px-3 py-3 text-gray-900 placeholder-gray-500 border border-gray-300 rounded-none appearance-none rounded-t-md focus:z-10 focus:border-[#2D5A45] focus:outline-none focus:ring-[#2D5A45] sm:text-sm" placeholder="Alamat Email" value="{{ old('email') }}">
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>
                    <div>
                        <label for="password" class="sr-only">Password</label>
                        <input id="password" name="password" type="password" autocomplete="current-password" required class="relative block w-full px-3 py-3 text-gray-900 placeholder-gray-500 border border-gray-300 rounded-none appearance-none rounded-b-md focus:z-10 focus:border-[#2D5A45] focus:outline-none focus:ring-[#2D5A45] sm:text-sm" placeholder="Password">
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>
                </div>

                <div class="flex items-center justify-between mt-4">
                    <div class="flex items-center">
                        <input id="remember_me" name="remember" type="checkbox" class="w-4 h-4 text-[#1B3A2D] border-gray-300 rounded focus:ring-[#C8A84B]">
                        <label for="remember_me" class="block ml-2 text-sm text-gray-700"> Ingat saya </label>
                    </div>

                    @if (Route::has('password.request'))
                        <div class="text-sm">
                            <a href="{{ route('password.request') }}" class="font-medium text-[#1B3A2D] hover:text-[#C8A84B]"> Lupa password? </a>
                        </div>
                    @endif
                </div>

                <div>
                    <button type="submit" class="relative flex justify-center w-full px-4 py-3 text-sm font-medium text-white transition-colors border border-transparent rounded-md bg-[#1B3A2D] hover:bg-[#2D5A45] shadow-sm hover:shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#C8A84B] group">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                            <svg class="w-5 h-5 text-[#2D5A45] group-hover:text-[#1B3A2D] transition-colors" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                            </svg>
                        </span>
                        Masuk ke Sistem
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
