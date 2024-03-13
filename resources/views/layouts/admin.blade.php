<x-layouts.base>
  <div x-data="{ open: false }" class="wrapper overflow-x-hidden bg-gray-100 dark:bg-gray-900 dark:bg-opacity-40">

    <x-layouts.sidebar>

      <x-menu label="Dashboard" permissions="admin:urls:view" route="admin.urls"></x-menu>
      <x-menu label="Users" permissions="admin:users:view" route="admin.users"></x-menu>
      <x-menu label="Roles" permissions="admin:roles:view" route="admin.roles"></x-menu>

    </x-layouts.sidebar>

    <div :class="{ 'ml-64 -mr-64 md:ml-0 md:mr-0': open, 'ml-0 mr-0 md:ml-64': !(open) }" class="flex flex-col min-h-screen transition-all duration-500 ease-in-out ml-0 mr-0 md:ml-64 " >

      <x-layouts.topnav>

      </x-layouts.topnav>

      <main class="pt-16 bg-blue-50 min-h-[calc(100vh-48px)]">
        <div class="mx-auto p-3">
          
          @yield('content')

        </div>
      </main>

      <footer class="bg-white dark:bg-gray-800 p-6 py-3 shadow-sm">
        <div class="mx-auto">
          <div class="flex flex-wrap flex-row -mx-4">
            <div class="flex-shrink max-w-full px-4 w-full md:w-1/2 text-center md:text-left">
              All right reserved &copy; {{date('Y')}}
            </div>
            <div class="flex-shrink max-w-full px-4 w-full md:w-1/2 text-center md:text-right ">
              <!-- copyright text -->
              <p class="mb-0 mt-3 md:mt-0">
                <a href="/" class="hover:text-indigo-500">
                  {{ config('app.name') }}
                </a> 
              </p>
            </div>
          </div>
        </div>
      </footer>


    </div>

  </div>
</x-layouts.base>
