@props([
  'label',
  'route' => "#",
  'permissions' => null,
])

@if($permissions)
  @can($permissions)
    <a href="{{ Route::has($route) ? route($route) : $route }}" class="{{ Route::has($route) && Route::is($route) ? 'shadow bg-blue-600 text-white' : '' }} flex items-center hover:shadow px-2 py-2 mt-2 text-gray-300 transition-colors duration-300 transform rounded-md dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 dark:hover:text-gray-200 hover:text-gray-700">

      @if($slot->toHtml())
        <div>
          {{ $slot }}
        </div>
      @endif

      <div class="flex flex-1 mx-4 font-medium">
        {{ $label }}
      </div>
    </a>
  @endcan
@else
  <a href="{{ Route::has($route) ? route($route) : $route }}" class="{{ Route::has($route) && Route::is($route) ? 'shadow bg-blue-600 text-white' : '' }} flex items-center hover:shadow px-2 py-2 mt-2 text-gray-300 transition-colors duration-300 transform rounded-md dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 dark:hover:text-gray-200 hover:text-gray-700">

    @if($slot->toHtml())
      <div>
        {{ $slot }}
      </div>
    @endif

    <div class="flex flex-1 mx-4 font-medium">
      {{ $label }}
    </div>
  </a>
@endif
