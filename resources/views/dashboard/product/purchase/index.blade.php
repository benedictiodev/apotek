@extends('layouts.index')

@section('main')
  <div>
    <div class="mb-1 w-full">
      <div class="mb-4">
        <nav class="mb-5 flex" aria-label="Breadcrumb">
          <ol class="inline-flex items-center space-x-1 text-sm font-medium md:space-x-2">
            <li class="inline-flex items-center">
              <a href="#" class="inline-flex items-center text-gray-700 hover:text-primary-600">
                Beranda
              </a>
            </li>
            <li>
              <div class="flex items-center">
                <x-fas-chevron-right class="h-3 w-3 text-gray-400" />
                <span class="ml-1 text-gray-400 md:ml-2" aria-current="page">Pembelian Produk</span>
              </div>
            </li>
          </ol>
        </nav>
        <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl">Pembelian Produk</h1>
      </div>
    </div>

    @if (session('success'))
      <div class="mb-4 rounded-lg bg-green-50 p-4 text-sm text-green-800" role="alert">
        <span class="font-medium">{{ session('success') }}</span>
      </div>
    @endif
    @if (session('failed'))
      <div class="mb-4 rounded-lg bg-red-50 p-4 text-sm text-red-800" role="alert">
        <span class="font-medium">{{ session('failed') }}</span>
      </div>
    @endif

    <div class="p-4 bg-white rounded-lg shadow-lg 2xl:col-span-2 sm:p-6 mb-4">
      <div class="block items-center justify-between sm:flex md:divide-x md:divide-gray-100 mb-4">
        <div class="mb-4 flex items-center sm:mb-0">
          <form class="sm:pr-3" action="{{ route('dashboard.product.purchase') }}" method="GET">
            <label for="products-search" class="sr-only">Search</label>
            <div class="relative mt-1 w-48 sm:w-64 xl:w-96">
              <input type="text" name="search" id="products-search"
                class="block w-full rounded-lg border border-gray-300 p-2.5 text-gray-900 focus:border-primary-500 focus:ring-primary-500 sm:text-sm"
                placeholder="Cari Pembelian Produk" @if (isset($_GET['search'])) value="{{ $_GET['search'] }}" @endif>
            </div>
          </form>
          {{-- <div class="flex w-full items-center sm:justify-end">
            <div class="flex space-x-1 pl-2">
              <a href="#"
                class="inline-flex cursor-pointer justify-center rounded p-1 text-gray-500 hover:bg-gray-100 hover:text-gray-900">
                <x-fas-trash-alt class="h-6 w-6" />
              </a>
            </div>
          </div> --}}
        </div>
        {{-- @can('master data-produk-tambah') --}}
          <button
            data-modal-target="modal" data-modal-toggle="modal" type="button"
            class="rounded-lg shadow-lg bg-blue-700 px-5 py-2.5 text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-primary-300"
          >
            Tambahkan Pembelian Produk Baru
          </button>
        {{-- @endcan --}}
      </div>
      <div class="flex flex-col">
        <div class="overflow-x-auto">
          <div class="inline-block min-w-full align-middle">
            <div class="overflow-hidden shadow rounded-t-lg">
              <table class="min-w-full table-fixed divide-y divide-gray-200">
                <thead class="bg-sky-300">
                  <tr>
                    <th scope="col" class="p-4">
                      <div class="flex items-center">
                        <input id="checkbox-all" aria-describedby="checkbox-1" type="checkbox"
                          class="focus:ring-3 h-4 w-4 rounded border-gray-300 bg-gray-50 focus:ring-primary-300">
                        <label for="checkbox-all" class="sr-only">checkbox</label>
                      </div>
                    </th>
                    <th scope="col" class="p-4 text-start text-base font-bold uppercase text-white">
                      Nomor Invoice
                    </th>
                    <th scope="col" class="p-4 text-start text-base font-bold uppercase text-white">
                      Supplier
                    </th>
                    <th scope="col" class="p-4 text-start text-base font-bold uppercase text-white">
                      Total Item
                    </th>
                    <th scope="col" class="p-4 text-start text-base font-bold uppercase text-white">
                      Total Harga
                    </th>
                    <th scope="col" class="p-4 text-start text-base font-bold uppercase text-white">
                      Metode Pembayaran
                    </th>
                    <th scope="col" class="p-4 text-start text-base font-bold uppercase text-white">
                      Status
                    </th>
                    <th scope="col" class="p-4 text-center text-base font-bold uppercase text-white">
                      Aksi
                    </th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                  @forelse ($data as $item)
                    <tr class="hover:bg-gray-100">
                      <td class="w-4 p-4">
                        <div class="flex items-center">
                          <input id="checkbox-" aria-describedby="checkbox-1" type="checkbox"
                            class="focus:ring-3 h-4 w-4 rounded border-gray-300 bg-gray-50 focus:ring-primary-300">
                          <label for="checkbox-" class="sr-only">checkbox</label>
                        </div>
                      </td>
                      <td class="whitespace-nowrap p-4 text-sm font-normal text-gray-500">
                        <p class="text-sm font-normal text-gray-900">{{ $item->no_invoice }}
                        </p>
                      </td>
                      <td class="whitespace-nowrap p-4 text-sm font-normal text-gray-500">
                        <p class="text-sm font-normal text-gray-900">{{ $item->Supplier->name }}
                        </p>
                      </td>
                      <td class="whitespace-nowrap p-4 text-sm font-normal text-gray-500">
                        <p class="text-sm font-normal text-gray-900">{{ count($item->PurchaseDetail) }}
                        </p>
                      </td>
                      <td class="whitespace-nowrap p-4 text-sm font-normal text-gray-500">
                        <p class="text-sm font-normal text-gray-900">{{ format_rupiah($item->total_payment) }}
                        </p>
                      </td>
                      <td class="whitespace-nowrap p-4 text-sm font-normal text-gray-500">
                        <p class="text-sm font-normal text-gray-900">{{ $item->payment_method || $item->payment_method == "" ? '-' : $item->payment_method }}
                        </p>
                      </td>
                      <td class="whitespace-nowrap p-4 text-sm font-normal text-gray-500">
                        <span class="inline-flex items-center rounded-full px-4 py-0.5 text-xs font-medium {{ $item->status == 'Done' ? 'text-green-800 bg-green-100' : 'text-yellow-800 bg-yellow-100' }}">
                          {{ $item->status }}
                        </span>
                      </td>
                      <td class="text-center space-x-2 whitespace-nowrap p-4">
                        {{-- @can('master data-produk-perbarui') --}}
                          <a href="{{ route('dashboard.product.purchase.show', ['id' => $item->id]) }}"
                            id="updateProductButton" data-drawer-target="drawer-update-product-default"
                            data-drawer-show="drawer-update-product-default" aria-controls="drawer-update-product-default"
                            data-drawer-placement="right"
                            class="inline-flex items-center rounded-lg bg-blue-700 px-3 py-2 text-center text-sm font-medium text-white hover:bg-blue-800 focus:ring-4 focus:ring-primary-300">
                            <x-fas-info class="mr-2 h-4 w-4" />
                            Detail Data
                          </a>
                        {{-- @endcan --}}
                        {{-- @can('master data-produk-hapus') --}}
                          {{-- <button type="button" id="deleteProductButton"
                            data-drawer-target="drawer-delete-product-default"
                            data-drawer-show="drawer-delete-product-default" aria-controls="drawer-delete-product-default"
                            data-drawer-placement="right"
                            class="inline-flex items-center rounded-lg bg-red-700 px-3 py-2 text-center text-sm font-medium text-white hover:bg-red-800 focus:ring-4 focus:ring-red-300"
                            data-id="{{ $item->id }}">
                            <x-fas-trash-alt class="mr-2 h-4 w-4" />
                            Hapus
                          </button> --}}
                        {{-- @endcan --}}
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td class="text-center text-base font-light p-4" colspan="8">Data Kosong</td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <div
        class="sticky bottom-0 right-0 w-full items-center border-t border-gray-200 bg-white p-4 sm:flex sm:justify-between">
        {{-- {{ $data->withQueryString()->links('vendor.pagination.tailwind') }} --}}
      </div>
    </div>

    <!-- Delete Product Drawer -->
    <div id="drawer-delete-product-default"
      class="fixed right-0 top-0 z-40 h-screen w-full max-w-xs translate-x-full overflow-y-auto bg-white p-4 transition-transform"
      tabindex="-1" aria-labelledby="drawer-label" aria-hidden="true">
      <h5 id="drawer-label" class="inline-flex items-center text-sm font-semibold uppercase text-gray-500">Hapus Data
      </h5>
      <button type="button" data-drawer-dismiss="drawer-delete-product-default"
        aria-controls="drawer-delete-product-default"
        class="absolute right-2.5 top-2.5 inline-flex items-center rounded-lg bg-transparent p-1.5 text-sm text-gray-400 hover:bg-gray-200 hover:text-gray-900">
        <x-fas-info-circle aria-hidden="true" class="h-5 w-5" />
        <span class="sr-only">Tutup</span>
      </button>
      <form id="form-delete">
        @csrf
        @method('DELETE')
        <input type="text" id="delete-id" value="" hidden>
        <x-fas-circle-exclamation class="mb-4 mt-8 h-10 w-10 text-red-600" />
        <h3 class="mb-6 text-lg text-gray-500">Apakah anda yakin akan menghapus data ini?</h3>
        <button type="button" data-type="button-delete"
          class="mr-2 inline-flex items-center rounded-lg bg-red-600 px-3 py-2.5 text-center text-sm font-medium text-white hover:bg-red-800 focus:ring-4 focus:ring-red-300">
          Ya, Saya Yakin
        </button>
        <button type="button"
          class="inline-flex items-center rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-center text-sm font-medium text-gray-900 hover:bg-gray-100 focus:ring-4 focus:ring-primary-300"
          data-drawer-hide="drawer-delete-product-default">
          Tidak, Batalkan
        </button>
      </form>
    </div>
  </div>

  <div id="modal" tabindex="-1" aria-hidden="true"
      class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
      <div class="relative p-4 w-full max-w-2xl max-h-full">
          <!-- Modal content -->
          <div class="relative bg-white rounded-lg shadow">
              <!-- Modal header -->
              <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
                  <h3 class="text-xl font-semibold text-gray-900">
                      Tambahkan Pembelian Produk Baru
                  </h3>
                  <button type="button"
                      class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center"
                      data-modal-hide="modal">
                      <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                          fill="none" viewBox="0 0 14 14">
                          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                              stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                      </svg>
                      <span class="sr-only">Tutup</span>
                  </button>
              </div>
              <!-- Modal body -->
              <div class="p-4 md:p-5 space-y-4">
                <form action="{{ route('dashboard.product.purchase.store')}}" id="form-purchase" method="POST">
                  @csrf
                  <div class="mb-3">
                      <label for="no_invoice" class="mb-2 block text-sm font-medium text-gray-900">
                          No Invoice
                      </label>
                      <input type="text" name="no_invoice" id="no_invoice"
                              class="block w-full rounded-lg border border-gray-300 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                              placeholder="No Invoice" required>
                  </div>
                  <div class="mb-3">
                      <label for="supplier_id" class="mb-2 block text-sm font-medium text-gray-900">
                          Supplier
                      </label>
                      <select id="supplier_id" name="supplier_id"
                          class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500">
                          <option selected value="">Pilih Supplier</option>
                          @foreach ($supplier as $item)
                              <option value="{{ $item->id }}">{{ $item->name }}</option>
                          @endforeach
                      </select>
                  </div>
                  <div class="mb-3">
                      <label for="date" class="mb-2 block text-sm font-medium text-gray-900">
                          Tanggal Invoice
                      </label>
                      <input type="date" name="date" id="date"
                              class="block w-full rounded-lg border border-gray-300 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                              placeholder="Tanggal Invoice" required>
                  </div>
                </form>
              </div>
              <!-- Modal footer -->
              <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b">
                  <button form="form-purchase" id="button-confirm_order"
                      class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                      Konfirmasi Pembelian
                  </button>
                  <button id="button-close_order" data-modal-hide="modal" type="button"
                      class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100">Tutup</button>
              </div>
          </div>
      </div>
  </div>
@endsection

@push('script')
  <script type="text/javascript">
    window.onload = () => {
      document.addEventListener('click', async (event) => {
        // DELETE DATA
        if (event.target.getAttribute('data-drawer-target') == "drawer-delete-product-default") {
          const id = event.target.getAttribute("data-id");
          document.querySelector("#delete-id").value = id;
        }
        if (event.target.getAttribute('data-type') == "button-delete") {
          const id = document.querySelector("#delete-id").value;
          document.querySelector("#form-delete").method = "POST";
          document.querySelector("#form-delete").action =
            `/dashboard/product/${id}`;
          document.querySelector("#form-delete").submit();
        }
      })
    }
  </script>
@endpush
