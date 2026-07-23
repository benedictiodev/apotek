@extends('layouts.auth')

@section('main')
  <div class="flex flex-col items-center justify-center px-6 pt-8 mx-auto md:h-screen pt:mt-0">
    <!-- Card -->
    <div class="w-full max-w-xl p-6 space-y-8 sm:p-8 bg-white rounded-2xl shadow-2xl">
      <div
        class="flex items-center justify-center mb-8 text-2xl font-semibold lg:mb-10">
        <img src="{{ asset("images/logo_benedictiodev.png")}}" class="mr-4 h-24" alt="Benedictio Logo">
        <div>
          <div>Benedictio Dev</div>
          <div class="text-sm">Point Of Sales Application</div>
        </div>
      </div>
      <div>
        <h4 class="text-xl text-center font-bold text-gray-900">
          Pemberitahuan Perubahan URL Website
        </h4>
        <div class="text-sm text-center mt-2">Kami informasikan bahwa website kami kini dapat diakses melalui alamat baru: <span class=" italic font-bold">pos.benedictiodev.my.id</span> . Silakan perbarui bookmark Anda dan gunakan URL terbaru untuk mengakses layanan kami.</div>
      </div>

      <div class="flex justify-center">
        <a href="https://pos.benedictiodev.my.id" class="m-auto">
          <button type="button"
            class="w-full px-5 py-3 text-base font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:ring-primary-300 sm:w-auto">
            Pindah URL
          </button>
        </a>
      </div>
    </div>
  </div>
@endsection
