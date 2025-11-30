<x-filament-panels::page>
    <form wire:submit.prevent="save">
        {{ $this->form }}
        <div class="my-4">
            <x-filament::button type="submit">Save</x-filament::button>
        </div>
    </form>
</x-filament-panels::page>
