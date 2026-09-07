@extends('layouts.index')

@section('main')
    <div class="">
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
                            <span class="ml-1 text-gray-400 md:ml-2" aria-current="page">
                                Order</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl mb-4">Detail Data Order</h1>
            <a href="{{ route('dashboard.order.list') }}"
                class="w-fit shadow-lg justify-center rounded-lg bg-slate-400 px-5 py-1.5 text-center text-sm font-medium text-white hover:bg-slate-800 focus:outline-none focus:ring-4 focus:ring-slate-300">
                Kembali
            </a>
        </div>

        <div class="p-4 bg-white rounded-lg shadow-lg 2xl:col-span-2 sm:p-6">
            <div class="mb-4">
                <div class="mb-4 flex justify-between">
                    
                </div>

                <div class="space-y-2">
                    <div class="flex flex-col">
                        <div class="overflow-x-auto">
                            <div class="inline-block min-w-full align-middle">
                                <div class="overflow-hidden shadow rounded-lg mb-4">
                                    <table class="table-fixed divide-y divide-gray-200 w-full">
                                        <thead class="divide-y divide-gray-200">
                                            <tr class="bg-sky-300">
                                                <td colspan="4" class="p-4 text-center text-lg font-bold uppercase text-white"> ID Order
                                                    {{ $orders->id_order }}</td>
                                            </tr>
                                            <tr>
                                                <th class="p-4 text-left text-base font-bold uppercase text-gray-500">Kasir</th>
                                                <td class="p-4 text-sm font-normal text-gray-900">
                                                    {{ $orders->User->name }}</td>

                                                <th class="p-4 text-left text-base font-bold uppercase text-gray-500">Waktu</th>
                                                <td class="p-4 text-sm font-normal text-gray-900">
                                                    {{ $orders->date_time }}</td>
                                            </tr>
                                            <tr>
                                                <th class="p-4 text-left text-base font-bold uppercase text-gray-500">Pelanggan</th>
                                                <td class="p-4 text-sm font-normal text-gray-900">
                                                    {{ $orders->Customer ?  $orders->Customer->name : '-' }}</td>

                                                <th class="p-4 text-left text-base font-bold uppercase text-gray-500">Total Pembayaran</th>
                                                <td class="p-4 text-sm font-normal text-gray-900">
                                                    {{ format_rupiah($orders->total_payment) }}</td>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                                <div class="overflow-hidden shadow rounded-lg">
                                    <table class="min-w-full table-fixed divide-y divide-gray-200">
                                        <thead class="bg-sky-300">
                                            <tr>
                                                <th scope="col"
                                                    class="p-4 text-start text-base font-bold uppercase text-white">
                                                    Kode
                                                </th>
                                                <th scope="col"
                                                    class="p-4 text-start text-base font-bold uppercase text-white">
                                                    Nama
                                                </th>
                                                <th scope="col"
                                                    class="p-4 text-start text-base font-bold uppercase text-white">
                                                    Batch
                                                </th>
                                                <th scope="col"
                                                    class="p-4 text-start text-base font-bold uppercase text-white"
                                                    width="10%">
                                                    Jumlah
                                                </th>
                                                <th scope="col"
                                                    class="p-4 text-start text-base font-bold uppercase text-white"
                                                    width="13%">
                                                    Satuan
                                                </th>
                                                <th scope="col"
                                                    class="p-4 text-start text-base font-bold uppercase text-white"
                                                    width="15%">
                                                    Harga
                                                </th>
                                                {{-- <th scope="col"
                                                    class="p-4 text-start text-base font-bold uppercase text-white"
                                                    width="15%">
                                                    Total
                                                </th>
                                                <th scope="col"
                                                    class="p-4 text-start text-base font-bold uppercase text-white"
                                                    width="12%">
                                                    Diskon (%)
                                                </th>
                                                <th scope="col"
                                                    class="p-4 text-start text-base font-bold uppercase text-white"
                                                    width="12%">
                                                    Total Diskon
                                                </th> --}}
                                                <th scope="col"
                                                    class="p-4 text-start text-base font-bold uppercase text-white"
                                                    width="15%">
                                                    Total Harga
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody id="body-order" class="divide-y divide-gray-200 bg-white">
                                            @foreach ($orders->Orders as $item)
                                                <tr>
                                                    <td class="whitespace-nowrap p-4 text-sm font-normal text-gray-500">
                                                        {{ $item->Product->code }}
                                                    </td>
                                                    <td class="whitespace-nowrap p-4 text-sm font-normal text-gray-500">
                                                        {{ $item->Product->name }}
                                                    </td>
                                                    <td class="whitespace-nowrap p-4 text-sm font-normal text-gray-500">
                                                        {{ $item->Stock->batch }}
                                                    </td>
                                                    <td class="whitespace-nowrap p-4 text-sm font-normal text-gray-500">
                                                        {{ $item->quantity }}
                                                    </td>
                                                    <td class="whitespace-nowrap p-4 text-sm font-normal text-gray-500">
                                                        {{ $item->uom }}
                                                    </td>
                                                    <td class="whitespace-nowrap p-4 text-sm font-normal text-gray-500">
                                                        {{ format_rupiah($item->price) }}
                                                    </td>
                                                    {{-- <td class="whitespace-nowrap p-4 text-sm font-normal text-gray-500">
                                                        {{ format_rupiah($item->total_price) }}
                                                    </td>
                                                    <td class="whitespace-nowrap p-4 text-sm font-normal text-gray-500">
                                                        {{ $item->discount }}
                                                    </td>
                                                    <td class="whitespace-nowrap p-4 text-sm font-normal text-gray-500">
                                                        {{ format_rupiah($item->total_discount) }}
                                                    </td> --}}
                                                    <td class="whitespace-nowrap p-4 text-sm font-normal text-gray-500">
                                                        {{ format_rupiah($item->amount) }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot class="bg-sky-300">
                                            <tr>
                                                <td class="text-start p-2 text-base font-bold uppercase text-white">
                                                    {{ count($orders->Orders) }} item</td>
                                                <td colspan="5" class="text-end p-2 text-base font-bold uppercase text-white">Total
                                            </td>
                                                <td class="text-end p-2 text-base font-bold uppercase text-white">
                                                    {{ format_rupiah($orders->total_price_item) }}</td>
                                                </tr>
                                            {{-- <tr>
                                                <td colspan="6" class="text-end p-2 text-base font-bold uppercase text-white">
                                                    Diskon</td>
                                                <td class="text-end p-2 text-base font-bold uppercase text-white">
                                                    {{ format_rupiah($orders->total_discount) }}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="6" class="text-end p-2 text-base font-bold uppercase text-white">
                                                    PPN</td>
                                                <td class="text-end p-2 text-base font-bold uppercase text-white">
                                                    {{ format_rupiah($orders->total_tax) }}</td>
                                            </tr> --}}
                                            <tr>
                                                <td colspan="6" class="text-end p-2 text-base font-bold uppercase text-white">
                                                    Total Pembayaran</td>
                                                <td class="text-end p-2 text-base font-bold uppercase text-white">
                                                    {{ format_rupiah($orders->total_payment) }}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="6" class="text-end p-2 text-base font-bold uppercase text-white">
                                                    {{ $orders->payment_method }}</td>
                                                <td class="text-end p-2 text-base font-bold uppercase text-white">
                                                    {{ format_rupiah($orders->payment) }}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="6" class="text-end p-2 text-base font-bold uppercase text-white">
                                                    Kembalian</td>
                                                <td class="text-end p-2 text-base font-bold uppercase text-white">
                                                    {{ format_rupiah($orders->change) }}</td>
                                            </tr>
                                        </tfoot>
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
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
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
