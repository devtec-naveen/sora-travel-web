<div>
    @php
        $iconColor = $isHome ? 'text-white' : 'text-slate-900';
        $borderClass = $isHome ? '' : 'border-b border-slate-200';
        $bgClass = $isHome ? 'bg-blue-950' : 'bg-white';
    @endphp
    <header class="{{ $bgClass }} {{ $borderClass }} py-1 md:py-2.5">
        <div class="container">
            <nav>
                <div class="w-full flex justify-between items-center gap-4">
                    <div class="flex flex-wrap items-center justify-center md:justify-start gap-4 lg:gap-8">
                        <a href="{{ url('/') }}" class="header-logo md:w-[140px] w-[110px]">
                            @include('include.logo',['home'=>$isHome])
                        </a>
                    </div>
                    <div class="flex items-center justify-center shrink-0 gap-2">
                        @auth
                            <button
                                class="w-10 h-10 hover:bg-white/10 rounded-lg transition-colors flex items-center justify-center">
                                <i data-tabler="wallet" class="size-6 {{ $iconColor }}"></i>
                            </button>
                            <div class="dropdown dropdown-end">
                                <button tabindex="0"
                                    class="w-10 h-10 hover:bg-white/10 rounded-lg transition-colors flex items-center justify-center">
                                    <i data-tabler="bell" class="size-6 {{ $iconColor }}"></i>
                                </button>
                                <div tabindex="0"
                                    class="dropdown-content md:w-[447px] w-full fixed md:absolute md:top-10 top-[67px] right-0 p-5 bg-white rounded-xl shadow-lg mt-2 z-[1]">
                                    <div class="flex flex-col items-center gap-3.5">
                                        <div class="w-full pb-5 border-b border-slate-200 flex flex-col gap-5">
                                            <div class="flex items-center gap-5">
                                                <div class="flex-1 text-xl font-semibold text-slate-800 leading-8">
                                                    Notifications</div>
                                                <div class="flex items-center gap-3.5">
                                                    <button
                                                        class="flex items-center gap-2 text-blue-600 hover:text-blue-700 transition-colors">
                                                        <i data-tabler="check" data-size="18"></i>
                                                        <span class="text-sm font-medium">Mark all as read</span>
                                                    </button>
                                                    <button
                                                        class="w-6 h-6 flex items-center justify-center hover:bg-slate-100 rounded transition-colors">
                                                        <i data-tabler="x" class="text-slate-950" data-size="18"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="flex flex-col gap-6 max-h-[300px] overflow-y-auto">
                                                <div class="text-sm text-slate-400 text-center py-4">No notifications yet
                                                </div>
                                            </div>
                                        </div>
                                        <a href="javascript:void(0)"
                                            class="text-base font-semibold text-blue-600 hover:text-blue-700 transition-colors">
                                            View all
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="dropdown dropdown-end">
                                <div tabindex="0" role="button"
                                    class="flex items-center gap-2 p-0 md:px-3 md:py-2 rounded-lg cursor-pointer transition-colors
                                    {{ $isHome ? 'border border-white/30 hover:bg-white/10' : 'border border-slate-200 bg-white hover:bg-slate-50' }}">
                                    <span
                                        class="text-sm font-medium {{ $iconColor }} hidden md:block max-w-[120px] truncate capitalize">
                                        {{ Str::limit(Auth::user()->name ?? 'Account', 15, '...') }}
                                    </span>
                                    <i data-tabler="chevron-down"
                                        class="size-4 {{ $isHome ? 'text-white' : 'text-blue-700' }}"></i>
                                </div>
                                <ul tabindex="0"
                                    class="dropdown-content menu bg-white rounded-lg shadow-lg border border-slate-200 z-[1] w-52 p-2 mt-2">
                                    <li>
                                        <a class="py-3 rounded-lg hover:bg-slate-50" href="{{ route('my-booking') }}">
                                            <i data-tabler="calendar" class="size-5"></i>My Bookings
                                        </a>
                                    </li>
                                    <li>
                                        <a class="py-3 rounded-lg hover:bg-slate-50"
                                            href="{{ route('my-account.personal-information') }}">
                                            <i data-tabler="user" class="size-5"></i>My Account
                                        </a>
                                    </li>
                                    <li>
                                        <button onclick="showLogoutPopup()"
                                            class="w-full flex items-center gap-2 px-3 py-3 rounded-lg hover:bg-slate-50 text-left text-sm text-slate-700">
                                            <i data-tabler="logout" class="size-5"></i>Logout
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        @else
                            <div class="dropdown dropdown-end cursor-pointer">
                                <div tabindex="0" role="button">
                                    <i data-tabler="user-circle" class="size-6 md:size-9 {{ $iconColor }}"></i>
                                </div>
                                <ul tabindex="0"
                                    class="dropdown-content menu bg-white rounded-lg shadow-lg border border-slate-200 z-[1] w-52 p-2 mt-2 !right-0 !left-auto top-10">
                                    <li>
                                        <a class="py-3 rounded-lg hover:bg-slate-50" href="javascript:void(0)"
                                            wire:click="openModal('login_modal')">
                                            <i data-tabler="user" class="size-5"></i>Login
                                        </a>
                                    </li>
                                    <li>
                                        <a class="py-3 rounded-lg hover:bg-slate-50 cursor-pointer"
                                            wire:click="openModal('signup_modal')">

                                            <i data-tabler="user-plus" class="size-5"></i>Signup
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        @endauth
                    </div>
                </div>
            </nav>
        </div>
    </header>

    <x-frontend.modal id="login_modal">
        <livewire:frontend.auth.login key="login_modal"/>
    </x-frontend.modal>

    <x-frontend.modal id="signup_modal">
        <livewire:frontend.auth.register key="signup_modal"/>
    </x-frontend.modal>

    <x-frontend.modal id="forgot_password_modal">
        <livewire:frontend.auth.forgot-password key="forgot_password_modal" />
    </x-frontend.modal>

    <x-booking-timeout-popup />

    <x-logout-popup />
</div>
