@props([
    'type' => 'text',
    'name',
    'label' => null,
    'placeholder' => '',
    'value' => null,
])

@if($type === 'password')
    <div class="mt-4">
        @if($label)
            <label>{{ $label }}</label>
        @endif
        <div class="position-relative">
            <input 
                wire:model="password"
                type="password" 
                name="{{ $name }}" 
                value="{{ old($name, $value) }}" 
                id="password"
                class="form-control"
                placeholder="{{ $placeholder }}" 
                autocomplete="off"
            >
            <span class="show-password" onclick="showPassword()">
                <i class="far fa-eye-slash"></i>
            </span>
        </div>
        @error($name)
            <div class="text-danger form-error">{{ $message }}</div>
        @enderror
    </div>
@else
    <div class="mb-3">
        @if($label)
            <label>{{ $label }}</label>
        @endif
        <input
            wire:model="{{ $name }}" 
            type="{{ $type }}" 
            name="{{ $name }}" 
            value="{{ old($name, $value) }}"
            placeholder="{{ $placeholder }}"
            {{ $attributes->merge(['class' => 'form-control']) }}
        >
        @error($name)
            <div class="text-danger form-error">{{ $message }}</div>
        @enderror
    </div>
@endif
