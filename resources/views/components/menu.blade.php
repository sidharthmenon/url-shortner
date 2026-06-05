@props([
  'label',
  'route' => "#",
  'permissions' => null,
])

@if($permissions)
  @can($permissions)
    <a href="{{ Route::has($route) ? route($route) : $route }}" class="{{ Route::has($route) && Route::is($route) ? 'shadow bg-primary-100' : '' }} flex items-center hover:shadow px-2 py-2 text-sm mt-2 transition-colors duration-300 transform rounded-md dark:text-gray-400 hover:bg-primary-50 dark:hover:bg-gray-800 dark:hover:text-gray-200 hover:text-gray-700">

      @if($slot->toHtml())
        <div>
          {{ $slot }}
        </div>
      @endif

      <div class="flex flex-1 mx-2 font-medium">
        {{ $label }}
      </div>
    </a>
  @endcan
@else
  <a href="{{ Route::has($route) ? route($route) : $route }}" class="{{ Route::has($route) && Route::is($route) ? 'shadow bg-primary-100' : '' }} flex items-center hover:shadow px-2 py-2 text-sm mt-2 transition-colors duration-300 transform rounded-md dark:text-gray-400 hover:bg-primary-50 dark:hover:bg-gray-800 dark:hover:text-gray-200 hover:text-gray-700">

    @if($slot->toHtml())
      <div>
        {{ $slot }}
      </div>
    @endif

    <div class="flex flex-1 mx-2 font-medium">
      {{ $label }}
    </div>
  </a>
@endif
