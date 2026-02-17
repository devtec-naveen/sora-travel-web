  @include('admin.layout.header')
  @if(!request()->routeIs('admin.login'))
        @include('admin.layout.menu-header')
        @include('admin.layout.sidebar')
  @endif
    <main id="content">
            @yield('content')
    </main>
  @include('admin.layout.footer')
