<aside id="sidebar"
  class="fixed top-0 left-0 z-[11] flex flex-col flex-shrink-0 w-64 h-full pt-16 font-normal duration-75 lg:flex transition-width hidden"
  aria-label="Sidebar">
  <div class="relative flex flex-col flex-1 min-h-0 pt-0 bg-white shadow-lg rounded-br-xl">
    <div class="flex flex-col flex-1 pt-5 pb-4 overflow-y-auto">
      <div class="flex-1 px-3 space-y-1 bg-white divide-y divide-gray-200">
        <ul class="pb-2 space-y-2">
          <li>
            <a href="{{ route('dashboard') }}"
              class="flex sidebar_base group {{ Request::route()->getName() == 'dashboard' ? 'active_sidebar' : '' }}">
              <x-fas-home class="w-6 h-6 text-gray-500 transition duration-75 {{ Request::route()->getName() == 'dashboard' ? 'text-white' : '' }}" />
              <span class="ml-3" sidebar-toggle-item>Beranda</span>
            </a>
          </li>
          <li>
            <a href="{{ route('dashboard.product') }}"
              class="flex sidebar_base group {{ str_contains(Request::route()->getName(), 'dashboard.product') ? 'active_sidebar' : '' }}">
              <x-fas-box class="w-6 h-6 text-gray-500 transition duration-75 {{ str_contains(Request::route()->getName(), 'dashboard.product') ? 'text-white' : '' }}" />
              <span class="ml-3" sidebar-toggle-item>Produk</span>
            </a>
          </li>

          {{-- FINANCE --}}
          {{-- @canany([
            'keuangan-dana-lihat', 
            'keuangan-arus kas harian-lihat', 
            'keuangan-arus kas bulanan-lihat'
          ]) --}}
            <li>
              <button type="button"
                class="flex sidebar_base group  p-2 {{ str_contains(Request::route()->getName(), 'dashboard.finance.') ? 'active_sidebar' : '' }}"
                aria-controls="dropdown-finance" data-collapse-toggle="dropdown-finance">
                <x-fas-money-bill class="w-6 h-6 text-gray-500 transition duration-75 mr-1 {{ str_contains(Request::route()->getName(), 'dashboard.finance.') ? 'text-white' : '' }}" />
                <span class="flex-1 ml-3 text-left whitespace-nowrap" sidebar-toggle-item>Keuangan</span>
                <x-fas-chevron-down
                  class="w-4 h-4 text-gray-500 transition duration-75 mr-1 {{ str_contains(Request::route()->getName(), 'dashboard.finance.') ? 'text-white' : '' }}" />
              </button>
              <ul id="dropdown-finance"
                class="{{ str_contains(Request::route()->getName(), 'dashboard.finance.') ? '' : 'hidden' }} py-2 space-y-2">
                @can('keuangan-dana-lihat')    
                  <li>
                    <a href="{{ route('dashboard.finance.funds') }}"
                      class="flex sidebar_base pl-11 group {{ str_contains(Request::route()->getName(), 'dashboard.finance.funds') ? 'active_sidebar' : '' }}">
                      Dana
                    </a>
                  </li>
                @endcan
                {{-- @can('keuangan-arus kas harian-lihat') --}}
                  <li>
                    <a href="{{ route('dashboard.finance.cash-flow-daily') }}"
                      class="flex sidebar_base pl-11 group {{ Request::route()->getName() == 'dashboard.finance.cash-flow-daily' ? 'active_sidebar' : '' }}">
                      Arus Kas Harian
                    </a>
                  </li>
                {{-- @endcan --}}
                {{-- @canany('keuangan-arus kas bulanan-lihat') --}}
                  <li>
                    <a href="{{ route('dashboard.finance.cash-flow-monthly') }}"
                      class="flex sidebar_base pl-11 group {{ Request::route()->getName() == 'dashboard.finance.cash-flow-monthly' ? 'active_sidebar' : '' }}">
                      Arus Kas Bulanan
                    </a>
                  </li>
                {{-- @endcanany --}}
              </ul>
            </li>
          {{-- @endcanany --}}
          {{-- END FINANCE --}}

          {{-- ORDER --}}
          {{-- @canany([
            'order-order aktif-lihat',
            'order-riwayat order-lihat',
            'order-pelaporan-lihat'
          ])   --}}
             <li>
              <a href="{{ route('dashboard.order') }}"
                class="flex sidebar_base group {{ str_contains(Request::route()->getName(), 'dashboard.order') ? 'active_sidebar' : '' }}">
                <x-fas-shopping-basket class="w-6 h-6 text-gray-500 transition duration-75 {{ str_contains(Request::route()->getName(), 'dashboard.order') ? 'text-white' : '' }}" />
                <span class="ml-3" sidebar-toggle-item>Order</span>
              </a>
            </li>
            {{-- <li>
              <button type="button"
                class="flex sidebar_base group p-2 {{ str_contains(Request::route()->getName(), 'dashboard.order') ? 'active_sidebar' : '' }}"
                aria-controls="dropdown-order" data-collapse-toggle="dropdown-order">
                <x-fas-shopping-basket
                  class="w-6 h-6 text-gray-500 transition duration-75 mr-1 {{ str_contains(Request::route()->getName(), 'dashboard.order') ? 'text-white' : '' }}" />
                <span class="flex-1 ml-3 text-left whitespace-nowrap" sidebar-toggle-item>Order</span>
                <x-fas-chevron-down class="w-4 h-4 text-gray-500 transition duration-75 mr-1 {{ str_contains(Request::route()->getName(), 'dashboard.order') ? 'text-white' : '' }}" />
              </button>
              <ul id="dropdown-order"
                class="{{ str_contains(Request::route()->getName(), 'dashboard.order') ? '' : 'hidden' }} py-2 space-y-2">
                @can('order-order aktif-lihat') 
                  <li>
                    <a href="{{ route('dashboard.order') }}"
                      class="flex sidebar_base pl-11 group {{ str_contains(Request::route()->getName(), 'dashboard.order') ? 'active_sidebar' : '' }}">
                      Order Aktif
                    </a>
                  </li>
                @endcan
                @can('order-riwayat order-lihat')
                  <li>
                    <a href="{{ route('dashboard.order.order_history') }}"
                      class="flex sidebar_base pl-11 group {{ str_contains(Request::route()->getName(), 'dashboard.order.order_history') ? 'active_sidebar' : '' }}">
                      Riwayat Order
                    </a>
                  </li>
                @endcan
                @can('order-pelaporan-lihat')
                  <li>
                    <a href="{{ route('dashboard.order.report') }}"
                      class="flex sidebar_base pl-11 group {{ str_contains(Request::route()->getName(), 'dashboard.order.report') ? 'active_sidebar' : '' }}">
                      Pelaporan
                    </a>
                  </li>
                @endcan
              </ul>
            </li> --}}
          {{-- @endcanany --}}
          {{-- END ORDER --}}

          {{-- COMPANY --}}
          @canany([
            'toko-profil-lihat',
            'toko-pengaturan-lihat'
          ])
            <li>
              <button type="button"
                class="flex sidebar_base group {{ str_contains(Request::route()->getName(), 'dashboard.company.') ? 'active_sidebar' : '' }}"
                aria-controls="dropdown-company" data-collapse-toggle="dropdown-company">
                <x-fas-building class="w-6 h-6 text-gray-500 transition duration-75 mr-1 {{ str_contains(Request::route()->getName(), 'dashboard.company.') ? 'text-white' : '' }}" />
                <span class="flex-1 ml-3 text-left whitespace-nowrap" sidebar-toggle-item>Toko</span>
                <x-fas-chevron-down
                  class="w-4 h-4 text-gray-500 transition duration-75 mr-1 {{ str_contains(Request::route()->getName(), 'dashboard.company.') ? 'text-white' : '' }}" />
              </button>
              <ul id="dropdown-company"
                class="{{ str_contains(Request::route()->getName(), 'dashboard.company.') ? '' : 'hidden' }} py-2 space-y-2">
                @can('toko-profil-lihat') 
                  <li>
                    <a href="{{ route('dashboard.company.profile') }}"
                      class="flex sidebar_base pl-11 group {{ str_contains(Request::route()->getName(), 'dashboard.company.profile') ? 'active_sidebar' : '' }}">
                      Profil
                    </a>
                  </li>
                @endcan
                @can('toko-pengaturan-lihat')
                  <li>
                    <a href="{{ route('dashboard.company.setting') }}"
                      class="flex sidebar_base pl-11 group {{ str_contains(Request::route()->getName(), 'dashboard.company.setting') ? 'active_sidebar' : '' }}">
                      Pengaturan
                    </a>
                  </li>
                @endcan
              </ul>
            </li>
          @endcanany
          {{-- END COMPANY --}}

          {{-- MASTER DATA --}}
          {{-- @canany([
            'master data-produk kategori-lihat',
            'master data-produk-lihat',
            'master data-tipe dana-lihat',
            'master data-keterangan arus kas-lihat'
          ]) --}}
            <li>
              <button type="button"
                class="flex sidebar_base group p-2 {{ str_contains(Request::route()->getName(), 'dashboard.master-data.') ? 'active_sidebar' : '' }}"
                aria-controls="dropdown-master-data" data-collapse-toggle="dropdown-master-data">
                <x-fas-database class="w-6 h-6 text-gray-500 transition duration-75 mr-1 {{ str_contains(Request::route()->getName(), 'dashboard.master-data.') ? 'text-white' : '' }}" />
                <span class="flex-1 ml-3 text-left whitespace-nowrap" sidebar-toggle-item>Master Data</span>
                <x-fas-chevron-down
                  class="w-4 h-4 text-gray-500 transition duration-75 mr-1 {{ str_contains(Request::route()->getName(), 'dashboard.master-data.') ? 'text-white' : '' }}" />
              </button>
              <ul id="dropdown-master-data"
                class="{{ str_contains(Request::route()->getName(), 'dashboard.master-data.') ? '' : 'hidden' }} py-2 space-y-2">
                {{-- @can('master data-produk kategori-lihat') --}}
                  <li>
                    <a href="{{ route('dashboard.master-data.product-category') }}"
                      class="flex sidebar_base pl-11 group {{ str_contains(Request::route()->getName(), 'dashboard.master-data.product-category') ? 'active_sidebar' : '' }}">
                      Produk Kategory
                    </a>
                  </li>
                {{-- @endcan --}}
                {{-- @can('master data-produk kategori-lihat') --}}
                  <li>
                    <a href="{{ route('dashboard.master-data.uom') }}"
                      class="flex sidebar_base pl-11 group {{ str_contains(Request::route()->getName(), 'dashboard.master-data.uom') ? 'active_sidebar' : '' }}">
                      Satuan Produk
                    </a>
                  </li>
                {{-- @endcan --}}
                {{-- @can('master data-produk kategori-lihat') --}}
                  <li>
                    <a href="{{ route('dashboard.master-data.payment-method') }}"
                      class="flex sidebar_base pl-11 group {{ str_contains(Request::route()->getName(), 'dashboard.master-data.payment-method') ? 'active_sidebar' : '' }}">
                      Metode Pembayaran
                    </a>
                  </li>
                {{-- @endcan --}}
                {{-- @can('master data-produk-lihat') --}}
                  <li>
                    <a href="{{ route('dashboard.master-data.supplier') }}"
                      class="flex sidebar_base pl-11 group {{ str_contains(Request::route()->getName(), 'dashboard.master-data.supplier') ? 'active_sidebar' : '' }}">
                      Supplier
                    </a>
                  </li>
                {{-- @endcan --}}
                {{-- @can('master data-tipe dana-lihat') --}}
                  <li>
                    <a href="{{ route('dashboard.master-data.sales') }}"
                      class="flex sidebar_base pl-11 group {{ str_contains(Request::route()->getName(), 'dashboard.master-data.sales') ? 'active_sidebar' : '' }}">
                      Sales
                    </a>
                  </li>
                {{-- @endcan --}}
                {{-- @can('master data-keterangan arus kas-lihat') --}}
                  <li>
                    <a href="{{ route('dashboard.master-data.customer') }}"
                      class="flex sidebar_base pl-11 group {{ str_contains(Request::route()->getName(), 'dashboard.master-data.customer') ? 'active_sidebar' : '' }}">
                      Pelanggan
                    </a>
                  </li>
                {{-- @endcan --}}
                {{-- @can('master data-keterangan arus kas-lihat') --}}
                  <li>
                    <a href="{{ route('dashboard.master-data.employee') }}"
                      class="flex sidebar_base pl-11 group {{ str_contains(Request::route()->getName(), 'dashboard.master-data.employee') ? 'active_sidebar' : '' }}">
                      Karyawan
                    </a>
                  </li>
                {{-- @endcan --}}
              </ul>
            </li>
          {{-- @endcanany --}}
          {{-- END MASTER DATA --}}

          {{-- PRESENCE --}}
          {{-- <li>
            <button type="button"
              class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 {{ str_contains(Request::route()->getName(), 'dashboard.presence.') ? 'active_sidebar' : '' }}"
              aria-controls="dropdown-presence" data-collapse-toggle="dropdown-presence">
              <x-fas-user-check class="w-6 h-6 text-gray-500 transition duration-75 mr-1" />
              <span class="flex-1 ml-3 text-left whitespace-nowrap" sidebar-toggle-item>Absensi</span>
              <x-fas-chevron-down class="w-4 h-4 text-gray-500 transition duration-75 mr-1" />
            </button>
            <ul id="dropdown-presence"
              class="{{ str_contains(Request::route()->getName(), 'dashboard.presence.') ? '' : 'hidden' }} py-2 space-y-2">
              @if (Auth::user()->id == 1)
                <li>
                  <a href="{{ route('dashboard.presence.index') }}"
                    class="flex items-center p-2 text-base text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 {{ str_contains(Request::route()->getName(), 'dashboard.presence.index') ? 'active_sidebar' : '' }}">
                    Absensi
                  </a>
                </li>
                <li>
                  <a href="{{ route('dashboard.presence.presence_history') }}"
                    class="flex items-center p-2 text-base text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 {{ str_contains(Request::route()->getName(), 'dashboard.presence.presence_history') ? 'active_sidebar' : '' }}">
                    Riwayat Absensi
                  </a>
                </li>
              @else
                <li>
                  <a href="{{ route('dashboard.presence.presence_user') }}"
                    class="flex items-center p-2 text-base text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 {{ str_contains(Request::route()->getName(), 'dashboard.presence.presence_user') ? 'active_sidebar' : '' }}">
                    Absensi Pengguna
                  </a>
                </li>
              @endif
            </ul>
          </li> --}}
          {{-- END PRESENCE --}}

          {{-- USER MANAGEMENT --}}
          @canany([
            'pengelolaan akun-akun pengguna-lihat',
            'pengelolaan akun-hak akses-lihat'
          ])
            <li>
              <button type="button"
                class="flex sidebar_base group {{ str_contains(Request::route()->getName(), 'dashboard.management-user.') ? 'active_sidebar' : '' }}"
                aria-controls="dropdown-management-user" data-collapse-toggle="dropdown-management-user">
                <x-fas-user-group class="w-6 h-6 text-gray-500 transition duration-75 mr-1 {{ str_contains(Request::route()->getName(), 'dashboard.management-user.') ? 'text-white' : '' }}" />
                <span class="flex-1 ml-3 text-left whitespace-nowrap" sidebar-toggle-item>Pengelolaan Akun</span>
                <x-fas-chevron-down
                  class="w-4 h-4 text-gray-500 transition duration-75 mr-1 {{ str_contains(Request::route()->getName(), 'dashboard.management-user.') ? 'text-white' : '' }}" />
              </button>
              <ul id="dropdown-management-user"
                class="{{ str_contains(Request::route()->getName(), 'dashboard.management-user.') ? '' : 'hidden' }} py-2 space-y-2">
                @can('pengelolaan akun-akun pengguna-lihat') 
                  <li>
                    <a href="{{ route('dashboard.management-user.user.index') }}"
                      class="flex sidebar_base pl-11 group {{ str_contains(Request::route()->getName(), 'dashboard.management-user.user.index') ? 'active_sidebar' : '' }}">
                      Akun Pengguna
                    </a>
                  </li>
                @endcan
                @can('pengelolaan akun-hak akses-lihat')
                  <li>
                    <a href="{{ route('dashboard.management-user.role.index') }}"
                      class="flex sidebar_base pl-11 group {{ str_contains(Request::route()->getName(), 'dashboard.management-user.role.') ? 'active_sidebar' : '' }}"
                      disabled>
                      Hak Akses
                    </a>
                  </li>
                @endcan
              </ul>
            </li>
          @endcanany
          {{-- END USER MANAGEMENT --}}
        </ul>
      </div>
    </div>
  </div>
