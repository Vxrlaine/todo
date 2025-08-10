@extends('layouts.guest')

@section('title', 'Login')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-900">
    <div class="max-w-md w-full bg-gray-800 rounded-lg shadow-lg overflow-hidden">
        <div class="bg-gray-850 py-6 px-8 border-b border-gray-700">
            <div class="flex justify-center">
                <div class="flex items-center">
                    <i class="fas fa-tasks text-red-500 text-3xl mr-2"></i>
                    <span class="text-2xl font-bold text-white">Todo<span class="text-red-500">App</span></span>
                </div>
            </div>
            <h2 class="mt-4 text-center text-xl font-bold text-gray-100">Masuk ke akun Anda</h2>
        </div>

        <div class="py-8 px-8">
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-300 mb-2">Email</label>
                    <input id="email" type="email" name="email" required autofocus
                        class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg focus:border-red-500 focus:outline-none text-white"
                        value="{{ old('email') }}">
                    @error('email')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-gray-300 mb-2">Password</label>
                    <input id="password" type="password" name="password" required
                        class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg focus:border-red-500 focus:outline-none text-white">
                    @error('password')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center">
                        <input id="remember" type="checkbox" name="remember"
                            class="h-4 w-4 bg-gray-700 border-gray-600 focus:ring-red-500 text-red-500 rounded">
                        <label for="remember" class="ml-2 block text-sm text-gray-300">Ingat saya</label>
                    </div>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-sm text-red-400 hover:text-red-300">
                            Lupa password?
                        </a>
                    @endif
                </div>

                <button type="submit"
                    class="w-full bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-lg transition duration-200">
                    Masuk
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-sm text-gray-400">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="text-red-400 hover:text-red-300 font-medium">
                        Daftar sekarang
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection