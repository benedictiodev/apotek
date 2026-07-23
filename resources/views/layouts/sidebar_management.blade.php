<aside id="sidebar"
  class="fixed top-0 left-0 z-[11] flex flex-col flex-shrink-0 w-64 h-full pt-16 font-normal duration-75 lg:flex transition-width hidden"
  aria-label="Sidebar">
  <div class="relative flex flex-col flex-1 min-h-0 pt-0 bg-white shadow-lg rounded-br-xl border-r border-gray-200">
    <div class="flex flex-col flex-1 pt-5 pb-4 overflow-y-auto">
      <div class="flex-1 px-3 space-y-1 bg-white divide-y divide-gray-200">
        <ul class="pb-2 space-y-2">
          <li>
            <a href="{{ route('management.dashboard') }}"
              class="flex items-center p-2 text-base text-gray-900 rounded-lg hover:bg-gray-100 group {{ Request::route()->getName() == 'management.dashboard' ? 'active_sidebar' : '' }}">
              <x-fas-home class="w-6 h-6 text-gray-500 transition duration-75 {{ Request::route()->getName() == 'management.dashboard' ? 'text-white' : '' }}" />
              <span class="ml-3" sidebar-toggle-item>Beranda</span>
            </a>
          </li>

          <li>
            <a href="{{ route('management.company.index') }}"
              class="flex items-center p-2 text-base text-gray-900 rounded-lg hover:bg-gray-100 group {{ str_contains(Request::route()->getName(), 'management.company') ? 'active_sidebar' : '' }}">
              <x-fas-building class="w-6 h-6 text-gray-500 transition duration-75 {{ str_contains(Request::route()->getName(), 'management.company') ? 'text-white' : '' }} mr-1" />
              <span class="ml-3" sidebar-toggle-item>Mitra Perusahaan</span>
            </a>
          </li>

          <li>
            <button type="button"
              class="flex sidebar_base group {{ str_contains(Request::route()->getName(), 'dashboard.finance.') ? 'active_sidebar' : '' }}"
              aria-controls="dropdown-finance" data-collapse-toggle="dropdown-finance">
              <x-fas-money-bill class="w-6 h-6 text-gray-500 transition duration-75 mr-1 {{ str_contains(Request::route()->getName(), 'dashboard.finance.') ? 'text-white' : '' }}" />
              <span class="flex-1 ml-3 text-left whitespace-nowrap" sidebar-toggle-item>Keuangan</span>
              <x-fas-chevron-down
                class="w-4 h-4 text-gray-500 transition duration-75 mr-1 {{ str_contains(Request::route()->getName(), 'dashboard.finance.') ? 'text-white' : '' }}" />
            </button>
            <ul id="dropdown-finance"
              class="{{ str_contains(Request::route()->getName(), 'management.fund.') ? '' : 'hidden' }} py-2 space-y-2">   
              <li>
                <a href="{{ route('management.fund.master_fund') }}"
                  class="flex sidebar_base pl-11 group {{ str_contains(Request::route()->getName(), 'management.fund.master_fund') ? 'active_sidebar' : '' }}">
                  Dana
                </a>
              </li>
              <li>
                <a href="{{ route('management.fund.monthly') }}"
                  class="flex sidebar_base pl-11 group {{ str_contains(Request::route()->getName(), 'management.fund.monthly') ? 'active_sidebar' : '' }}">
                  Kas Bulanan
                </a>
              </li>
            </ul>
          </li>
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
