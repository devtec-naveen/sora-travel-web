<button id="sidebarToggle"
    class="lg:hidden flex items-center gap-2 px-4 py-3 bg-primary-600 text-white rounded-xl mb-2 focus:outline-none focus:ring-2 focus:ring-primary-600">
    <i data-tabler="menu-2" class="size-6 pointer-events-none"></i>
    Menu
</button>
<aside id="sidebarNav"
    class="w-full lg:w-72 xl:w-80 shrink-0 fixed lg:static top-0 left-0 h-full lg:h-auto z-[999] sm:z-[9] bg-white lg:bg-transparent transition-transform duration-300 ease-in-out -translate-x-full lg:translate-x-0 shadow-lg lg:shadow-none"
    style="max-width: 100vw;">
    <div class="card md:rounded-xl rounded-none p-4 flex flex-col gap-1.5 h-full">
        <button id="sidebarClose"
            class="lg:hidden ml-auto mb-2 flex items-center gap-1 px-3 py-1.5 rounded transition-colors hover:bg-slate-100 text-slate-600 focus:outline-none">
            <i data-tabler="x" class="size-6"></i>
        </button>
        <a href="{{ route('my-account.personal-information') }}"
            class="sidebar-link flex items-center gap-2.5 px-2.5 py-3.5 rounded-xl text-base font-normal transition-colors
        {{ request()->routeIs('my-account.personal-information') ? 'bg-primary-600 text-white' : 'text-slate-600 hover:bg-slate-100' }}">

            <i data-tabler="user" class="size-6 shrink-0"></i>
            <span>Personal Information</span>
        </a>
        <a href="{{ route('my-account.notification-preferences') }}"
            class="sidebar-link flex items-center gap-2.5 px-2.5 py-3.5 rounded-xl text-slate-600 text-base font-normal transition-colors {{ request()->routeIs('my-account.notification-preferences') ? 'bg-primary-600 text-white' : 'text-slate-600 hover:bg-slate-100' }}"
            data-page="{{ route('my-account.notification-preferences') }}">
            <i data-tabler="bell" class="size-6 shrink-0"></i>
            <span>Notification Preferences</span>
        </a>
        <a href="{{route('my-account.save-cards')}}"
            class="sidebar-link flex items-center gap-2.5 px-2.5 py-3.5 rounded-xl text-slate-600 text-base font-normal transition-colors {{ request()->routeIs('my-account.save-cards') ? 'bg-primary-600 text-white' : 'text-slate-600 hover:bg-slate-100' }}"
            data-page="{{route('my-account.save-cards')}}">
            <i data-tabler="credit-card" class="size-6 shrink-0"></i>
            <span>Saved Cards</span>
        </a>
        <a href="{{route('my-account.save-address')}}"
            class="sidebar-link flex items-center gap-2.5 px-2.5 py-3.5 rounded-xl text-slate-600 text-base font-normal transition-colors {{ request()->routeIs('my-account.save-address') ? 'bg-primary-600 text-white' : 'text-slate-600 hover:bg-slate-100' }}"
            data-page="{{route('my-account.save-address')}}">
            <i data-tabler="map-pin" class="size-6 shrink-0"></i>
            <span>Saved Addresses</span>
        </a>
        <a href="{{ route('my-account.delete-account') }}"
            class="sidebar-link flex items-center gap-2.5 px-2.5 py-3.5 rounded-xl text-base font-normal transition-colors
            {{ request()->routeIs('my-account.delete-account') ? 'bg-red-500 text-white' : 'text-red-500 hover:bg-red-50' }}">
            <i data-tabler="trash" class="size-6 shrink-0"></i>
            <span>Delete Account</span>
        </a>
        <a href="javascript:void(0)" onclick="showLogoutPopup()"
            class="flex items-center gap-2.5 px-2.5 py-3.5 rounded-xl text-slate-600 text-base font-normal hover:bg-slate-100 transition-colors">
            <i data-tabler="logout" class="size-6 shrink-0"></i>
            <span>Logout</span>
        </a>
    </div>
</aside>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebarNav = document.getElementById('sidebarNav');
        const sidebarClose = document.getElementById('sidebarClose');

        const currentPage = window.location.pathname.split('/').pop() || '';
        document.querySelectorAll('.sidebar-link').forEach(function(link) {
            if (link.getAttribute('data-page') === currentPage) {
                link.classList.add('bg-primary-600', 'text-white');
                link.classList.remove('text-slate-600', 'hover:bg-slate-100');
            }
        });

        function openSidebar() {
            sidebarNav.classList.remove('-translate-x-full');
            document.body.classList.add('overflow-hidden');
        }

        function closeSidebar() {
            sidebarNav.classList.add('-translate-x-full');
            document.body.classList.remove('overflow-hidden');
        }

        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', openSidebar);
        }
        if (sidebarClose) {
            sidebarClose.addEventListener('click', closeSidebar);
        }
        document.addEventListener('click', function(e) {
            if (window.innerWidth < 1024 && !sidebarNav.contains(e.target) && e.target !==
                sidebarToggle) {
                closeSidebar();
            }
        });
        sidebarNav.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    });
</script>
