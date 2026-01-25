<div class="flex items-center gap-2">
    <span>{{ $getState() }}</span>

    @if (!$record->is_verified)
        <x-filament::button size="xs" color="success" wire:click="verify({{ $record->id }})">
            Verify
        </x-filament::button>
    @endif
</div>