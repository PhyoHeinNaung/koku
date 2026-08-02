@props(['size' => 'sm'])

{{--
Shared spinner, built on DaisyUI's `loading loading-spinner` utility so it
inherits the app's theme automatically. Usable in both admin and customer
views. Pass any wire:loading/wire:target attributes straight through:

<x-spinner size="xs" wire:loading wire:target="save" />

Sizes: xs, sm (default), md, lg
--}}
<span {{ $attributes->merge(['class' => "loading loading-spinner loading-{$size}"]) }}></span>