</aside>

<div class="fixed inset-0 z-10 hidden bg-gray-900/50" id="sidebarBackdrop"></div>

<script>
  const sidebar = document.getElementById('sidebar');

  if (sidebar) {
    const toggleSidebarMobile = (sidebar, sidebarBackdrop, toggleSidebarMobileHamburger, toggleSidebarMobileClose) => {
      sidebar.classList.toggle('hidden');
      sidebarBackdrop.classList.toggle('hidden');
      toggleSidebarMobileHamburger.classList.toggle('hidden');
      toggleSidebarMobileClose.classList.toggle('hidden');
    }

    const toggleSidebarMobileEl = document.getElementById('toggleSidebarMobile');
    const sidebarBackdrop = document.getElementById('sidebarBackdrop');
    const toggleSidebarMobileHamburger = document.getElementById('toggleSidebarMobileHamburger');
    const toggleSidebarMobileClose = document.getElementById('toggleSidebarMobileClose');
    // const toggleSidebarMobileSearch = document.getElementById('toggleSidebarMobileSearch');

    // toggleSidebarMobileSearch.addEventListener('click', () => {
    //   toggleSidebarMobile(sidebar, sidebarBackdrop, toggleSidebarMobileHamburger, toggleSidebarMobileClose);
    // });

    toggleSidebarMobileEl.addEventListener('click', () => {
      toggleSidebarMobile(sidebar, sidebarBackdrop, toggleSidebarMobileHamburger, toggleSidebarMobileClose);
    });

    sidebarBackdrop.addEventListener('click', () => {
      toggleSidebarMobile(sidebar, sidebarBackdrop, toggleSidebarMobileHamburger, toggleSidebarMobileClose);
    });
  }
</script>
