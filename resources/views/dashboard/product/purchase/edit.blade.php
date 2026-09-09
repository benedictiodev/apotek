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
                                Detail Pembelian Produk</span>
                        </div>
                    </li>
                </ol>
            </nav>

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

            <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl mb-4">Detail Data Pembelian Produk</h1>
            <a href="{{ route('dashboard.product.purchase') }}"
                class="w-fit shadow-lg justify-center rounded-lg bg-slate-400 px-5 py-1.5 text-center text-sm font-medium text-white hover:bg-slate-800 focus:outline-none focus:ring-4 focus:ring-slate-300">
                Kembali
            </a>
        </div>

        <div class="p-4 bg-white rounded-lg shadow-lg 2xl:col-span-2 sm:p-6">
            <div class="mb-4">
                <div class="space-y-2">
                    <div class="flex flex-col">
                        <div class="overflow-x-auto">
                            <div class="inline-block min-w-full align-middle">
                                <div class="overflow-hidden shadow rounded-lg mb-4">
                                    <table class="table-fixed divide-y divide-gray-200 w-full">
                                        <thead class="divide-y divide-gray-200">
                                            <tr class="bg-sky-300">
                                                <td colspan="4" class="p-4 text-center text-lg font-bold uppercase text-white"> Nomor Invoice : 
                                                    {{ $data->no_invoice }}</td>
                                            </tr>
                                            <tr>
                                                <th class="p-4 text-left text-base font-bold uppercase text-gray-500">Supplier</th>
                                                <td class="p-4 text-sm font-normal text-gray-900">
                                                    {{ $data->Supplier->name }}</td>

                                                <th class="p-4 text-left text-base font-bold uppercase text-gray-500">Tanggal Pembelian</th>
                                                <td class="p-4 text-sm font-normal text-gray-900">
                                                    {{ $data->date }}</td>
                                            </tr>
                                            <tr>
                                                <th class="p-4 text-left text-base font-bold uppercase text-gray-500">Status</th>
                                                <td class="p-4 text-sm font-normal text-gray-900">
                                                    <span class="inline-flex items-center rounded-full px-4 py-0.5 text-xs font-medium {{ $data->status == 'Done' ? 'text-green-800 bg-green-100' : 'text-yellow-800 bg-yellow-100' }}">
                                                        {{ $data->status }}
                                                    </span>
                                                </td>

                                                <th class="p-4 text-left text-base font-bold uppercase text-gray-500">Total Item</th>
                                                <td class="p-4 text-sm font-normal text-gray-900">
                                                    {{ count($data->PurchaseDetail) }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="p-4 text-left text-base font-bold uppercase text-gray-500">Metode Pembayaran</th>
                                                <td class="p-4 text-sm font-normal text-gray-900">
                                                    {{ $data->payment_method || $data->payment_method == "" ? '-' : $data->payment_method }}
                                                </td>

                                                <th class="p-4 text-left text-base font-bold uppercase text-gray-500">Total Pembayaran</th>
                                                <td class="p-4 text-sm font-normal text-gray-900">
                                                    {{ format_rupiah($data->total_payment) }}</td>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>

                                @if ($data->status != 'Done')
                                    <div class="mb-4 flex justify-between">
                                        <div class="relative mt-1 w-72 sm:w-64 xl:w-96">
                                            <form action="#" onsubmit="searchProduct(event)">
                                                <input type="text" name="search" id="products-search"
                                                    class="block w-full rounded-lg border border-gray-300 p-2.5 text-gray-900 focus:border-primary-500 focus:ring-primary-500 sm:text-sm"
                                                    placeholder="Cari Data Product" autofocus autocomplete="off">
                                            </form>
                                        </div>

                                        <button
                                            data-modal-target="modal-confirmation" data-modal-toggle="modal-confirmation" type="button"
                                            class="rounded-lg shadow-lg bg-blue-700 px-5 py-2.5 text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-primary-300"
                                        >
                                            Simpan / Konfirmasi Pembelian
                                        </button>
                                    </div>
                                @endif

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
                                                    class="p-4 text-start text-base font-bold uppercase text-white">
                                                    Tanggal Expired
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
                                                <th scope="col"
                                                    class="p-4 text-start text-base font-bold uppercase text-white"
                                                    width="15%">
                                                    Total Harga
                                                </th>
                                                @if ($data->status != 'Done')
                                                    <th scope="col"
                                                        class="p-4 text-start text-base font-bold uppercase text-white"
                                                        width="15%">
                                                        Aksi
                                                    </th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody id="body-order" class="divide-y divide-gray-200 bg-white">
                                            @foreach ($data->PurchaseDetail as $item)
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
                                                        {{ $item->Stock->expired_date }}
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
                                                    <td class="whitespace-nowrap p-4 text-sm font-normal text-gray-500">
                                                        {{ format_rupiah($item->amount) }}
                                                    </td>
                                                    @if ($data->status != 'Done')
                                                        <td class="whitespace-nowrap p-4 text-sm font-normal text-gray-500">
                                                            <button type="button"
                                                                onclick="updateDetailProduct({{ $item->id }})"
                                                                data-modal-target="modal-update" data-modal-toggle="modal-update"
                                                                class="inline-flex items-center rounded-lg bg-blue-700 px-3 py-2 text-center text-sm font-medium text-white hover:bg-blue-800 focus:ring-4 focus:ring-blue-300"
                                                            >
                                                                <x-fas-pencil class="mr-2 h-4 w-4" />
                                                                Update
                                                            </button>
                                                            <button type="button"
                                                                data-drawer-target="drawer-delete-product-default"
                                                                data-drawer-show="drawer-delete-product-default" aria-controls="drawer-delete-product-default"
                                                                data-drawer-placement="right"
                                                                class="inline-flex items-center rounded-lg bg-red-700 px-3 py-2 text-center text-sm font-medium text-white hover:bg-red-800 focus:ring-4 focus:ring-red-300"
                                                                data-id="{{ $item->id }}">
                                                                <x-fas-trash-alt class="mr-2 h-4 w-4" />
                                                                Hapus
                                                            </button>
                                                        </td>
                                                    @endif
                                                </tr>
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

    <div id="product-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4">
        <div class="w-full max-w-3xl rounded-xl bg-white shadow-2xl">
            <div class="flex items-center justify-between border-b p-5">
                <div>
                    <h2 class="text-xl font-bold text-gray-900">Pilih Produk</h2>
                    <p class="mt-1 text-sm text-gray-500">Gunakan tombol ↑ ↓ lalu Enter</p>
                </div>

                <button type="button" onclick="closeProductModal()"
                    class="rounded-lg px-3 py-2 text-gray-500 hover:bg-gray-100">
                    ✕
                </button>
            </div>

            <div id="product-modal-list" class="max-h-[450px] overflow-y-auto p-4">
            </div>

            <div class="border-t p-4 text-sm text-gray-500">
                ↑ ↓ Pilih produk · Enter Tambahkan · Esc Tutup
            </div>
        </div>
    </div>

    <button
        id="btn-open-modal-add"
        type="button"
        class="hidden"
        data-modal-target="modal-add"
        data-modal-toggle="modal-add">
    </button>
    <div id="modal-add" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-4xl max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
                    <h3 class="text-xl font-semibold text-gray-900">
                        Tambah Pembelian Produk Baru
                    </h3>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center"
                        data-modal-hide="modal-add">
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
                    <form action="{{ route('dashboard.product.purchase.store.detail', ['id' => $data->id])}}" id="form-add-purchase" method="POST">
                        @csrf
                        <div class="grid grid-cols-2 gap-4">
                            <div class="col-span-1">
                                <div class="mb-3">
                                    <label for="product_code-add" class="mb-2 block text-sm font-medium text-gray-900">
                                        Kode Produk
                                    </label>
                                    <input type="text" name="product_id-add" id="product_id-add"
                                        class="block w-full rounded-lg border bg-gray-100 border-gray-300 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                                        placeholder="Kode Produk" hidden>
                                    <input type="text" name="product_code-add" id="product_code-add"
                                        class="block w-full rounded-lg border bg-gray-100 border-gray-300 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                                        placeholder="Kode Produk" readonly>
                                </div>
                                <div class="mb-3">
                                    <label for="batch-add" class="mb-2 block text-sm font-medium text-gray-900">
                                        Batch
                                    </label>
                                    <input type="text" name="batch-add" id="batch-add"
                                            class="block w-full rounded-lg border border-gray-300 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                                            placeholder="Batch" required>
                                </div>
                                <div class="mb-3">
                                    <label for="quantity-add" class="mb-2 block text-sm font-medium text-gray-900">
                                        Jumlah
                                    </label>
                                    <input type="number" name="quantity-add" id="quantity-add"  onkeyup="updateDataItem('-add')"
                                            class="block w-full rounded-lg border border-gray-300 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                                            placeholder="Jumlah" required>
                                </div>
                                <div class="mb-3">
                                    <label for="price-add" class="mb-2 block text-sm font-medium text-gray-900">
                                        Harga
                                    </label>
                                    <input type="text" name="price-add" id="price-add" onkeyup="keyup_rupiah(this);updateDataItem('-add')"
                                            class="block w-full rounded-lg border border-gray-300 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                                            placeholder="Harga" required>
                                </div>
                            </div>
                            <div class="col-span-1">
                                <div class="mb-3">
                                    <label for="product_name-add" class="mb-2 block text-sm font-medium text-gray-900">
                                        Nama Produk
                                    </label>
                                    <input type="text" name="product_name-add" id="product_name-add"
                                            class="block w-full rounded-lg border bg-gray-100 border-gray-300 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                                            placeholder="Nama Produk" readonly>
                                </div>
                                <div class="mb-3">
                                    <label for="expired_date-add" class="mb-2 block text-sm font-medium text-gray-900">
                                        Tanggal Expired
                                    </label>
                                    <input type="date" name="expired_date-add" id="expired_date-add"
                                            class="block w-full rounded-lg border border-gray-300 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                                            placeholder="Tanggal Invoice" required>
                                </div>
                                <div class="mb-3">
                                    <label for="uom_id-add" class="mb-2 block text-sm font-medium text-gray-900">
                                        Satuan
                                    </label>
                                    <select id="uom_id-add" name="uom_id-add"
                                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500">
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="amount-add" class="mb-2 block text-sm font-medium text-gray-900">
                                        Total Harga
                                    </label>
                                    <input type="text" name="amount-add" id="amount-add"
                                            class="block w-full rounded-lg border bg-gray-100 border-gray-300 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                                            placeholder="Total Harga" readonly>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- Modal footer -->
                <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b">
                    <button form="form-add-purchase"
                        type="submit"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                        Simpan Perubahan
                    </button>
                    <button id="button-close_order" data-modal-hide="modal-add" type="button"
                        class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <div id="modal-update" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-4xl max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
                    <h3 class="text-xl font-semibold text-gray-900">
                        Update Pembelian Produk Baru
                    </h3>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center"
                        data-modal-hide="modal-update">
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
                    <form action="{{ route('dashboard.product.purchase.update.detail')}}" id="form-update-purchase" method="POST">
                        @csrf
                        <div class="grid grid-cols-2 gap-4">
                            <div class="col-span-1">
                                <div class="mb-3">
                                    <label for="product_code" class="mb-2 block text-sm font-medium text-gray-900">
                                        Kode Produk
                                    </label>
                                    <input type="text" name="detail_id" id="detail_id"
                                        class="block w-full rounded-lg border bg-gray-100 border-gray-300 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                                        placeholder="Kode Produk" hidden>
                                    <input type="text" name="product_code" id="product_code"
                                        class="block w-full rounded-lg border bg-gray-100 border-gray-300 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                                        placeholder="Kode Produk" readonly>
                                </div>
                                <div class="mb-3">
                                    <label for="batch" class="mb-2 block text-sm font-medium text-gray-900">
                                        Batch
                                    </label>
                                    <input type="text" name="batch" id="batch"
                                            class="block w-full rounded-lg border border-gray-300 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                                            placeholder="Batch" required>
                                </div>
                                <div class="mb-3">
                                    <label for="quantity" class="mb-2 block text-sm font-medium text-gray-900">
                                        Jumlah
                                    </label>
                                    <input type="number" name="quantity" id="quantity"  onkeyup="updateDataItem()"
                                            class="block w-full rounded-lg border border-gray-300 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                                            placeholder="Jumlah" required>
                                </div>
                                <div class="mb-3">
                                    <label for="price" class="mb-2 block text-sm font-medium text-gray-900">
                                        Harga
                                    </label>
                                    <input type="text" name="price" id="price" onkeyup="keyup_rupiah(this);updateDataItem()"
                                            class="block w-full rounded-lg border border-gray-300 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                                            placeholder="Harga" required>
                                </div>
                            </div>
                            <div class="col-span-1">
                                <div class="mb-3">
                                    <label for="product_name" class="mb-2 block text-sm font-medium text-gray-900">
                                        Nama Produk
                                    </label>
                                    <input type="text" name="product_name" id="product_name"
                                            class="block w-full rounded-lg border bg-gray-100 border-gray-300 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                                            placeholder="Nama Produk" readonly>
                                </div>
                                <div class="mb-3">
                                    <label for="expired_date" class="mb-2 block text-sm font-medium text-gray-900">
                                        Tanggal Expired
                                    </label>
                                    <input type="date" name="expired_date" id="expired_date"
                                            class="block w-full rounded-lg border border-gray-300 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                                            placeholder="Tanggal Invoice" required>
                                </div>
                                <div class="mb-3">
                                    <label for="uom_id" class="mb-2 block text-sm font-medium text-gray-900">
                                        Satuan
                                    </label>
                                    <select id="uom_id" name="uom_id"
                                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500">
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="amount" class="mb-2 block text-sm font-medium text-gray-900">
                                        Total Harga
                                    </label>
                                    <input type="text" name="amount" id="amount"
                                            class="block w-full rounded-lg border bg-gray-100 border-gray-300 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                                            placeholder="Total Harga" readonly>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- Modal footer -->
                <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b">
                    <button form="form-update-purchase"
                        type="submit"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                        Simpan Perubahan
                    </button>
                    <button id="button-close_order" data-modal-hide="modal-update" type="button"
                        class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <div id="modal-confirmation" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-4xl max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
                    <h3 class="text-xl font-semibold text-gray-900">
                        Konfirmasi Pembelian
                    </h3>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center"
                        data-modal-hide="modal-confirmation">
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
                    <div class="overflow-hidden shadow rounded-lg mb-4">
                        <table class="table-fixed divide-y divide-gray-200 w-full">
                            <thead class="divide-y divide-gray-200">
                                <tr class="bg-sky-300">
                                    <td colspan="4" class="p-4 text-center text-lg font-bold uppercase text-white"> Nomor Invoice : 
                                        {{ $data->no_invoice }}</td>
                                </tr>
                                <tr>
                                    <th class="p-4 text-left text-base font-bold uppercase text-gray-500">Supplier</th>
                                    <td class="p-4 text-sm font-normal text-gray-900">
                                        {{ $data->Supplier->name }}</td>

                                    <th class="p-4 text-left text-base font-bold uppercase text-gray-500">Tanggal Pembelian</th>
                                    <td class="p-4 text-sm font-normal text-gray-900">
                                        {{ $data->date }}</td>
                                </tr>
                                <tr>
                                    <th class="p-4 text-left text-base font-bold uppercase text-gray-500">Total Item</th>
                                    <td class="p-4 text-sm font-normal text-gray-900">
                                        {{ count($data->PurchaseDetail) }}
                                    </td>

                                    <th class="p-4 text-left text-base font-bold uppercase text-gray-500">Total Pembayaran</th>
                                    <td class="p-4 text-sm font-normal text-gray-900">
                                        {{ format_rupiah($data->total_payment) }}</td>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <form action="{{ route('dashboard.product.purchase.confirmation', ['id' => $data->id])}}" id="form-confirmation-purchase" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="payment_method" class="mb-2 block text-sm font-medium text-gray-900">
                                Metode Pembayaran
                            </label>
                            <select id="payment_method" name="payment_method" required
                                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500">
                                <option disabled value="">Pilih Tipe Pembayaran</option>
                                @foreach ($paymentMethod as $item)
                                    <option value="{{ $item->name }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>
                <!-- Modal footer -->
                <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b">
                    <button form="form-confirmation-purchase"
                        type="submit"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                        Simpan / Konfirmasi Pembelian
                    </button>
                    <button id="button-close_order" data-modal-hide="modal-confirmation" type="button"
                        class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100">Tutup</button>
                </div>
            </div>
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
@endsection

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
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
                        `/dashboard/product/purchase/${id}`;
                    document.querySelector("#form-delete").submit();
                }
            })
        }

        const updateDataItem = (elementId = '') => {
            const price = update_to_number($(`#price${elementId}`).val());
            const quantity = $(`#quantity${elementId}`).val();
            const totalPrice = price * quantity;

            $(`#amount${elementId}`).val(update_to_format_rupiah(totalPrice));
        }

        const dataProduct = @json($data->PurchaseDetail);
        const updateDetailProduct = (id) => {
            const product = dataProduct.find(item => item.id === id);

            $('#detail_id').val(id);
            $('#product_code').val(product.product.code);
            $('#batch').val(product.stock.batch);
            $('#quantity').val(product.quantity);
            $('#price').val(update_to_format_rupiah(product.price));
            $('#product_name').val(product.product.name);
            $('#expired_date').val(product.stock.expired_date);
            // $('#uom_id').val(product.product.name);
            $('#amount').val(update_to_format_rupiah(product.amount));

            const uomOption = product.product.product_detail
                .map((item, index) => {
                    return `
                <option
                    value="${item.id}"
                    ${item.id === product.product_detail_id ? 'selected' : ''}
                >
                    ${item.uom.name}
                </option>
            `;
                })
                .join('');

            $('#uom_id').html(uomOption);
        }

        const addProductToOrder = (data) => {
            $('#product_id-add').val(data.id);
            $('#product_code-add').val(data.code);
            $('#batch-add').val('');
            $('#quantity-add').val(0);
            $('#price-add').val(update_to_format_rupiah(0));
            $('#product_name-add').val(data.name);
            $('#expired_date-add').val('');
            // $('#uom_id-add').val(product.product.name);
            $('#amount-add').val(update_to_format_rupiah(0));

            const uomOption = data.product_detail
                .map((item, index) => {
                    return `
                <option
                    value="${item.id}"
                    ${index === 0 ? 'selected' : ''}
                >
                    ${item.uom.name}
                </option>
            `;
                })
                .join('');

            $('#uom_id-add').html(uomOption);

            document.getElementById('btn-open-modal-add').click();
        }

        const searchProduct = async (event) => {
            event.preventDefault();

            const input = document.getElementById('products-search');
            const keyword = input.value.trim();

            const response = await axios.get(
                '/dashboard/product/search', {
                    params: {
                        search: keyword
                    }
                }
            );

            const resultData = JSON.parse(response.data.data);

            if (resultData.length == 1) {
                addProductToOrder(resultData[0]);
            }

            if (resultData.length > 1) {
                openProductModal(resultData);
            }

            document.getElementById('products-search').value = '';
        }

        window.selectProductFromModal = (index) => {
            const product = modalProducts[index];

            addProductToOrder(product);
            closeProductModal();
        };

        window.closeProductModal = () => {
            const modal = document.getElementById('product-modal');

            modal.classList.add('hidden');
            modal.classList.remove('flex');

            modalProducts = [];
            selectedProductIndex = 0;

            const searchInput = document.getElementById('products-search');
            searchInput.focus();
        };

        const renderProductModal = () => {
            const productList = document.getElementById('product-modal-list');

            productList.innerHTML = modalProducts
                .map((product, index) => {
                    const isSelected = index === selectedProductIndex;

                    return `
                        <button type="button" onclick="selectProductFromModal(${index})" 
                        class="product-modal-item mb-2 flex w-full items-center rounded-lg border px-4 py-2 text-left transition ${isSelected ? 'border-blue-600 bg-blue-50' : 'border-gray-200 hover:bg-gray-50'}"
                        >
                        <div class="font-semibold text-gray-900">${product.code}</div>
                        <div class="text-gray-700 mx-2">-</div>
                        <div class="text-gray-500">${product.name}</div>
                        </button>
                    `;
                })
                .join('');

            const selectedItem = document.querySelector('.product-modal-item.border-blue-600');
            selectedItem?.scrollIntoView({
                block: 'nearest'
            });
        };

        const openProductModal = (products) => {
            modalProducts = products;
            selectedProductIndex = 0;

            const modal = document.getElementById(
                'product-modal'
            );

            modal.classList.remove('hidden');
            modal.classList.add('flex');

            renderProductModal();
        };

        document.addEventListener('keydown', (event) => {
            const modal = document.getElementById('product-modal');
            const isModalOpen = !modal.classList.contains('hidden');

            if (!isModalOpen) {
                return;
            }

            // Arrow bawah
            if (event.key === 'ArrowDown') {
                event.preventDefault();
                selectedProductIndex = (selectedProductIndex + 1) % modalProducts.length;
                renderProductModal();
                return;
            }

            // Arrow atas
            if (event.key === 'ArrowUp') {
                event.preventDefault();
                selectedProductIndex = (selectedProductIndex - 1 + modalProducts.length) % modalProducts.length;
                renderProductModal();
                return;
            }

            // Enter
            if (event.key === 'Enter') {
                event.preventDefault();
                selectProductFromModal(selectedProductIndex);
                return;
            }

            // Escape
            if (event.key === 'Escape') {
                event.preventDefault();
                closeProductModal();
            }
        });
    </script>
@endpush
