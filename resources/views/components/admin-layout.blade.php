@props([
    'breadcrumb' => true,    
])
@extends('admin.layout.main-layout')
@section('content')
    <div class="main-content side-content pt-0">
        <div class="container-fluid">
            <div class="inner-body {{$breadcrumb !== false ? 'pt-5' : ''}}">                
                <div class="page-header d-block" style="{{$breadcrumb === false ? 'min-height:10px' : ''}}">
                    <h2 class="main-content-title tx-24 mg-b-5" wire:ignore>{{$pageTitle}}</h2>
                    @if($breadcrumb !== false)
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{$breadcrumb}}</li>
                        </ol>
                    @endif
                </div>                
                {{ $slot }}
            </div>
        </div>
    </div>
@endsection
