<!DOCTYPE html>
<html lang="en">

<head>
    <title>Admin Panel - Travel</title>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1, shrink-to-fit=no" name="viewport">
    <meta name="description" content="{{ __('app.name') }}">
    <meta name="author" content="{{ __('app.name') }}">
    <meta name="keywords" content="{{ __('app.name') }}">
    <meta name="csrf-token" id="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/images/logo/favicon-blue.ico') }}" media="(prefers-color-scheme: light)">
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/images/logo/favicon-white.ico') }}" media="(prefers-color-scheme: dark)">
    @livewireStyles
    <link href="{{ asset('admin/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('admin/plugins/web-fonts/icons.css') }}" rel="stylesheet" />
    <link href="{{ asset('admin/plugins/web-fonts/font-awesome/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/plugins/web-fonts/plugin.css') }}" rel="stylesheet" />
    <link href="{{ asset('admin/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/css/skins.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/css/dark-style.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/css/colors/default.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/css/custom.css') }}" rel="stylesheet">
    <script src="{{ asset('admin/plugins/jquery/jquery.min.js') }}"></script>
    <link href="{{ asset('admin/css/sidemenu/sidemenu.css') }}" rel="stylesheet">
    <script src="https://cdn.ckeditor.com/ckeditor5/35.0.1/classic/ckeditor.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.css" />
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-bs4.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/dropify/dist/css/dropify.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    @stack('styles')
</head>

<body class="main-body leftmenu">
    {{-- <div id="global-loader">
      <div class="spinner-border text-primary loader-img" role="status">
        <span class="sr-only">Loading...</span>
      </div>
    </div> --}}
