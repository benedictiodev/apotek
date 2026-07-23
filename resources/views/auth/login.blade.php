@extends('layouts.auth')

@section('main')
  <div class="flex flex-col items-center justify-center px-6 pt-8 mx-auto md:h-screen pt:mt-0">
    <!-- Card -->
    <div class="w-full max-w-xl p-6 space-y-8 sm:p-8 bg-white rounded-2xl shadow-2xl">
      <div
        class="flex items-center justify-center mb-2 text-2xl font-semibold lg:mb-4">
        <img src="{{ asset("images/logo_with_text_in_bottom.png")}}" class="h-40" alt="Benedictio Logo">
      </div>
      <h2 class="text-2xl font-bold text-gray-900">
        Masuk ke platform
      </h2>
      <form class="mt-8 space-y-6" action="{{ route('post_login') }}" method="POST">
        @csrf
        <div>
          <label for="email" class="block mb-2 text-sm font-medium text-gray-900">E-mail</label>
          <input type="email" name="email" id="email"
            class="border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5"
            placeholder="name@company.com" required="">
        </div>
        <div>
          <label for="password" class="block mb-2 text-sm font-medium text-gray-900">Kata Sandi</label>
          <input type="password" name="password" id="password" placeholder="••••••••"
            class="border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5"
            required="">
        </div>
        <div class="flex items-end">
          {{-- <div class="flex items-center h-5">
            <input id="remember" aria-describedby="remember" name="remember" type="checkbox"
              class="w-4 h-4 border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-primary-300"
              required="">
          </div>
          <div class="ml-3 text-sm">
            <label for="remember" class="font-medium text-gray-900">Remember me</label>
          </div> --}}
          {{-- <a href="#" class="ml-auto text-sm text-primary-700 hover:underline">Lupa Kata Sandi?</a> --}}
        </div>
        <button type="submit"
          class="w-full shadow-lg px-5 py-3 text-base font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:ring-primary-300 sm:w-auto">
          Masuk ke akun Anda
        </button>
        {{-- <button type="button"
          onclick="cek_screen_size()"
          class="w-full px-5 py-3 text-base font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:ring-primary-300 sm:w-auto">
          Cek Ukuran Layar
        </button> --}}
      </form>
    </div>
  </div>
@endsection
