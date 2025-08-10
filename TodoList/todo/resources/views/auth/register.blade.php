@extends('layouts.guest')

@section('title', 'Register')

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
            <h2 class="mt-4 text-center text-xl font-bold text-gray-100">Buat akun baru</h2>
        </div>

        <div class="py-8 px-8">
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-300 mb-2">Nama</label>
                    <input id="name" type="text" name="name" required autofocus
                        class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg focus:border-red-500 focus:outline-none text-white"
                        value="{{ old('name') }}">
                    @error('name')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-300 mb-2">Email</label>
                    <input id="email" type="email" name="email" required
                        class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg focus:border-red-500 focus:outline-none text-white"
                        value="{{ old('email') }}">
                    @error('email')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-300 mb-2">Password</label>
                    <input id="password" type="password" name="password" required
                        class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg focus:border-red-500 focus:outline-none text-white">
                    @error('password')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-300 mb-2">Konfirmasi Password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required
                        class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg focus:border-red-500 focus:outline-none text-white">
                </div>

                <button type="submit"
                    class="w-full bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-lg transition duration-200">
                    Daftar
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-sm text-gray-400">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="text-red-400 hover:text-red-300 font-medium">
                        Masuk disini
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection