@extends('layouts.auth')

@section('main')
    <div class="flex flex-col items-center justify-center px-6 pt-8 mx-auto h-screen pt:mt-0">
        <!-- Card -->
        <x-ui.card class="w-full max-w-xl space-y-4 sm:p-8 rounded-2xl shadow-2xl">
            <div class="flex items-center justify-center mb-2 text-2xl font-semibold lg:mb-4">
                <img src="{{ asset('images/logo_with_text_in_bottom.png') }}" class="h-40" alt="Benedictio Logo">
            </div>
            <h2 class="text-2xl text-heading font-bold">
                Masuk ke platform
            </h2>

            @if (session()->has('success'))
                <x-ui.alert class="text-fg-success-strong bg-success-soft">
                    <x-fas-check-circle class="w-4 h-4 me-2 shrink-0 mt-0.5 sm:mt-0" />
                    {{ session('success') }}
                </x-ui.alert>
            @endif

            @if (session()->has('failed'))
                <x-ui.alert class="text-fg-danger-strong bg-danger-soft">
                    <x-fas-times-circle class="w-4 h-4 me-2 shrink-0 mt-0.5 sm:mt-0" />
                    {{ session('failed') }}
                </x-ui.alert>
            @endif

            <form class="mt-8 space-y-6" action="{{ route('post_login') }}" method="POST">
                @csrf
                <div>
                    <x-ui.label for="email">Email</x-ui.label>
                    <x-ui.input type="email" name="email" id="email" placeholder="name@company.com" required />
                </div>

                <div>
                    <x-ui.label for="password">Kata Sandi</x-ui.label>
                    <x-ui.input type="password" name="password" id="password" placeholder="••••••••" required />
                </div>

                <div class="flex items-center">
                    <x-ui.input id="remember" aria-describedby="remember" name="remember" type="checkbox" value="true" />
                    <x-ui.label for="remember" type="checkbox">Ingat saya</x-ui.label>
                </div>

                <x-ui.button type="submit">
                    Masuk ke akun Anda
                </x-ui.button>
            </form>
        </x-ui.card>
    </div>
@endsection
