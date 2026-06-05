<x-layouts.base>
    <div class="p-5 bg-green-50 min-h-screen" x-data="{ open: false }">
        <div
            :class="{ 'md:-translate-x-full': open, '-translate-x-[110%] md:translate-x-0': !(open) }"
            class="fixed transition-all duration-500  w-72 min-h-[calc(100vh-2.5rem)] divide-y divide-gray-200 overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:divide-white/10 dark:bg-gray-900 dark:ring-white/10 -translate-x-[110%] md:translate-x-0">

            <div class="flex items-center justify-center py-5">
                <img src="/img/logo.svg" alt="" class="h-16">
            </div>

            <x-sidebar></x-sidebar>

        </div>
        <div :class="{ 'ml-[19rem] -mr-[19rem] md:ml-5 md:mr-0': open, 'ml-0 mr-0 md:ml-[19rem]': !(open) }" class="flex flex-col min-h-[calc(100vh-2.5rem)] transition-all duration-500 ease-in-out ml-0 mr-0 md:ml-[19rem]">

            <div class="sm:hidden flex items-center justify-center mb-5">
                <img src="/img/logo.svg" alt="" class="h-16">
            </div>


            <div class="flex items-center justify-between p-2 mb-5 fi-ta-ctn fi-ta-ctn-with-header rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:divide-white/10 dark:bg-gray-900 dark:ring-white/10">
                <button class="text-gray-500 " x-on:click="open = !open">
                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-layout-sidebar"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 4m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z" /><path d="M9 4l0 16" /></svg>
                </button>


                @auth

                    <x-filament::dropdown placement="bottom-end">
                        <x-slot name="trigger">
                            <div class="flex items-center gap-1 mr-2">
                                <div class="w-8 h-8 bg-gray-500 border-white border-2 shadow-md rounded-full"></div>
                                <div class="flex flex-col items-start">
                                    <span class="text-xs">{{auth()->user()->name}}</span>
                                    <!-- <span class="text-xs">Admin</span> -->
                                </div>
                            </div>
                        </x-slot>

                        <x-filament::dropdown.list>
                            <x-filament::dropdown.list.item tag="a" href="/logout" icon="heroicon-m-arrow-right-start-on-rectangle">
                                Logout
                            </x-filament::dropdown.list.item>
                        </x-filament::dropdown.list>
                    </x-filament::dropdown>

                @endauth


            </div>

            {{ $slot }}

            <div class="fixed text-right text-sm bottom-5 right-5">
                &copy; Kerala Startup Mission
            </div>
        </div>
    </div>
</x-layouts.base>
