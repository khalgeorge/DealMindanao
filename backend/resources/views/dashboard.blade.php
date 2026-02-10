<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="card">
        <p class="text-gray-900">{{ __("You're logged in!") }}</p>
    </div>
</x-app-layout>
