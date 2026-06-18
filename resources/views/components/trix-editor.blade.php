@props(['model' => null, 'id' => null, 'placeholder' => ''])

@php $inputId = $id ?? 'trix-input'; @endphp

<div {{ $attributes->merge(['class' => '']) }}>
    <input id="{{ $inputId }}" type="hidden" wire:model="{{ $model }}">
    <trix-editor
        wire:ignore
        input="{{ $inputId }}"
        placeholder="{{ $placeholder }}"
        class="trix-content rounded-2xl border border-ink-200 bg-white"
    ></trix-editor>
</div>
