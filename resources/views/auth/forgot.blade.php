<x-layouts.base>
  <section class="bg-white dark:bg-gray-900 min-h-screen items-center flex">
    <div class="container px-6 py-24 mx-auto lg:py-32">
    <div class="lg:flex">
      <div class="lg:w-1/2">
          {{-- <img class="w-auto h-16 " src="/img/logo.svg" alt=""> --}}

          <h1 class="mt-4 text-gray-600 dark:text-gray-300 md:text-lg">Forgot Credentials</h1>
          
          <h1 class="mt-4 text-2xl font-medium text-gray-800 capitalize lg:text-3xl dark:text-white">
              Reset Your Password
          </h1>
      </div>

      <div class="mt-8 lg:w-1/2 lg:mt-0">
        <form class="w-full lg:max-w-xl" action="{{route('password.forgot')}}" method="POST">
          @csrf
            <x-flash-message/>
            <div class="relative flex items-center">
                <span class="absolute">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mx-3 text-gray-300 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </span>

                <input name="email" type="email" class="block w-full py-3 text-gray-700 bg-white border rounded-lg px-11 dark:bg-gray-900 dark:text-gray-300 dark:border-gray-600 focus:border-blue-400 dark:focus:border-blue-300 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40" placeholder="Email address">
            </div>
            @error('email')
                <span class="inline-block mb-2 text-xs text-red-400">{{$message}}</span>
            @enderror

            <div class="mt-8 md:flex md:items-center">
                <button class="w-full px-6 py-3 text-sm font-medium tracking-wide text-white capitalize transition-colors duration-300 transform bg-blue-500 rounded-lg md:w-1/2 hover:bg-blue-400 focus:outline-none focus:ring focus:ring-blue-300 focus:ring-opacity-50">
                    Reset Password
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