@extends('layouts.index')

@section('main')
  <div>
    <div class="mb-1 w-full">
      <div class="mb-4">
        <nav class="mb-5 flex" aria-label="Breadcrumb">
          <ol class="inline-flex items-center space-x-1 text-sm font-medium md:space-x-2">
            <li class="inline-flex items-center">
              <a href="#" class="inline-flex items-center text-gray-700 hover:text-blue-600">
                Beranda
              </a>
            </li>
            <li>
              <div class="flex items-center">
                <x-fas-chevron-right class="h-3 w-3 text-gray-400" />
                <span class="ml-1 text-gray-400 md:ml-2" aria-current="page">Keuangan</span>
              </div>
            </li>
            <li>
              <div class="flex items-center">
                <x-fas-chevron-right class="h-3 w-3 text-gray-400" />
                <span class="ml-1 text-gray-400 md:ml-2" aria-current="page">Arus Kas Harian</span>
              </div>
            </li>
          </ol>
        </nav>
        <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl">Arus Kas Harian</h1>
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

    <div class="p-4 bg-white rounded-lg shadow-lg 2xl:col-span-2 mb-4">
      <div class="block items-center justify-between sm:flex md:divide-x md:divide-gray-100 mb-4">
        <div class="mb-4 flex items-center sm:mb-0">
          <form class="sm:pr-3" action="#" method="GET" id="form-search">
            <label for="cashflow-search" class="sr-only">Pencarian</label>
            <div class="relative mt-1 w-48 sm:w-64 xl:w-96">
              <input type="date" name="periode" id="cashflow-search"
                class="block w-full rounded-lg border border-gray-300 p-2.5 text-gray-900 focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                placeholder="Search for daily cash flow"
                value="{{ Request::get('periode') ? Request::get('periode') : Date::now()->format('Y-m-d') }}"
                onchange="change_search()"
                max="{{ Carbon\Carbon::now()->format('Y-m-d') }}">
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
        @if (request()->get('periode') == Carbon\Carbon::now()->format('Y-m-d') or !request()->get('periode'))
          <div>
            @can('keuangan-arus kas harian-tambah pemasukkan dana')
              <a id="createProductButton"
                class="mr-2 shadow-lg rounded-lg bg-blue-700 px-5 py-2.5 text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300"
                href="{{ route('dashboard.finance.cash-in.create') }}">
                Tambahkan Pemasukan Dana
              </a>
            @endcan
            @can('keuangan-arus kas harian-tambah pengeluaran dana')
              <a id="createProductButton"
                class="rounded-lg shadow-lg bg-blue-700 px-5 py-2.5 text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300"
                href="{{ route('dashboard.finance.cash-out.create') }}">
                Tambahakan Pengeluaran Dana
              </a>
            @endcan
          </div>
        @endif
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
                          class="focus:ring-3 h-4 w-4 rounded border-gray-300 bg-gray-50 focus:ring-blue-300">
                        <label for="checkbox-all" class="sr-only">checkbox</label>
                      </div>
                    </th>
                    <th scope="col" class="p-4 text-left text-base font-bold uppercase text-white">
                      Waktu
                    </th>
                    <th scope="col" class="p-4 text-right text-base font-bold uppercase text-white">
                      Debit
                    </th>
                    <th scope="col" class="p-4 text-right text-base font-bold uppercase text-white">
                      Kredit
                    </th>
                    <th scope="col" class="p-4 text-left text-base font-bold uppercase text-white">
                      Tipe Dana
                    </th>
                    <th scope="col" class="p-4 text-left text-base font-bold uppercase text-white">
                      Keterangan
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
                            class="focus:ring-3 h-4 w-4 rounded border-gray-300 bg-gray-50 focus:ring-blue-300">
                          <label for="checkbox-" class="sr-only">checkbox</label>
                        </div>
                      </td>
                      <td class="whitespace-nowrap p-4 text-sm font-normal text-gray-500">
                        <p class="text-sm font-normal text-gray-900">
                          {{ $item->date_time }}
                        </p>
                      </td>
                      <td class="text-right whitespace-nowrap p-4 text-sm font-normal text-gray-500">
                        <p class="text-sm font-normal text-gray-900">
                          {{ $item->type == 'cash-out' ? format_rupiah($item->fund) : '-' }}
                        </p>
                      </td>
                      <td class="text-right whitespace-nowrap p-4 text-sm font-normal text-gray-500">
                        <p class="text-sm font-normal text-gray-900">
                          {{ $item->type == 'cash-in' ? format_rupiah($item->fund) : '-' }}
                        </p>
                      </td>
                      <td class="whitespace-nowrap p-4 text-sm font-normal text-gray-500">
                        <p class="text-sm font-normal text-gray-900">{{ $item->type_fund }}
                        </p>
                      </td>
                      <td class="whitespace-nowrap p-4 text-sm font-normal text-gray-500">
                        <p class="text-sm font-normal text-gray-900">{{ $item->remarks_from_master }}
                        </p>
                      </td>

                      <td class="text-center space-x-2 whitespace-nowrap p-4">
                        @if ($item->type == 'cash-in' && $item->order_id)
                          @canany(['order-order aktif-lihat', 'order-riwayat order-lihat'])
                            <a href="{{ route('dashboard.order.order_detail', ['id' => $item->order_id]) }}"
                              class="inline-flex items-center rounded-lg bg-blue-700 px-3 py-2 text-center text-sm font-medium text-white hover:bg-blue-800 focus:ring-4 focus:ring-blue-300">
                              <x-fas-file class="mr-2 h-4 w-4" />
                              Lihat Order
                            </a>
                          @endcanany
                        @else
                          @can('keuangan-arus kas harian-perbarui dana') 
                            <a href="{{ route('dashboard.finance.' . $item->type . '.edit', ['id' => $item->id]) }}"
                              id="updateProductButton"
                              class="inline-flex items-center rounded-lg bg-blue-700 px-3 py-2 text-center text-sm font-medium text-white hover:bg-blue-800 focus:ring-4 focus:ring-blue-300">
                              <x-fas-edit class="mr-2 h-4 w-4" />
                              Perbarui
                            </a>
                          @endcan
                          @can('keuangan-arus kas harian-hapus dana') 
                            <button type="button" id="deleteProductButton"
                              data-drawer-target="drawer-delete-cash-in-default"
                              data-drawer-show="drawer-delete-cash-in-default"
                              aria-controls="drawer-delete-cash-in-default" data-drawer-placement="right"
                              class="inline-flex items-center rounded-lg bg-red-700 px-3 py-2 text-center text-sm font-medium text-white hover:bg-red-800 focus:ring-4 focus:ring-red-300"
                              data-id="{{ $item->id }}" data-type="{{ $item->type }}">
                              <x-fas-trash-alt class="mr-2 h-4 w-4" />
                              Hapus
                            </button>
                          @endcan
                        @endif
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td class="text-center text-base font-light p-4" colspan="7">Data Kosong</td>
                    </tr>
                  @endforelse
                </tbody>
                <tfoot class="bg-sky-300">
                  <tr>
                    <th scope="col" class="p-4 text-center text-base font-bold uppercase text-white">
                    </th>
                    <th scope="col" class="p-4 text-left text-base font-bold uppercase text-white">
                      Jumlah Total
                    </th>
                    <th scope="col" class="p-4 text-right text-base font-bold uppercase text-white">
                      {{ format_rupiah($total_cash_out) }}
                    </th>
                    <th scope="col" class="p-4 text-right text-base font-bold uppercase text-white">
                      {{ format_rupiah($total_cash_in) }}
                    </th>
                    <th scope="col" colspan="3"
                      class="p-4 text-center text-base font-medium uppercase text-white">
                    </th>
                  </tr>
                  <tr class="border-t">
                    <th scope="col" colspan="7" class="p-4 text-center text-sm font-bold uppercase text-white">
                      Detil Jumlah Setiap Tipe Dana
                    </th>
                  </tr>
                  @foreach ($result_fund as $item)
                      <tr class="bg-white border-t">
                        <th scope="col" class="p-4 text-center text-sm font-normal">
                        </th>
                        <th scope="col" class="p-4 text-left text-sm font-normal">
                          {{ $item->name }}
                        </th>
                        <th scope="col" class="p-4 text-right text-sm font-normal">
                          {{ format_rupiah($item->cash_out) }}
                        </th>
                        <th scope="col" class="p-4 text-right text-sm font-normal">
                          {{ format_rupiah($item->cash_in) }}
                        </th>
                        <th scope="col" colspan="3"
                          class="p-4 text-center text-sm font-normal">
                        </th>
                      </tr>
                  @endforeach
                </tfoot>
              </table>
            </div>
          </div>
        </div>
      </div>

      <div
        class="sticky bottom-0 right-0 w-full items-center border-t border-gray-200 bg-white p-4 sm:flex sm:justify-between">
        {{ $data->links('vendor.pagination.tailwind') }}
      </div>
    </div>

    <!-- Delete Product Drawer -->
    <div id="drawer-delete-cash-in-default"
      class="fixed right-0 top-0 z-40 h-screen w-full max-w-xs translate-x-full overflow-y-auto bg-white p-4 transition-transform"
      tabindex="-1" aria-labelledby="drawer-label" aria-hidden="true">
      <h5 id="drawer-label" class="inline-flex items-center text-sm font-semibold uppercase text-gray-500">Hapus Item
      </h5>
      <button type="button" data-drawer-dismiss="drawer-delete-cash-in-default"
        aria-controls="drawer-delete-cash-in-default"
        class="absolute right-2.5 top-2.5 inline-flex items-center rounded-lg bg-transparent p-1.5 text-sm text-gray-400 hover:bg-gray-200 hover:text-gray-900">
        <x-fas-info-circle aria-hidden="true" class="h-5 w-5" />
        <span class="sr-only">Tutup</span>
      </button>
      <form id="form-delete">
        @csrf
        @method('DELETE')
        <input type="text" id="delete-id" value="" hidden>
        <input type="text" id="delete-type" value="" hidden>
        <input type="text" id="delete-periode" value="{{ Request::get('periode') }}" hidden>
        <x-fas-circle-exclamation class="mb-4 mt-8 h-10 w-10 text-red-600" />
        <h3 class="mb-6 text-lg text-gray-500">Apakah anda yakin untuk menghapus dana ini?</h3>
        <button type="button" data-type="button-delete"
          class="mr-2 inline-flex items-center rounded-lg bg-red-600 px-3 py-2.5 text-center text-sm font-medium text-white hover:bg-red-800 focus:ring-4 focus:ring-red-300">
          Ya, Saya Yakin
        </button>
        <button type="button"
          class="inline-flex items-center rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-center text-sm font-medium text-gray-900 hover:bg-gray-100 focus:ring-4 focus:ring-blue-300"
          data-drawer-hide="drawer-delete-cash-in-default">
          Tidak, Batalkan
        </button>
      </form>
    </div>
  </div>
@endsection

@push('script')
  <script type="text/javascript">
    function change_search() {
      let value = document.querySelector("#cashflow-search").value;
      document.querySelector("#form-search").action = `/dashboard/finance/cash-flow-daily/`;
      document.querySelector("#form-search").submit();
    }

    window.onload = () => {
      document.addEventListener('click', async (event) => {
        // DELETE DATA
        if (event.target.getAttribute('data-drawer-target') == "drawer-delete-cash-in-default") {
          const id = event.target.getAttribute("data-id");
          const type = event.target.getAttribute("data-type");
          document.querySelector("#delete-id").value = id;
          document.querySelector("#delete-type").value = type;
        }
        if (event.target.getAttribute('data-type') == "button-delete") {
          const id = document.querySelector("#delete-id").value;
          const type = document.querySelector("#delete-type").value;
          const periode = document.querySelector("#delete-periode").value;
          document.querySelector("#form-delete").method = "POST";
          document.querySelector("#form-delete").action =
            `/dashboard/finance/${type}/${id}${periode !== '' ? `?periode=${periode}`: ''}`;
          document.querySelector("#form-delete").submit();
        }
      })
    }
  </script>
@endpush
