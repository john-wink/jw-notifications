<x-filament-panels::page>
    <div class="mx-auto w-full max-w-4xl">
        <x-filament-panels::form wire:submit="submit">
            {{ $this->form }}
            <div>
                <x-filament::button type="submit" size="sm">@lang('jw-notifications::general.submit')</x-filament::button>
            </div>
        </x-filament-panels::form>
    </div>
</x-filament-panels::page>
