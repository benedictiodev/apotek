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
      <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl mb-4">Pembaruan Data Produk</h1>
      <a href="{{ route('dashboard.product') }}"
        class="w-fit shadow-lg justify-center rounded-lg bg-slate-400 px-5 py-1.5 text-center text-sm font-medium text-white hover:bg-slate-800 focus:outline-none focus:ring-4 focus:ring-slate-300">
        Kembali
      </a>
    </div>

    <div
      class="p-4 bg-white rounded-lg shadow-lg 2xl:col-span-2 sm:p-6">
      <div class="mb-4">
        <form action="{{ route('dashboard.product.update', ['id' => $data->id]) }}" method="POST" enctype="multipart/form-data">
          @method('PUT')
          @csrf
          <div class="space-y-2">
            <div class="flex justify-between gap-4">
              <div class="flex-1">
                <div>
                  <label for="code" class="mb-2 block text-sm font-medium text-gray-900">Kode
                    Produk</label>
                  <input type="text" name="code" id="code"
                    class="block w-full rounded-lg border border-gray-300 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                    placeholder="Kode Produk" required value="{{ old('code', $data->code) }}">
                </div>

                <div>
                  <label for="name" class="my-2 block text-sm font-medium text-gray-900">Nama
                    Produk</label>
                  <input type="text" name="name" id="name"
                    class="block w-full rounded-lg border border-gray-300 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                    placeholder="Nama Produk" required value="{{ old('name', $data->name) }}">
                </div>

                <div>
                  <label for="name" class="my-2 block text-sm font-medium text-gray-900">Kategori</label>
                  <select id="product_category_id" name="product_category_id"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                    required>
                    <option disabled value="" selected>~ Pilih Kategori ~</option>
                    @foreach ($category as $item)
                      <option value="{{ $item->id }}" @if (old('product_category_id', $data->product_category_id) == $item->id) selected @endif>
                        {{ $item->name }}</option>
                    @endforeach
                  </select>
                </div>

                <div>
                  <label for="name" class="my-2 block text-sm font-medium text-gray-900">Satuan Dasar</label>
                  <select id="base_uom_id" name="base_uom_id" onchange="changeBaseUom(this)"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                    required>
                    <option disabled value="" selected>~ Pilih Satuan ~</option>
                    @foreach ($uom as $item)
                      <option value="{{ $item->id }}" @if (old('base_uom_id', $data->base_uom_id) == $item->id) selected @endif>
                        {{ $item->name }}</option>
                    @endforeach
                  </select>
                </div>

                <div>
                  <label for="purchase_price" class="my-2 block text-sm font-medium text-gray-900">Harga Beli</label>
                  <input type="text" name="purchase_price" id="purchase_price" onkeyup="keyup_rupiah(this);checkPrice(this)"
                    class="block w-full rounded-lg border border-gray-300 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                    placeholder="Harga Beli" required  value="{{ old('purchase_price', number_format($data->purchase_price, 0, ',', '.')) }}">
                </div>
              </div>

              <div class="flex-1">
                <div>
                  <label for="supplier_id" class="mb-2 block text-sm font-medium text-gray-900">Supplier</label>
                  <select id="supplier_id" name="supplier_id"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                    required>
                    <option disabled value="" selected>~ Pilih Satuan ~</option>
                    @foreach ($supplier as $item)
                      <option value="{{ $item->id }}" @if (old('supplier_id', $data->supplier_id) == $item->id) selected @endif>
                        {{ $item->code }} - {{ $item->name }}</option>
                    @endforeach
                  </select>
                </div>

                <div>
                  <label for="stock" class="my-2 block text-sm font-medium text-gray-900">Stok</label>
                  <input type="number" min="0" name="stock" id="stock"
                    class="block w-full rounded-lg border border-gray-300 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                    placeholder="Stok" required value="{{ old('stock', $data->stock) }}">
                </div>

                <div>
                  <label for="stock_minimal" class="my-2 block text-sm font-medium text-gray-900">Stok Minimal</label>
                  <input type="number" min="0" name="stock_minimal" id="stock_minimal"
                    class="block w-full rounded-lg border border-gray-300 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                    placeholder="Stok Minimal" required value="{{ old('stock_minimal', $data->stock_minimal) }}">
                </div>

                <div>
                  <label for="location" class="my-2 block text-sm font-medium text-gray-900">Lokasi / Rak</label>
                  <input type="text" name="location" id="location"
                    class="block w-full rounded-lg border border-gray-300 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                    placeholder="Lokasi / Rak" value="{{ old('location', $data->location) }}">
                </div>

                <div>
                  <label for="expired_date" class="my-2 block text-sm font-medium text-gray-900">Tanggal Kadaluarsa</label>
                  <input type="date" name="expired_date" id="expired_date"
                    class="block w-full rounded-lg border border-gray-300 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                    placeholder="Tanggal Kadaluarsa" required value="{{ old('expired_date', $data->expired_date) }}">
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
                          <th scope="col" class="p-4 text-start text-base font-bold uppercase text-white">
                            Diskon (%)
                          </th>
                        </tr>
                      </thead>
                      <tbody class="divide-y divide-gray-200 bg-white">
                        <tr>
                          <td class="whitespace-nowrap p-4 text-sm font-normal text-gray-500">
                            <input type="text" id="uom_id-base-1"
                              class="block w-full rounded-lg border border-gray-300 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                              placeholder="Pilih Satuan" disabled value="{{ $selectedUom->name ?? null }}">
                            <input type="text" name="uom_id[0]" id="uom_id-1"
                              class="block w-full rounded-lg border border-gray-300 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                              hidden value="{{ old('uom_id[0]', $dataDetail[0]?->uom_id ?? null) }}">
                            <input type="text" name="detail_id[0]"
                              class="block w-full rounded-lg border border-gray-300 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                              hidden value="{{ old('detail_id[0]', $dataDetail[0]?->id ?? null) }}">
                          </td>
                          <td class="whitespace-nowrap p-4 text-sm font-normal text-gray-500">
                            <input type="number" min="0" name="contains[]" id="contains-1"
                              class="block w-full rounded-lg border border-gray-300 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                              placeholder="Isi" readonly value="{{ old('contains[0]', $dataDetail[0]?->contains ?? null) }}">
                          </td>
                          <td class="whitespace-nowrap p-4 text-sm font-normal text-gray-500">
                            <input type="text" name="price[]" id="price-1" onkeyup="keyup_rupiah(this);checkPrice(this)"
                              class="block w-full rounded-lg border border-gray-300 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                              placeholder="Harga Jual" required value="{{ number_format(old('price[0]', $dataDetail[0]?->price ?? null), 0, ',', '.') }}">
                          </td>
                          <td class="whitespace-nowrap p-4 text-sm font-normal text-gray-500">
                            <input type="number" min="0" name="profit[]" id="profit-1" onkeyup="checkPrice(this)"
                              class="block w-full rounded-lg border border-gray-300 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                              placeholder="Laba" value="{{
                                data_get($dataDetail, '0.contains', 0) > 0 &&
                                $data->purchase_price > 0
                                ? (
                                    (
                                        data_get($dataDetail, '0.price', 0)
                                        / data_get($dataDetail, '0.contains', 1)
                                        - $data->purchase_price
                                    )
                                    / $data->purchase_price
                                ) * 100
                                : 0
                              }}">
                          </td>
                          <td class="whitespace-nowrap p-4 text-sm font-normal text-gray-500">
                            <input type="number" min="0" name="discount[]" id="discount-1"
                              class="block w-full rounded-lg border border-gray-300 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                              placeholder="Diskon" value="{{ old('discount[0]', $dataDetail[0]?->discount ?? null) }}">
                          </td>
                        </tr>
                        <tr>
                          <td class="whitespace-nowrap p-4 text-sm font-normal text-gray-500">
                            <select id="uom_id-2" name="uom_id[1]"
                              class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                              >
                              <option disabled value="" selected>~ Pilih Satuan ~</option>
                              @foreach ($uom as $item)
                                <option value="{{ $item->id }}" @if (old('uom_id[1]', $dataDetail[1]?->uom_id ?? null) == $item->id) selected @endif>
                                  {{ $item->name }}</option>
                              @endforeach
                            </select>
                            <input type="text" name="detail_id[1]"
                              class="block w-full rounded-lg border border-gray-300 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                              hidden value="{{ old('detail_id[1]', $dataDetail[1]?->id ?? null) }}">
                          </td>
                          <td class="whitespace-nowrap p-4 text-sm font-normal text-gray-500">
                            <input type="number" min="0" name="contains[]" id="contains-2"
                              class="block w-full rounded-lg border border-gray-300 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                              placeholder="Isi" value="{{ old('contains[1]', $dataDetail[1]?->contains ?? null) }}">
                          </td>
                          <td class="whitespace-nowrap p-4 text-sm font-normal text-gray-500">
                            <input type="text" name="price[]" id="price-2" onkeyup="keyup_rupiah(this);checkPrice(this)"
                              class="block w-full rounded-lg border border-gray-300 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                              placeholder="Harga Jual" value="{{ number_format(old('price[1]', $dataDetail[1]?->price ?? null), 0, ',', '.') }}">
                          </td>
                          <td class="whitespace-nowrap p-4 text-sm font-normal text-gray-500">
                            <input type="number" min="0" name="profit[]" id="profit-2" onkeyup="checkPrice(this)"
                              class="block w-full rounded-lg border border-gray-300 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                              placeholder="Laba" value="{{
                                data_get($dataDetail, '1.contains', 0) > 0 &&
                                $data->purchase_price > 0
                                ? (
                                    (
                                        data_get($dataDetail, '1.price', 0)
                                        / data_get($dataDetail, '1.contains', 1)
                                        - $data->purchase_price
                                    )
                                    / $data->purchase_price
                                ) * 100
                                : 0
                              }}">
                          </td>
                          <td class="whitespace-nowrap p-4 text-sm font-normal text-gray-500">
                            <input type="number" min="0" name="discount[]" id="discount-2"
                              class="block w-full rounded-lg border border-gray-300 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                              placeholder="Diskon" value="{{ old('discount[1]', $dataDetail[1]?->discount ?? null) }}">
                          </td>
                        </tr>
                        <tr>
                          <td class="whitespace-nowrap p-4 text-sm font-normal text-gray-500">
                            <select id="uom_id-3" name="uom_id[2]"
                              class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                              >
                              <option disabled value="" selected>~ Pilih Satuan ~</option>
                              @foreach ($uom as $item)
                                <option value="{{ $item->id }}" @if (old('uom_id[2]', $dataDetail[2]?->uom_id ?? null) == $item->id) selected @endif>
                                  {{ $item->name }}</option>
                              @endforeach
                            </select>
                            <input type="text" name="detail_id[2]"
                              class="block w-full rounded-lg border border-gray-300 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                              hidden value="{{ old('detail_id[2]', $dataDetail[2]?->id ?? null) }}">
                          </td>
                          <td class="whitespace-nowrap p-4 text-sm font-normal text-gray-500">
                            <input type="number" min="0" name="contains[]" id="contains-3"
                              class="block w-full rounded-lg border border-gray-300 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                              placeholder="Isi" value="{{ old('contains[2]', $dataDetail[2]?->contains ?? null) }}">
                          </td>
                          <td class="whitespace-nowrap p-4 text-sm font-normal text-gray-500">
                            <input type="text" name="price[]" id="price-3" onkeyup="keyup_rupiah(this);checkPrice(this)"
                              class="block w-full rounded-lg border border-gray-300 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                              placeholder="Harga Jual" value="{{ number_format(old('price[2]', $dataDetail[2]?->price ?? null), 0, ',', '.') }}">
                          </td>
                          <td class="whitespace-nowrap p-4 text-sm font-normal text-gray-500">
                            <input type="number" min="0" name="profit[]" id="profit-3" onkeyup="checkPrice(this)"
                              class="block w-full rounded-lg border border-gray-300 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                              placeholder="Laba" value="{{
                                data_get($dataDetail, '2.contains', 0) > 0 &&
                                $data->purchase_price > 0
                                ? (
                                    (
                                        data_get($dataDetail, '2.price', 0)
                                        / data_get($dataDetail, '2.contains', 1)
                                        - $data->purchase_price
                                    )
                                    / $data->purchase_price
                                ) * 100
                                : 0
                              }}">
                          </td>
                          <td class="whitespace-nowrap p-4 text-sm font-normal text-gray-500">
                            <input type="number" min="0" name="discount[]" id="discount-3"
                              class="block w-full rounded-lg border border-gray-300 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                              placeholder="Diskon" value="{{ old('discount[2]', $dataDetail[2]?->discount ?? null) }}">
                          </td>
                        </tr>
                        <tr>
                          <td class="whitespace-nowrap p-4 text-sm font-normal text-gray-500">
                            <select id="uom_id-4" name="uom_id[3]"
                              class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                              >
                              <option disabled value="" selected>~ Pilih Satuan ~</option>
                              @foreach ($uom as $item)
                                <option value="{{ $item->id }}" @if (old('uom_id[3]', $dataDetail[3]?->uom_id ?? null) == $item->id) selected @endif>
                                  {{ $item->name }}</option>
                              @endforeach
                            </select>
                            <input type="text" name="detail_id[3]"
                              class="block w-full rounded-lg border border-gray-300 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                              hidden value="{{ old('detail_id[3]', $dataDetail[3]?->id ?? null) }}">
                          </td>
                          <td class="whitespace-nowrap p-4 text-sm font-normal text-gray-500">
                            <input type="number" min="0" name="contains[]" id="contains-4"
                              class="block w-full rounded-lg border border-gray-300 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                              placeholder="Isi" value="{{ old('contains[3]', $dataDetail[3]?->contains ?? null) }}">
                          </td>
                          <td class="whitespace-nowrap p-4 text-sm font-normal text-gray-500">
                            <input type="text" name="price[]" id="price-4" onkeyup="keyup_rupiah(this);checkPrice(this)"
                              class="block w-full rounded-lg border border-gray-300 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                              placeholder="Harga Jual" value="{{ number_format(old('price[3]', $dataDetail[3]?->price ?? null), 0, ',', '.') }}">
                          </td>
                          <td class="whitespace-nowrap p-4 text-sm font-normal text-gray-500">
                            <input type="number" min="0" name="profit[]" id="profit-4" onkeyup="checkPrice(this)"
                              class="block w-full rounded-lg border border-gray-300 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                              placeholder="Laba" value="{{
                                data_get($dataDetail, '3.contains', 0) > 0 &&
                                $data->purchase_price > 0
                                ? (
                                    (
                                        data_get($dataDetail, '3.price', 0)
                                        / data_get($dataDetail, '3.contains', 1)
                                        - $data->purchase_price
                                    )
                                    / $data->purchase_price
                                ) * 100
                                : 0
                              }}">
                          </td>
                          <td class="whitespace-nowrap p-4 text-sm font-normal text-gray-500">
                            <input type="number" min="0" name="discount[]" id="discount-4"
                              class="block w-full rounded-lg border border-gray-300 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                              placeholder="Diskon" value="{{ old('discount[3]', $dataDetail[3]?->discount ?? null) }}">
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>

            <button type="submit"
              class="w-fit shadow-lg justify-center rounded-lg bg-blue-700 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300">
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
      previewImage.classList.add("h-64")
      previewImage.classList.add("mb-2")
      previewImage.style.display = "block"
    }

    const uom = @json($uom);
    const changeBaseUom = (event) => {
      let input = document.querySelector('#uom_id-1');
      input.value = event.value;

      let getUom = uom.find(function(item) {
        return item.id == event.value;
      });
      let inputBase = document.querySelector('#uom_id-base-1');
      inputBase.value = getUom.name;
    }

    const checkPrice = (event) => {
      if (event.id == 'purchase_price') {
        let purchasePrice = update_to_number(event.value);
        let queryPrice = document.getElementsByName('price[]');
        queryPrice.forEach(element => {
          if (element.value != '' && element.value != 0) {
            let queryId = element.id;
            let number = queryId.split('-')[1];
            let queryContain = document.getElementById(`contains-${number}`);
            let contains = queryContain.value ?? 0;
            if (contains > 0) {
              let elementValue = update_to_number(element.value);
              let profit = elementValue - purchasePrice;
              let percentage = (profit / purchasePrice) * 100;
              let queryProfit = document.getElementById(`profit-${number}`);
              queryProfit.value = percentage;
            }
          }
        });
      } else {
        let queryPurchasePrice = document.getElementById('purchase_price');
        if (queryPurchasePrice.value != '' && queryPurchasePrice.value != 0) {
          let purchasePrice = update_to_number(queryPurchasePrice.value);
          let elementValue = update_to_number(event.value);
          let queryId = event.id;
          let number = queryId.split('-')[1];

          let queryContain = document.getElementById(`contains-${number}`);
          let contains = queryContain.value ?? 0;
          if (contains > 0) {
            if (queryId.split('-')[0] == 'profit') {
              let price = (purchasePrice * (1 + elementValue / 100)) * contains;
              let queryPrice = document.getElementById(`price-${number}`);
              queryPrice.value = update_to_format_rupiah(price);
            } else if (queryId.split('-')[0] == 'price') {
              let profit = (elementValue / contains) - purchasePrice;
              let percentage = (profit / purchasePrice) * 100;
              let queryProfit = document.getElementById(`profit-${number}`);
              queryProfit.value = percentage;
            }
          }
        }
      }
    }
  </script>
@endpush
