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
            <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl mb-4">Tambahkan Data Order</h1>
            <a href="{{ route('dashboard.order') }}"
                class="w-fit shadow-lg justify-center rounded-lg bg-slate-400 px-5 py-1.5 text-center text-sm font-medium text-white hover:bg-slate-800 focus:outline-none focus:ring-4 focus:ring-slate-300">
                Kembali
            </a>
        </div>

        <div class="p-4 bg-white rounded-lg shadow-lg 2xl:col-span-2 sm:p-6">
            <div class="mb-4">
                <div class="mb-4 flex justify-between">
                    <div class="relative mt-1 w-72 sm:w-64 xl:w-96">
                        <form action="#" onsubmit="searchProduct(event)">
                            <input type="text" name="search" id="products-search"
                                class="block w-full rounded-lg border border-gray-300 p-2.5 text-gray-900 focus:border-primary-500 focus:ring-primary-500 sm:text-sm"
                                placeholder="Cari Data Product" autofocus>
                        </form>
                    </div>
                    <div class="flex gap-2">
                        <input type="text" id="confirm-total_payment" value="Rp. 0"
                            class="flex-6 block w-full rounded-lg border bg-gray-100 border-gray-300 p-2.5 text-gray-900 focus:border-primary-500 focus:ring-primary-500 sm:text-sm"
                            disabled>
                        <button data-modal-target="modal-order" data-modal-toggle="modal-order" type="button"
                            onclick="open_modal_confirm_order()"
                            class="flex-6 w-fit shadow-lg justify-center rounded-lg bg-blue-700 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300">
                            Konfirmasi Pembayaran
                        </button>
                    </div>
                </div>

                <form action="{{ route('dashboard.order.store') }}" method="POST" enctype="multipart/form-data"
                    id="form-order">
                    @csrf
                    <div class="space-y-2">
                        <div class="flex flex-col">
                            <div class="overflow-x-auto">
                                <div class="inline-block min-w-full align-middle">
                                    <div class="overflow-hidden shadow rounded-t-lg">
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
                                                    </th>
                                                    <th scope="col"
                                                        class="p-4 text-start text-base font-bold uppercase text-white"
                                                        width="15%">
                                                        Total Harga
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody id="body-order" class="divide-y divide-gray-200 bg-white"></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="modal-order" tabindex="-1" aria-hidden="true"
                        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                        <div class="relative p-4 w-full max-w-2xl max-h-full">
                            <!-- Modal content -->
                            <div class="relative bg-white rounded-lg shadow">
                                <!-- Modal header -->
                                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
                                    <h3 class="text-xl font-semibold text-gray-900">
                                        Konfirmasi Order
                                    </h3>
                                    <button type="button"
                                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center"
                                        data-modal-hide="modal-order">
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
                                    <div id="false-confirm_order">
                                        <div class="text-center text-red-500 font-bold">Oppss !!!</div>
                                        <div class="text-center text-sm font-semi text-red-400">
                                            Ada yang salah, silakan Tambahkan Produk Ke Keranjang
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="customer_id" class="mb-2 block text-sm font-medium text-gray-900">
                                            Nama Pelanggan
                                        </label>
                                        <select id="customer_id" name="customer_id"
                                            class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500">
                                            <option selected value="">Pilih Pelanggan</option>
                                            @foreach ($customers as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div id="payment_form" class="border pl-4 pr-2 py-4 rounded-lg relative">
                                        <div class="absolute top-[-11px] left-0 right-0 flex justify-center">
                                            <div class="bg-white px-4 text-sm font-semibold">Formulir pembayaran</div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="payment_method"
                                                class="mb-2 block text-sm font-medium text-gray-900">
                                                Tipe Pembayaran
                                            </label>
                                            <select id="payment_method" name="payment_method" required
                                                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500">
                                                <option disabled value="">Pilih Tipe Pembayaran</option>
                                                @foreach ($paymentMethod as $item)
                                                    <option value="{{ $item->name }}">{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="total_price_item"
                                                class="mb-2 block text-sm font-medium text-gray-900">
                                                Total Harga
                                            </label>
                                            <input type="text" min="0" name="total_price_item"
                                                id="total_price_item"
                                                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                                                placeholder="Total Harga" readonly>
                                        </div>
                                        <div class="mb-3 flex justify-between">
                                            <div class="w-1/3 mr-2">
                                                <label for="discount"
                                                    class="mb-2 block text-sm font-medium text-gray-900">
                                                    Diskon (%)
                                                </label>
                                                <input type="text" min="0" name="discounts" id="discount"
                                                    class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                                                    placeholder="Diskon" onkeyup="change_discount()" value="0">
                                            </div>
                                            <div class="w-2/3 ml-2">
                                                <label for="total_discount"
                                                    class="mb-2 block text-sm font-medium text-gray-900">
                                                    Total Diskon
                                                </label>
                                                <input type="text" min="0" name="total_discounts"
                                                    id="total_discount"
                                                    class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                                                    placeholder="Total Diskon" readonly>
                                            </div>
                                        </div>
                                        <div class="mb-3 flex justify-between">
                                            <div class="w-1/3 mr-2">
                                                <label for="tax"
                                                    class="mb-2 block text-sm font-medium text-gray-900">
                                                    PPN (%)
                                                </label>
                                                <input type="text" min="0" name="tax" id="tax"
                                                    class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                                                    placeholder="PPN" onkeyup="change_tax()" value="0">
                                            </div>
                                            <div class="w-2/3 ml-2">
                                                <label for="total_tax"
                                                    class="mb-2 block text-sm font-medium text-gray-900">
                                                    Total PPN
                                                </label>
                                                <input type="text" min="0" name="total_tax" id="total_tax"
                                                    class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                                                    placeholder="Total PPN" readonly>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="total_payment"
                                                class="mb-2 block text-sm font-medium text-gray-900">
                                                Total Harga Yang Harus Dibayar
                                            </label>
                                            <input type="text" min="0" name="total_payment" id="total_payment"
                                                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                                                placeholder="Total Harga Yang Harus Dibayar" readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label for="payment"
                                                class="mb-2 block text-sm font-medium text-gray-900">Pembayaran</label>
                                            <input type="text" min="0" name="payment" id="payment"
                                                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                                                placeholder="Pembayaran" onkeyup="count_change_payment()">
                                        </div>
                                        <div class="">
                                            <label for="change"
                                                class="mb-2 block text-sm font-medium text-gray-900">Kembalian</label>
                                            <input type="text" min="0" name="change" id="change"
                                                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                                                placeholder="Kembalian" readonly>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="mb-2 flex items-center gap-3 text-sm font-medium text-gray-900">
                                            <input type="checkbox" name="is_print" value="1" id="is_print"
                                                class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                            Cetak Struk
                                        </label>
                                    </div>
                                </div>
                                <!-- Modal footer -->
                                <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b">
                                    <button onclick="submit_order()" id="button-confirm_order" hidden type="button"
                                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                                        Konfirmasi Order
                                    </button>
                                    <button id="button-close_order" data-modal-hide="modal-order" type="button"
                                        class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100">Tutup</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <button id="trigger-drawer-confirm_order" data-drawer-target="drawer-confirm_order"
        data-drawer-show="drawer-confirm_order" aria-controls="drawer-confirm_order" data-drawer-placement="right"
        class="hidden">
    </button>
    <div id="drawer-confirm_order"
        class="fixed right-0 top-0 z-40 h-screen w-full max-w-xs translate-x-full overflow-y-auto bg-white p-4 transition-transform"
        tabindex="-1" aria-labelledby="drawer-label" aria-hidden="true">
        <h5 id="drawer-label" class="inline-flex items-center text-sm font-semibold uppercase text-gray-500">Konfirmasi
            Order
        </h5>
        <button type="button" data-drawer-dismiss="drawer-confirm_order" aria-controls="drawer-confirm_order"
            class="absolute right-2.5 top-2.5 inline-flex items-center rounded-lg bg-transparent p-1.5 text-sm text-gray-400 hover:bg-gray-200 hover:text-gray-900">
            <x-fas-info-circle aria-hidden="true" class="h-5 w-5" />
            <span class="sr-only">Tutup</span>
        </button>

        <x-fas-circle-exclamation id="icon_fas-circle-exclamation" class="mb-4 mt-8 h-10 w-10 text-gray-400" />
        <h3 id="header-drawer" class="mb-3 text-lg text-gray-500">
            Apakah anda yakin untuk melakukan konfirmasi order ini?
        </h3>
        <button id="button-drawer-confirm" type="submit" data-type="button-confirm_order"
            class="mr-2 inline-flex items-center rounded-lg bg-red-600 px-3 py-2.5 text-center text-sm font-medium text-white hover:bg-red-800 focus:ring-4 focus:ring-red-300"
            data-drawer-hide="drawer-confirm_order" form="form-order">
            Ya, Saya Yakin
        </button>
        <button id="button-drawer-close" type="button"
            class="inline-flex items-center rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-center text-sm font-medium text-gray-900 hover:bg-gray-100 focus:ring-4 focus:ring-primary-300"
            data-drawer-hide="drawer-confirm_order">
            Tidak, Batalkan
        </button>
    </div>
@endsection

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

        let sequence = 0;
        let dataProduct = [];
        let modalProducts = [];
        let selectedProductIndex = 0;

        const updateConfirmTotalPayment = () => {
            if (sequence > 0) {
                let totalPrice = 0;
                $('[id^="total_price-"]').each(function() {
                    totalPrice += update_to_number($(this).val());
                });

                $('#confirm-total_payment').val('Rp. ' + update_to_format_rupiah(totalPrice));
            } else {
                $('#confirm-total_payment').val(0);
            }
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

        const updateDataItem = (sequenceNumber) => {
            const quantity = document.getElementById(`quantity-${sequenceNumber}`).value;
            const price = update_to_number(document.getElementById(`price-${sequenceNumber}`).value);
            const discount = document.getElementById(`discount-${sequenceNumber}`).value;

            const totalPrice = price * quantity;
            document.getElementById(`total_base_price-${sequenceNumber}`).value = update_to_format_rupiah(totalPrice);
            document.getElementById(`total_discount_price-${sequenceNumber}`).value = update_to_format_rupiah(
                totalPrice * discount / 100);
            document.getElementById(`total_price-${sequenceNumber}`).value = update_to_format_rupiah(totalPrice - (
                totalPrice * discount / 100));
            updateConfirmTotalPayment();
        }

        const updateUom = (event, sequenceNumber) => {
            const value = event.value;
            const dataProductSelected = dataProduct[sequenceNumber];
            const dataProductDetail = dataProductSelected.find(item => item.id == value);

            const quantity = document.getElementById(`quantity-${sequenceNumber}`).value;
            document.getElementById(`product_detail_id-${sequenceNumber}`).value = value;
            document.getElementById(`price-${sequenceNumber}`).value = update_to_format_rupiah(dataProductDetail.price);
            document.getElementById(`discount-${sequenceNumber}`).value = dataProductDetail.discount;

            const totalPrice = dataProductDetail.price * quantity;
            document.getElementById(`total_base_price-${sequenceNumber}`).value = update_to_format_rupiah(totalPrice);
            document.getElementById(`total_discount_price-${sequenceNumber}`).value = update_to_format_rupiah(
                totalPrice * dataProductDetail.discount / 100);
            document.getElementById(`total_price-${sequenceNumber}`).value = update_to_format_rupiah(totalPrice - (
                totalPrice * dataProductDetail.discount / 100));
            updateConfirmTotalPayment();
        }

        const addProductToOrder = (data) => {
            dataProduct[sequence] = data.product_detail;

            const uomOptions = data.product_detail
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

            let body = `
        <tr>
          <td class="whitespace-nowrap p-4 text-sm font-normal text-gray-500">
            ${data.code}
            <input type="text" name="product_id[${sequence}]" id="product_id-${sequence}"
              class="block w-full rounded-lg border border-gray-300 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
              value="${data.id}" hidden>
          </td>
          <td class="whitespace-nowrap p-4 text-sm font-normal text-gray-500">
            ${data.name}
          </td>
          <td class="whitespace-nowrap p-4 text-sm font-normal text-gray-500">
            <input type="number" min="0" value="1" name="quantity[${sequence}]" id="quantity-${sequence}" oninput="updateDataItem(${sequence})"
              class="block w-full rounded-lg border border-gray-300 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
              placeholder="jumlah">
          </td>
          <td class="whitespace-nowrap p-4 text-sm font-normal text-gray-500">
            <input type="text" name="product_detail_id[${sequence}]" id="product_detail_id-${sequence}" onkeyup="updateDataItem(${sequence})"
              class="block w-full rounded-lg border border-gray-300 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
              value="${data.product_detail[0].id}" hidden>
            <select id="select_product_id-${sequence}" name="select_product_id[${sequence}]"
              class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
              onchange="updateUom(this, ${sequence})"
            >
              ${uomOptions}
            </select>
          </td>
          <td class="whitespace-nowrap p-4 text-sm font-normal text-gray-500">
            <input type="text" name="price[${sequence}]" id="price-${sequence}" onkeyup="keyup_rupiah(this);updateDataItem(${sequence})"
              class="block w-full rounded-lg border border-gray-300 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
              placeholder="Harga" value="${update_to_format_rupiah(data.product_detail[0].price)}">
          </td>
          <td class="whitespace-nowrap p-4 text-sm font-normal text-gray-500">
            <input type="text" name="total_base_price[${sequence}]" id="total_base_price-${sequence}"
              class="block w-full rounded-lg border bg-gray-100 border-gray-300 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
              placeholder="Total" readonly  value="${update_to_format_rupiah(data.product_detail[0].price)}">
          </td>
          <td class="whitespace-nowrap p-4 text-sm font-normal text-gray-500">
            <input type="number" min="0" name="discount[${sequence}]" id="discount-${sequence}" oninput="updateDataItem(${sequence})"
              class="block w-full rounded-lg border border-gray-300 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
              placeholder="Diskon" value="${data.product_detail[0].discount}">
          </td>
          <td class="whitespace-nowrap p-4 text-sm font-normal text-gray-500">
            <input type="text" name="total_discount_price[${sequence}]" id="total_discount_price-${sequence}"
              class="block w-full rounded-lg border bg-gray-100 border-gray-300 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
              placeholder="Total" readonly  value="${update_to_format_rupiah(data.product_detail[0].price)}">
          </td>
          <td class="whitespace-nowrap p-4 text-sm font-normal text-gray-500">
            <input type="text" name="total_price[${sequence}]" id="total_price-${sequence}"
              class="block w-full rounded-lg border bg-gray-100 border-gray-300 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
              placeholder="Total" readonly  value="${update_to_format_rupiah(data.product_detail[0].price - (data.product_detail[0].price * data.product_detail[0].discount / 100))}">
          </td>
        </tr>
      `;
            const queryBodyOrderTable = document.getElementById('body-order');
            queryBodyOrderTable.insertAdjacentHTML('beforeend', body);

            updateDataItem(sequence);
            sequence++;
            updateConfirmTotalPayment();
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

        const open_modal_confirm_order = () => {
            if (sequence > 0) {
                let totalPrice = 0;
                $('[id^="total_price-"]').each(function() {
                    totalPrice += update_to_number($(this).val());
                });

                $('#total_price_item').val(update_to_format_rupiah(totalPrice));
                $('#total_payment').val(update_to_format_rupiah(totalPrice));
                $('#button-confirm_order').attr('hidden', false);
                $('#form-confirm_order').attr('hidden', false);
                $('#false-confirm_order').attr('hidden', true);
            } else {
                $('#total_price_item').val(0);
                $('#button-confirm_order').attr('hidden', true);
                $('#form-confirm_order').attr('hidden', true);
                $('#false-confirm_order').attr('hidden', false);
            }
        }

        const change_discount = () => {
            let total_price_item = parseInt(($('#total_price_item').val()).replaceAll('.', ''));
            let payment = parseInt(($('#payment').val()).replaceAll('.', ''));
            let tax = parseInt(($('#total_tax').val()).replaceAll('.', ''));
            tax = isNaN(tax) ? 0 : tax;
            let discount = parseInt(($('#discount').val()).replaceAll('.', ''));
            discount = discount ? (discount > 100 ? 100 : discount) : 0;
            let total_discount = Math.ceil((Number(total_price_item) * Number(discount)) / 100);
            let total_payment = Number(total_price_item) - Number(total_discount) - tax;

            $('#total_discount').val(update_to_format_rupiah(total_discount));
            $('#discount').val(update_to_format_rupiah(discount));
            $('#total_payment').val(update_to_format_rupiah(total_payment));
            $('#change').val(update_to_format_rupiah(payment > 0 ? payment - total_payment : 0));
        }

        const change_tax = () => {
            let total_price_item = parseInt(($('#total_price_item').val()).replaceAll('.', ''));
            let payment = parseInt(($('#payment').val()).replaceAll('.', ''));
            let discount = parseInt(($('#total_discount').val()).replaceAll('.', ''));
            discount = isNaN(discount) ? 0 : discount;
            let total_payment = Number(total_price_item) - Number(discount);
            let tax = parseInt(($('#tax').val()).replaceAll('.', ''));
            tax = tax ? (tax > 100 ? 100 : tax) : 0;
            let total_tax = Math.ceil((total_payment * Number(tax)) / 100);

            let total_payment_after_tax = total_payment - total_tax;

            $('#total_tax').val(update_to_format_rupiah(total_tax));
            $('#tax').val(update_to_format_rupiah(tax));
            $('#total_payment').val(update_to_format_rupiah(total_payment_after_tax));
            $('#change').val(update_to_format_rupiah(payment > 0 ? payment - total_payment_after_tax : 0));
        }

        const count_change_payment = () => {
            let total_payment = parseInt(($('#total_payment').val()).replaceAll('.', ''));
            let payment = parseInt(($('#payment').val()).replaceAll('.', ''));
            $('#change').val(update_to_format_rupiah(Number(payment) - Number(total_payment)));
            $('#payment').val(update_to_format_rupiah(payment));
        };

        const submit_order = () => {
            let customer_id = $('#customer_id').val();
            let condition_success = true,
                message = '';

            let total_payment = Number($('#total_payment').val());
            let payment = Number($('#payment').val());
            if (total_payment > payment) {
                message = 'Opps Terjadi kesalahan pada bagian pembayaran!';
                condition_success = false;
            }

            if (condition_success) {
                $('#header-drawer').html('Apakah Anda yakin ingin mengonfirmasi pesanan ini?');
                $('#header-drawer').removeClass('text-red-500');
                $('#header-drawer').addClass('text-gray-500');
                $('#icon_fas-circle-exclamation').removeClass('text-red-500');
                $('#icon_fas-circle-exclamation').addClass('text-gray-500');
                $('#button-drawer-confirm').removeClass('hidden');
                $('button-drawer-close').html('Tidak, Batalkan');
            } else {
                $('#header-drawer').html(message);
                $('#header-drawer').addClass('text-red-500');
                $('#header-drawer').removeClass('text-gray-500');
                $('#icon_fas-circle-exclamation').addClass('text-red-500');
                $('#icon_fas-circle-exclamation').removeClass('text-gray-500');
                $('#button-drawer-confirm').addClass('hidden');
                $('button-drawer-close').html('Tutup');
            }

            $('#button-close_order').trigger('click');
            $('#trigger-drawer-confirm_order').trigger('click');
        };
    </script>
@endpush
