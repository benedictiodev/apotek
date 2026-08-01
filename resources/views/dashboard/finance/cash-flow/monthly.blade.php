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
                <span class="ml-1 text-gray-400 md:ml-2" aria-current="page">Arus Kas Bulanan</span>
              </div>
            </li>
          </ol>
        </nav>
        <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl">Arus Kas Bulanan</h1>
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
          <form class="sm:pr-3" action="#" method="GET" id="form-search">
            <label for="cashflow-search" class="sr-only">Search</label>
            <div class="relative mt-1 w-48 sm:w-64 xl:w-96">
              <input type="month" name="periode" id="cashflow-search"
                class="block w-full rounded-lg border border-gray-300 p-2.5 text-gray-900 focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                placeholder="Search for daily cash flow"
                value="{{ Request::get('periode') ? Request::get('periode') : Date::now()->format('Y-m') }}"
                onchange="change_search()"
                max="{{ Carbon\Carbon::now()->format('Y-m') }}">
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
      </div>
      <div class="flex flex-col">
        <div class="overflow-x-auto">
          <div class="inline-block min-w-full align-middle">
            <div class="overflow-hidden shadow rounded-lg">
              <table class="min-w-full table-fixed divide-y divide-gray-200">
                <thead class="bg-sky-300">
                  <tr>
                    <th scope="col" class="p-4">
                      <div class="flex items-center">
                        <input id="checkbox-all" aria-describedby="checkbox-1" type="checkbox" ">
                        <label for="checkbox-all" class="sr-only">checkbox</label>
                      </div>
                    </th>
                    <th scope="col"
                      class="p-4 text-left text-base font-bold uppercase text-white">
                      Waktu
                    </th>
                    <th scope="col"
                      class="p-4 text-right text-base font-bold uppercase text-white">
                      Debit
                    </th>
                    <th scope="col"
                      class="p-4 text-right text-base font-bold uppercase text-white">
                      Kredit
                    </th>
                    <th scope="col"
                      class="p-4 text-right text-base font-bold uppercase text-white">
                      Jumlah
                    </th>
                    <th scope="col"
                      class="p-4 text-right text-base font-bold uppercase text-white">
                      Total Jumlah
                    </th>
                    <th scope="col"
                      class="p-4 text-center text-base font-bold uppercase text-white">
                      Aksi
                    </th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                  @forelse ($data as $item)
                    <tr class="hover:bg-gray-100">
                      <td class="w-4 p-4">
                        <div class="flex items-center">
                          <input id="checkbox-" aria-describedby="checkbox-1" type="checkbox" ">
                          <label for="checkbox-" class="sr-only">checkbox</label>
                        </div>
                      </td>
                      <td class="whitespace-nowrap p-4 text-sm font-normal text-gray-500">
                        <p class="text-sm font-normal text-gray-900">
                          {{ $item->date }}
                        </p>
                      </td>
                      <td class="text-right whitespace-nowrap p-4 text-sm font-normal text-gray-500">
                        <p class="text-sm font-normal text-gray-900">
                          {{ format_rupiah($item->debit) }}
                        </p>
                      </td>
                      <td class="text-right whitespace-nowrap p-4 text-sm font-normal text-gray-500">
                        <p class="text-sm font-normal text-gray-900">
                          {{ format_rupiah($item->kredit) }}
                        </p>
                      </td>
                      <td class="text-right whitespace-nowrap p-4 text-sm font-normal text-gray-500">
                        <p class="text-sm font-normal text-gray-900">
                          {{ format_rupiah($item->amount) }}
                        </p>
                      </td>
                      <td class="text-right whitespace-nowrap p-4 text-sm font-normal text-gray-500">
                        <p class="text-sm font-normal text-gray-900">
                          {{ format_rupiah($item->total_amount) }}
                        </p>
                      </td>

                      <td class="text-center space-x-2 whitespace-nowrap p-4">
                        <a href="{{ route('dashboard.finance.cash-flow-daily', ['periode' => $item->date]) }}"
                          class="inline-flex items-center rounded-lg bg-blue-700 px-3 py-2 text-center text-sm font-medium text-white hover:bg-blue-800 focus:ring-4 focus:ring-blue-300">
                          <x-fas-info class="mr-2 h-4 w-4" />
                          Detil
                        </a>
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
                      Total Jumlah
                    </th>
                    <th scope="col" class="p-4 text-right text-base font-bold uppercase text-white">
                      {{ format_rupiah($total_cash_out) }}
                    </th>
                    <th scope="col" class="p-4 text-right text-base font-bold uppercase text-white">
                      {{ format_rupiah($total_cash_in) }}
                    </th>
                    <th scope="col" class="p-4 text-right text-base font-bold uppercase text-white">
                      {{ format_rupiah($total_amount) }}
                    </th>
                    <th scope="col" class="p-4 text-right text-base font-bold uppercase text-white">
                    </th>
                    <th scope="col" colspan="2"
                      class="p-4 text-center text-base font-medium uppercase text-white">
                    </th>
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('script')
  <script type="text/javascript">
    function change_search() {
      let value = document.querySelector("#cashflow-search").value;
      document.querySelector("#form-search").action = `/dashboard/finance/cash-flow-monthly/`;
      document.querySelector("#form-search").submit();
    }

    const equite_change = (type = '') => {
      let equite = 0;
      $(`.${type}option-equite`).each(function() {
        equite += parseInt((this.value ? this.value : '0').replaceAll('.', ''));
        this.value = update_to_format_rupiah(this.value);
      });
      $(`#${type}equite`).val(update_to_format_rupiah(equite));
    }

    $('#set_equity').on('click', function() {
      if (this.checked) {
        $('#next_equite_form').attr('hidden', false);
      } else {
        $('#next_equite_form').attr('hidden', true);
      }
    });
  </script>
@endpush
