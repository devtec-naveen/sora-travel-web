@php
    $url = $path ? asset(trim('uploads/'. $folder, '/') . '/' . ltrim($path, '/')) : null;
@endphp
@if ($url)
    <img src="{{ $url }}" @if ($width) width="{{ $width }}" @endif
        {{ $attributes->merge(['class' => 'img-thumbnail']) }}>
@else
    <span {{ $attributes }}>-</span>
@endif
