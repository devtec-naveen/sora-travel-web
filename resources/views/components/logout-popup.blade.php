<div>
    @auth
        <div id="logout-popup" class="fixed inset-0 z-[999] flex items-center justify-center p-4"
            style="display: none !important;">
            <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="hideLogoutPopup()"></div>
            <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6 flex flex-col items-center gap-5 z-10">

                <div class="w-16 h-16 rounded-full bg-red-50 flex items-center justify-center">
                    <i data-tabler="logout" class="text-red-500" data-size="32"></i>
                </div>

                <div class="text-center flex flex-col gap-2">
                    <h3 class="font-bold text-lg text-slate-900">Logout</h3>
                    <p class="text-sm text-slate-500 leading-relaxed">
                        Are you sure you want to logout from your account?
                    </p>
                </div>

                <div class="flex gap-3 w-full">
                    <button onclick="hideLogoutPopup()" class="btn btn-white flex-1">
                        Cancel
                    </button>
                    <button onclick="submitLogout()"
                        class="btn btn-primary flex-1 bg-red-600 hover:bg-red-700 border-red-600">
                        Logout
                    </button>
                </div>

            </div>
        </div>
        <form id="logout-form" method="POST" action="{{ route('logout') }}" style="display:none;">
            @csrf
        </form>
    @endauth
    @push('scripts')
        <script>
            function showLogoutPopup() {
                const popup = document.getElementById('logout-popup');
                popup.style.removeProperty('display');
                popup.style.display = 'flex';
            }

            function hideLogoutPopup() {
                const popup = document.getElementById('logout-popup');
                popup.style.display = 'none';
            }

            function submitLogout() {
                document.getElementById('logout-form').submit();
            }
        </script>
    @endpush
</div>
