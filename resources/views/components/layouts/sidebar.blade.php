<div 
  :class="{ 'md:-translate-x-full': open, '-translate-x-full md:translate-x-0': !(open) }"
  class="fixed transition-all duration-500 flex flex-col w-64 h-screen px-4 py-8 bg-slate-900 border-r rtl:border-r-0 rtl:border-l dark:bg-gray-900 dark:border-gray-700">
  
  <a href="#" class="px-3 py-5 bg-white rounded-md shadow-md text-center">
    <h2 class="text-2xl font-semibold text-gray-700 px-4 overflow-hidden hidden-compact">
      <img class="h-16 mx-auto" src="/img/logo.svg">
      {{-- App --}}
    </h2>
  </a>
  

  <div class="flex flex-col justify-between flex-1 ">
    <nav class="pt-5">
      {{$slot}}
    </nav>

  </div>
</div>

{{-- <nav id="sidebar-menu" :class="{ 'w-64 md:w-0': open, 'w-0 md:w-64': !(open) }" class="fixed transition-all duration-500 ease-in-out h-screen bg-blue-700 dark:bg-gray-800 shadow-sm w-0 md:w-64" >

  <div class="h-full overflow-y-auto scrollbars">

    <div class="mh-18 text-center py-5">
      <a href="#" class="relative">
        <h2 class="text-2xl font-semibold text-gray-200 px-4 overflow-hidden hidden-compact">
          <div class="px-3 py-5 bg-white rounded-md">
            <img class="inline-block h-14 ltr:mr-2 rtl:ml-2 -mt-1" src="/img/logo.svg">
          </div>
        </h2>
      </a>
    </div>

    <ul class="w-full float-none flex flex-col font-medium ltr:pl-1.5 rtl:pr-1.5">

      {{$slot}}

    </ul>

  </div>

</nav> --}}
