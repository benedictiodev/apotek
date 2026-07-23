@extends('layouts.index')

@section('main')
  <div class="">
    <div class="mb-4">
      <nav class="mb-5 flex" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 text-sm font-medium md:space-x-2">
          <li class="inline-flex items-center">
            <a href="#"
              class="inline-flex items-center text-gray-700 hover:text-primary-600">
              Beranda
            </a>
          </li>
          <li>
            <div class="flex items-center">
              <x-fas-chevron-right class="h-3 w-3 text-gray-400" />
              <span class="ml-1 text-gray-400 md:ml-2" aria-current="page">Perbarui Kata Sandi</span>
            </div>
          </li>
        </ol>
      </nav>
      <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl mb-4">Perbarui Kata Sandi</h1>
    </div>

    @if (session('success'))
      <div class="mb-4 rounded-lg bg-green-50 p-4 text-sm text-green-800"
        role="alert">
        <span class="font-medium">{{ session('success') }}</span>
      </div>
    @endif
    @if (session('failed'))
      <div class="mb-4 rounded-lg bg-red-50 p-4 text-sm text-red-800" role="alert">
        <span class="font-medium">{{ session('failed') }}</span>
      </div>
    @endif
    <div
      class="p-4 bg-white rounded-lg shadow-lg 2xl:col-span-2 sm:p-6">
      <div class="mb-4">
        <form action="{{ route('dashboard.change_password.post') }}" method="POST" id="form_edit_profile">
          @csrf
          <div class="space-y-6">
            <div>
              <label for="old_password" class="mb-2 block text-sm font-medium text-gray-900">Kata Sandi Lama</label>
              <input type="password" name="old_password" id="old_password"
                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                placeholder="Kata Sandi Lama" required>
            </div>
            <div>
              <label for="new_password" class="mb-2 block text-sm font-medium text-gray-900">Kata Sandi Baru</label>
              <input type="password" name="new_password" id="new_password"
                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                placeholder="Kata Sandi Baru" required>
            </div>
            <div>
              <label for="confirm_password" class="mb-2 block text-sm font-medium text-gray-900">Konfirmasi Kata Sandi</label>
              <input type="password" name="confirm_password" id="confirm_password"
                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                placeholder="Konfirmasi Kata Sandi" required>
            </div>
            
            <button type="submit"
              class="w-fit justify-center rounded-lg bg-blue-700 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-primary-300">
              Kirim
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection