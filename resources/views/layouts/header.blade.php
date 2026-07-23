<nav class="fixed z-30 w-full bg-white shadow-md rounded-b-xl">
  <div class="px-3 py-3 lg:px-5 lg:pl-3">
    <div class="flex items-center justify-between w-full">
      <div class="flex items-center justify-start w-full">
        <button id="toggleSidebarMobile" aria-expanded="true" aria-controls="sidebar"
          class="p-2 text-gray-600 rounded cursor-pointer lg:hidden hover:text-gray-900 hover:bg-gray-100 focus:bg-gray-100 focus:ring-2 focus:ring-gray-100">
          <svg id="toggleSidebarMobileHamburger" class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"
            xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd"
              d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h6a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
              clip-rule="evenodd"></path>
          </svg>
          <svg id="toggleSidebarMobileClose" class="hidden w-6 h-6" fill="currentColor" viewBox="0 0 20 20"
            xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd"
              d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
              clip-rule="evenodd"></path>
          </svg>
        </button>
        <a href="{{ route(Auth::user()->is_management ? 'management.dashboard' : 'dashboard') }}" class="flex ml-2 md:mr-24">
          <img src="{{ asset("images/logo_with_text.png")}}" class="mr-4 h-12" alt="Benedictio Logo">
          {{-- <div class="text-[#339bf7]">
            <div class="self-center font-semibold sm:text-2xl whitespace-nowrap">Benedictio Dev</div>
            <div class="text-xs">Point Of Sales Application</div>
          </div> --}}
          {{-- <span class="self-center text-xl font-semibold sm:text-2xl whitespace-nowrap">Benedictio Dev</span> --}}
        </a>
        <form action="#" method="GET" class="hidden lg:block lg:pl-3.5">
          {{-- <label for="topbar-search" class="sr-only">Search</label>
          <div class="relative mt-1 lg:w-96">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
              <svg class="w-5 h-5 text-gray-500" fill="currentColor" viewBox="0 0 20 20"
                xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd"
                  d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                  clip-rule="evenodd"></path>
              </svg>
            </div>
            <input type="text" name="email" id="topbar-search"
              class="bg-sky-500 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 p-2.5"
              placeholder="Search">
          </div> --}}
        </form>
      </div>
      <div class="flex items-center">

        <!-- Profile -->
        <div class="flex items-center ml-3">
          <div>
            <button type="button"
              class="flex text-sm bg-gray-200 rounded-full focus:ring-4 focus:ring-gray-300"
              id="user-menu-button-2" aria-expanded="false" data-dropdown-toggle="dropdown-2">
              <span class="sr-only">Open user menu</span>
              {{-- <img class="w-8 h-8 rounded-full" src="https://flowbite.com/docs/images/people/profile-picture-5.jpg"
                alt="user photo"> --}}
              <div class="w-10 h-10 flex justify-center items-center bg-gray-200 rounded-full text-2xl font-bold">
                {{ strtoupper(substr(Auth::user()->name, 0, 1 )) }}
              </div>
            </button>
          </div>
          <!-- Dropdown menu -->
          <div
            class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded shadow"
            id="dropdown-2">
            <div class="px-4 py-3" role="none">
              <p class="text-sm text-gray-900" role="none">
                {{ Auth::user()->name }}
              </p>
              <p class="text-sm font-medium text-gray-900 truncate" role="none">
                {{ Auth::user()->email }}
              </p>
            </div>
            <ul class="py-1" role="none">
              {{-- <li>
                <a href="#"
                  class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                  role="menuitem">Dashboard</a>
              </li>
              <li>
                <a href="#"
                  class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                  role="menuitem">Earnings</a>
              </li> --}}
              <li>
                <a href="{{ route('dashboard.profile') }}"
                  class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                  role="menuitem">Profil</a>
              </li>
              <li>
                <a href="{{ route('dashboard.change_password') }}"
                  class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                  role="menuitem">Rubah Password</a>
              </li>
              <li>
                <form action="{{ route('logout') }}" method="POST" id="logout">
                  @csrf
                </form>
                <button
                  class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                  role="menuitem" type="submit" form="logout"
                >
                  Keluar
                </button>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</nav>
