@props([
    'breadcrumb' => true,
])
@extends('admin.layout.main-layout')
@section('content')
    <div class="main-content side-content pt-0">
        <div class="container-fluid">
            <div class="inner-body">
                <div class="page-header d-block" style="{{ $breadcrumb === false ? 'min-height:0px' : '' }}">                    
                    @if ($breadcrumb !== false && is_array($breadcrumb))
                        <nav class="breadcrumb-5">
                            <div class="breadcrumb flat ps-0 pt-0">
                                @foreach ($breadcrumb as $item)
                                    @if ($loop->last)
                                        <a wire:navigate class="active disableclick" href="{{ $item['url'] ?? '#' }}">{{ $item['label'] }}</a>
                                    @else
                                        <a wire:navigate href="{{ $item['url'] }}">{{ $item['label'] }}</a>
                                    @endif
                                @endforeach
                            </div>
                        </nav>
                    @endif
                </div>
                {{ $slot }}
            </div>
        </div>
    </div>
@endsection
