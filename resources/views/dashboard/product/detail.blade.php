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
              <span class="ml-1 text-gray-400 md:ml-2" aria-current="page">
                Produk</span>
            </div>
          </li>
        </ol>
      </nav>
      <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl mb-4">Detail Data Produk</h1>
      <div class="flex justify-between items-center">
        <a href="{{ route('dashboard.product.master') }}"
          class="w-fit shadow-lg justify-center rounded-lg bg-slate-400 px-5 py-1.5 text-center text-sm font-medium text-white hover:bg-slate-800 focus:outline-none focus:ring-4 focus:ring-slate-300">
          Kembali
        </a>
        <div class="flex justify-between items-center">
          <a href="{{ route('dashboard.product.master.edit', ['id' => request()->route('id')]) }}"
            class="mr-2 w-fit shadow-lg justify-center rounded-lg bg-yellow-400 px-5 py-1.5 text-center text-sm font-medium text-white hover:bg-slate-800 focus:outline-none focus:ring-4 focus:ring-slate-300">
            Perbaharui Produk
          </a>
          {{-- <a href="{{ route('dashboard.product') }}"
            class="w-fit shadow-lg justify-center rounded-lg bg-yellow-400 px-5 py-1.5 text-center text-sm font-medium text-white hover:bg-slate-800 focus:outline-none focus:ring-4 focus:ring-slate-300">
            Edit Supplier
          </a> --}}
        </div>
      </div>
    </div>

    <div
      class="p-4 bg-white rounded-lg shadow-lg 2xl:col-span-2 sm:p-6">
      <div class="mb-4">
        <div class="space-y-2">
          <div class="flex justify-between gap-4">
            <div class="flex-1">
              <div class="grid grid-cols-6 mb-2">
                <label for="code" class="block text-sm font-medium text-gray-900 col-span-2">Kode
                  Produk</label>
                <div class="text-gray-500 font-semibold col-span-4">: {{ $data->code }}</div>
              </div>

              <div class="grid grid-cols-6 mb-2">
                <label for="name" class="block text-sm font-medium text-gray-900 col-span-2">Nama
                  Produk</label>
                <div class="text-gray-500 font-semibold col-span-4">: {{ $data->name }}</div>
              </div>

              <div class="grid grid-cols-6 mb-2">
                <label for="name" class="block text-sm font-medium text-gray-900 col-span-2">Kategori</label>
                <div class="text-gray-500 font-semibold col-span-4">: {{ $data->Category->name }}</div>
              </div>

              <div class="grid grid-cols-6 mb-2">
                <label for="name" class="block text-sm font-medium text-gray-900 col-span-2">Satuan Dasar</label>
                <div class="text-gray-500 font-semibold col-span-4">: {{ $data->BaseUom->name }}</div>
              </div>
            </div>

            <div class="flex-1">
              <div class="grid grid-cols-6 mb-2">
                <label for="purchase_price" class="block text-sm font-medium text-gray-900 col-span-2">Harga Beli</label>
                <div class="text-gray-500 font-semibold col-span-4">: {{ number_format($data->purchase_price, 0, ',', '.') }}</div>
              </div>

              <div class="grid grid-cols-6 mb-2">
                <label for="stock_minimal" class="block text-sm font-medium text-gray-900 col-span-2">Stok Tersedia</label>
                <div class="text-gray-500 font-semibold col-span-4">: {{ $totalStock }}</div>
              </div>

              <div class="grid grid-cols-6 mb-2">
                <label for="stock_minimal" class="block text-sm font-medium text-gray-900 col-span-2">Stok Minimal</label>
                <div class="text-gray-500 font-semibold col-span-4">: {{ $data->stock_minimal }}</div>
              </div>

              <div class="grid grid-cols-6 mb-2">
                <label for="location" class="block text-sm font-medium text-gray-900 col-span-2">Lokasi / Rak</label>
                <div class="text-gray-500 font-semibold col-span-4">: {{ $data->location }}</div>
              </div>
            </div>
          </div>

          <div class="flex flex-col">
            <div class="overflow-x-auto">
              <div class="inline-block min-w-full align-middle">
                <label for="expired_date" class="mb-2 block text-sm font-medium text-gray-900">Harga Jual</label>
                <div class="overflow-hidden shadow rounded-t-lg">
                  <table class="min-w-full table-fixed divide-y divide-gray-200">
                    <thead class="bg-sky-300">
                      <tr>
                        <th scope="col" class="p-4 text-start text-base font-bold uppercase text-white">
                          Satuan
                        </th>
                        <th scope="col" class="p-4 text-start text-base font-bold uppercase text-white">
                          Isi
                        </th>
                        <th scope="col" class="p-4 text-start text-base font-bold uppercase text-white">
                          Harga Jual
                        </th>
                        <th scope="col" class="p-4 text-start text-base font-bold uppercase text-white">
                          Laba (%)
                        </th>
                        {{-- <th scope="col" class="p-4 text-start text-base font-bold uppercase text-white">
                          Diskon (%)
                        </th> --}}
                      </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                      @foreach ($dataDetail as $item)
                        <tr>
                          <td class="whitespace-nowrap p-4 font-semibold text-gray-500">
                            <div>{{ $item->Uom->name }}</div>
                          </td>
                          <td class="whitespace-nowrap p-4 font-semibold text-gray-500">
                            <div>{{ $item->contains }}</div>
                          </td>
                          <td class="whitespace-nowrap p-4 font-semibold text-gray-500">
                            <div>{{ number_format(($dataDetail[0]?->price ?? 0), 0, ',', '.') }}</div>
                          </td>
                          <td class="whitespace-nowrap p-4 font-semibold text-gray-500">
                            <div>
                              {{ 
                                data_get($item, 'contains', 0) > 0 &&
                                $data->purchase_price > 0
                                ? (
                                    (
                                        data_get($item, 'price', 0)
                                        / data_get($item, 'contains', 1)
                                        - $data->purchase_price
                                    )
                                    / $data->purchase_price
                                ) * 100
                                : 0
                              }}
                            </div>
                          </td>
                          {{-- <td class="whitespace-nowrap p-4 font-semibold text-gray-500">
                            <div>{{ $item->discount ?? 0 }}</div>
                          </td> --}}
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>

          <div class="flex flex-col">
            <div class="overflow-x-auto">
              <div class="inline-block min-w-full align-middle">
                <label for="expired_date" class="mb-2 block text-sm font-medium text-gray-900">Stok Tersedia</label>
                <div class="overflow-hidden shadow rounded-t-lg">
                  <table class="min-w-full table-fixed divide-y divide-gray-200">
                    <thead class="bg-sky-300">
                      <tr>
                        <th scope="col" class="p-4 text-start text-base font-bold uppercase text-white">
                          No Batch
                        </th>
                        <th scope="col" class="p-4 text-start text-base font-bold uppercase text-white">
                          Stok
                        </th>
                        <th scope="col" class="p-4 text-start text-base font-bold uppercase text-white">
                          Tanggal Kadaluarsa
                        </th>
                      </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                      @foreach ($stockDetail as $item)
                        <tr>
                          <td class="whitespace-nowrap p-4 font-semibold text-gray-500">
                            <div>{{ $item->batch }}</div>
                          </td>
                          <td class="whitespace-nowrap p-4 font-semibold text-gray-500">
                            <div>{{ $item->stock }}</div>
                          </td>
                          <td class="whitespace-nowrap p-4 font-semibold text-gray-500">
                            <div>{{ $item->expired_date }}</div>
                          </td>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>

          <div class="flex flex-col">
            <div class="overflow-x-auto">
              <div class="inline-block min-w-full align-middle">
                <label for="expired_date" class="mb-2 block text-sm font-medium text-gray-900">Supplier</label>
                <div class="overflow-hidden shadow rounded-t-lg">
                  <table class="min-w-full table-fixed divide-y divide-gray-200">
                    <thead class="bg-sky-300">
                      <tr>
                        <th scope="col" class="p-4 text-start text-base font-bold uppercase text-white">
                          Kode Supplier
                        </th>
                        <th scope="col" class="p-4 text-start text-base font-bold uppercase text-white">
                          Supplier
                        </th>
                        <th scope="col" class="p-4 text-start text-base font-bold uppercase text-white">
                          Alamat
                        </th>
                        <th scope="col" class="p-4 text-start text-base font-bold uppercase text-white">
                          No Telpon
                        </th>
                        <th scope="col" class="p-4 text-start text-base font-bold uppercase text-white">
                          Email
                        </th>
                      </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                      @foreach ($supplierDetail as $item)
                        <tr>
                          <td class="whitespace-nowrap p-4 font-semibold text-gray-500">
                            <div>{{ $item->Supplier->code }}</div>
                          </td>
                          <td class="whitespace-nowrap p-4 font-semibold text-gray-500">
                            <div>{{ $item->Supplier->name }}</div>
                          </td>
                          <td class="whitespace-nowrap p-4 font-semibold text-gray-500">
                            <div>{{ $item->Supplier->address }}</div>
                          </td>
                          <td class="whitespace-nowrap p-4 font-semibold text-gray-500">
                            <div>{{ $item->Supplier->phone_number }}</div>
                          </td>
                          <td class="whitespace-nowrap p-4 font-semibold text-gray-500">
                            <div>{{ $item->Supplier->email }}</div>
                          </td>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
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
      previewImage.classList.add("h-64")
      previewImage.classList.add("mb-2")
      previewImage.style.display = "block"
    }
  </script>
@endpush
