<x-modal formAction="saveAndStay" >
    <x-slot name="title">
        {{$title}}
    </x-slot>

    {{ $this->form }}

    <x-slot name="buttons">
        <div class="flex justify-end w-full">
            <div class="flex justify-end pt-5 space-x-2 ">
                @if ($this->backTitle)
                    <button type="button" wire:click.prevent="saveAndBack" class="px-8 py-2 text-blue-500 border border-blue-500 rounded-md hover:bg-blue-700 hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        {{$this->backTitle}}
                    </button>
                @endif
                <button type="submit" class="px-8 py-2 text-white bg-blue-500 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    {{$this->saveTitle}}
                </button>
            </div>
        </div>
    </x-slot>
</x-modal>
