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

    <div
      class="p-4 bg-white rounded-lg shadow-lg 2xl:col-span-2 sm:p-6">
      <div class="mb-4">
        <div class="mb-4">
          <div class="relative mt-1 w-72 sm:w-64 xl:w-96">
            <form action="#" onsubmit="searchProduct(event)">
              <input type="text" name="search" id="products-search"
                class="block w-full rounded-lg border border-gray-300 p-2.5 text-gray-900 focus:border-primary-500 focus:ring-primary-500 sm:text-sm"
                placeholder="Cari Data Product" autofocus>
            </form>
          </div>
        </div>
        <form action="{{ route('dashboard.product.store') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="space-y-2">
            <div class="flex flex-col">
              <div class="overflow-x-auto">
                <div class="inline-block min-w-full align-middle">
                  <div class="overflow-hidden shadow rounded-t-lg">
                    <table class="min-w-full table-fixed divide-y divide-gray-200">
                      <thead class="bg-sky-300">
                        <tr>
                          <th scope="col" class="p-4 text-start text-base font-bold uppercase text-white">
                            Kode
                          </th>
                          <th scope="col" class="p-4 text-start text-base font-bold uppercase text-white">
                            Nama
                          </th>
                          <th scope="col" class="p-4 text-start text-base font-bold uppercase text-white" width="10%">
                            Jumlah
                          </th>
                          <th scope="col" class="p-4 text-start text-base font-bold uppercase text-white" width="13%">
                            Satuan
                          </th>
                          <th scope="col" class="p-4 text-start text-base font-bold uppercase text-white" width="15%">
                            Harga
                          </th>
                          <th scope="col" class="p-4 text-start text-base font-bold uppercase text-white" width="12%">
                            Diskon (%)
                          </th>
                          <th scope="col" class="p-4 text-start text-base font-bold uppercase text-white" width="15%">
                            Total
                          </th>
                        </tr>
                      </thead>
                      <tbody id="body-order" class="divide-y divide-gray-200 bg-white"></tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>

            <button type="submit"
              class="w-fit shadow-lg justify-center rounded-lg bg-blue-700 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300">
              Tambahkan
            </button>
          </div>
        </form>
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

    const sequence = 0;
    let dataProduct = [];

    const updateDataItem = (sequenceNumber) => {
      const quantity = document.getElementById(`quantity-${sequenceNumber}`).value;
      const price = update_to_number(document.getElementById(`price-${sequenceNumber}`).value);
      const discount = document.getElementById(`discount-${sequenceNumber}`).value;

      const totalPrice = price * quantity;
      document.getElementById(`total_price-${sequenceNumber}`).value = update_to_format_rupiah(totalPrice - (totalPrice * discount / 100));
    }

    const updateUom = (event, sequenceNumber) => {
      const value = event.value;
      const dataProductSelected = dataProduct[sequenceNumber];
      const dataProductDetail = dataProductSelected.find(item => item.id == value);

      const quantity = document.getElementById(`quantity-${sequenceNumber}`).value;
      document.getElementById(`uom_id-${sequenceNumber}`).value = value;
      document.getElementById(`price-${sequenceNumber}`).value = update_to_format_rupiah(dataProductDetail.price);
      document.getElementById(`discount-${sequenceNumber}`).value = dataProductDetail.discount;

      const totalPrice = dataProductDetail.price * quantity;
      document.getElementById(`total_price-${sequenceNumber}`).value = update_to_format_rupiah(totalPrice - (totalPrice * dataProductDetail.discount / 100));
    }

    const searchProduct = async (event) => {
      event.preventDefault();

      const input = document.getElementById('products-search');
      const keyword = input.value.trim();

      const response = await axios.get(
        '/dashboard/product/search',
        {
          params: {
            search: keyword
          }
        }
      );
      
      const resultData = JSON.parse(response.data.data);
      
      if (resultData.length == 1) {
        const data = resultData[0];
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
              <input type="text" name="uom_id[${sequence}]" id="uom_id-${sequence}" onkeyup="updateDataItem(${sequence})"
                class="block w-full rounded-lg border border-gray-300 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                value="${data.product_detail[0].uom_id}" hidden>
              <select id="select_uom-${sequence}" name="select_uom[${sequence}]"
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
              <input type="number" min="0" name="discount[${sequence}]" id="discount-${sequence}" oninput="updateDataItem(${sequence})"
                class="block w-full rounded-lg border border-gray-300 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                placeholder="Diskon" value="${data.product_detail[0].discount}">
            </td>
            <td class="whitespace-nowrap p-4 text-sm font-normal text-gray-500">
              <input type="text" name="total_price[${sequence}]" id="total_price-${sequence}"
                class="block w-full rounded-lg border border-gray-300 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600"
                placeholder="Total" readonly  value="${update_to_format_rupiah(data.product_detail[0].price - (data.product_detail[0].price * data.product_detail[0].discount / 100))}">
            </td>
          </tr>
        `;
        const queryBodyOrderTable = document.getElementById('body-order');
        queryBodyOrderTable.insertAdjacentHTML('beforeend', body);
      }

      document.getElementById('products-search').value = '';
    }
  </script>
@endpush
