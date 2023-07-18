<x-layouts.base>
  <section class="bg-white dark:bg-gray-900 min-h-screen items-center flex">
    <div class="container px-6 py-24 mx-auto lg:py-32">
    <div class="lg:flex">
      <div class="lg:w-1/2">
          {{-- <img class="w-auto h-16 " src="/img/logo.svg" alt=""> --}}

          {{-- <h1 class="mt-4 text-gray-600 dark:text-gray-300 md:text-lg"></h1> --}}
          
          <h1 class="mt-4 text-2xl font-medium text-gray-800 capitalize lg:text-3xl dark:text-white">
            Set account password
          </h1>
      </div>

      <div class="mt-8 lg:w-1/2 lg:mt-0">
        <form class="w-full lg:max-w-xl" action="{{route('password.reset')}}" method="POST">
          @csrf
            <input type="hidden" name="email" value="{{ app('request')->input('email') }}">
            <input type="hidden" name="token" value="{{ app('request')->input('token') }}">

            <x-flash-message/>
            <div class="relative flex items-center">
                <span class="absolute">
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mx-3 text-gray-300 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                  </svg>
                </span>

                <input name="password" type="password" class="block w-full py-3 text-gray-700 bg-white border rounded-lg px-11 dark:bg-gray-900 dark:text-gray-300 dark:border-gray-600 focus:border-blue-400 dark:focus:border-blue-300 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40" placeholder="Password">
            </div>
            @error('password')
                <span class="inline-block mb-2 text-xs text-red-400">{{$message}}</span>
            @enderror

            <div class="relative flex items-center mt-5">
                <span class="absolute">
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mx-3 text-gray-300 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                  </svg>
                </span>

                <input name="password_confirmation" type="password" class="block w-full py-3 text-gray-700 bg-white border rounded-lg px-11 dark:bg-gray-900 dark:text-gray-300 dark:border-gray-600 focus:border-blue-400 dark:focus:border-blue-300 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40" placeholder="Confirm Password">
            </div>
            @error('password_confirmation')
                <span class="inline-block mb-2 text-xs text-red-400">{{$message}}</span>
            @enderror

            <div class="mt-8 md:flex md:items-center">
                <button class="w-full px-6 py-3 text-sm font-medium tracking-wide text-white capitalize transition-colors duration-300 transform bg-blue-500 rounded-lg md:w-1/2 hover:bg-blue-400 focus:outline-none focus:ring focus:ring-blue-300 focus:ring-opacity-50">
                    Setup Password
                </button>

                <a href="{{route('login')}}" class="inline-block mt-4 text-center text-blue-500 md:mt-0 md:mx-6 hover:underline dark:text-blue-400">
                    Login to your account
                </a>
            </div>
          </form>
        </div>
      </div>

        
    </div>
  </section>
</x-layouts.base>