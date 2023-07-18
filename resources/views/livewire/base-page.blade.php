<div>
    <div class="flex flex-wrap flex-row justify-between items-center mb-5 mt-5">
		<div class="flex-shrink px-4 ">   
				<p class="text-xl font-bold ">{{$title}}</p>
		</div>
		<div class="flex flex-wrap items-center justify-end flex-1">   
            @if( method_exists($this, 'getActions') )
                @if($actions = $this->getActions())
                    @foreach ($actions as $action)

                        <button wire:click="{{$action['action']}}()" class="flex items-center px-5 py-2 mx-2 my-1 text-sm leading-normal rounded-full {{$action['class'] ?? ''}}" role="menuitem" tabindex="-1" id="menu-item-0">
                            <i class="ti ti-{{$action['icon']}} text-xl"></i>
                            <span class="ml-3">
                                {{$action['label']}}
                            </span>
                        </button>

                    @endforeach
                @endif
            @endif
		</div>
	</div>

    {{ $this->table }}
</div>
