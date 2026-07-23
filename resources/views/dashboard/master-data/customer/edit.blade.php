@extends('layouts.index')

@section('main')
  <div>
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
              <span class="ml-1 text-gray-400 md:ml-2" aria-current="page">Master
                Data</span>
            </div>
          </li>
          <li>
            <div class="flex items-center">
              <x-fas-chevron-right class="h-3 w-3 text-gray-400" />
              <span class="ml-1 text-gray-400 md:ml-2" aria-current="page">
                Pelanggan</span>
            </div>
          </li>
        </ol>
      </nav>
      <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl mb-4">Pembaruan Pelanggan</h1>
      <a href="{{ route('dashboard.master-data.customer') }}"
        class="w-fit shadow-lg justify-center rounded-lg bg-slate-400 px-5 py-1.5 text-center text-sm font-medium text-white hover:bg-slate-800 focus:outline-none focus:ring-4 focus:ring-slate-300">
        Kembali
      </a>
    </div>

    <div
      class="p-4 bg-white rounded-lg shadow-lg 2xl:col-span-2 sm:p-6 mb-4">
      <div>
        <form action="{{ route('dashboard.master-data.customer.update', ['id' => $data->id]) }}" method="POST"
          enctype="multipart/form-data">
          @csrf
          @method('PUT')
          <div class="space-y-6">
            <div>
              <label for="code" class="mb-2 block text-sm font-medium text-gray-900">Kode
                Pelanggan</label>
              <input type="text" name="code" id="code"
                class="block w-full rounded-lg border border-gray-300 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                placeholder="Kode Pelanggan" required value="{{ old('code', $data->code) }}">
            </div>

            <div>
              <label for="name" class="mb-2 block text-sm font-medium text-gray-900">Nama
                Pelanggan</label>
              <input type="text" name="name" id="name"
                class="block w-full rounded-lg border border-gray-300 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                placeholder="Nama Pelanggan" required value="{{ old('name', $data->name) }}">
            </div>

            <div>
              <label for="address" class="mb-2 block text-sm font-medium text-gray-900">Alamat</label>
              <textarea id="address" rows="4" name="address"
                class="block w-full rounded-lg border border-gray-300 p-2.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500"
                placeholder="Alamat">
                {{ old('address', $data->address) }}
              </textarea>
            </div>
            
            <div>
              <label for="phone_number" class="mb-2 block text-sm font-medium text-gray-900">Nomor Telfon</label>
              <input type="text" name="phone_number" id="phone_number"
                class="block w-full rounded-lg border border-gray-300 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                placeholder="Nomor Telfon" value="{{ old('phone_number', $data->phone_number) }}">
            </div>

            <div>
              <label for="email" class="mb-2 block text-sm font-medium text-gray-900">E-mail</label>
              <input type="mail" name="email" id="email"
                class="block w-full rounded-lg border border-gray-300 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                placeholder="E-mail" value="{{ old('email', $data->email) }}">
            </div>

            <div>
              <label for="bank_account_number" class="mb-2 block text-sm font-medium text-gray-900">Nomor Rekening</label>
              <input type="text" name="bank_account_number" id="bank_account_number"
                class="block w-full rounded-lg border border-gray-300 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                placeholder="Nomor Rekening" value="{{ old('bank_account_number', $data->bank_account_number) }}">
            </div>

            <div>
              <label for="remarks" class="mb-2 block text-sm font-medium text-gray-900">Keterangan</label>
              <textarea id="remarks" rows="4" name="remarks"
                class="block w-full rounded-lg border border-gray-300 p-2.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500"
                placeholder="Keterangan">
                {{ old('remarks', $data->remarks) }}
              </textarea>
            </div>

            <button type="submit"
              class="w-fit shadow-lg justify-center rounded-lg bg-blue-700 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-primary-300">
              Perbarui
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection

@push('script')
  <script type="text/javascript">
    const onChange = () => {
      const image = document.querySelector('#create-image');
      const previewImage = document.querySelector("#preview-image")
      console.log(image.files);
      const blob = URL.createObjectURL(image.files[0]);
      previewImage.src = blob;
    }
  </script>
@endpush
