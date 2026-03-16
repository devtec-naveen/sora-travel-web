<header class="{{ request()->routeIs('home') ? 'bg-blue-950' : 'bg-white' }} py-1 md:py-2.5">
    <div class="container">
        <nav>
            <div class="w-full flex justify-between items-center gap-4">
                <div class="flex flex-wrap items-center justify-center md:justify-start gap-4 lg:gap-8">
                    <a href="{{ url('/') }}" class="header-logo md:w-[140px] w-[110px]">
                        @include('include.logo')
                    </a>
                </div>
                <div class="flex items-center justify-center shrink-0 ">
                    <div class="dropdown cursor-pointer">
                        <div tabindex="0" role="button" class=""><i data-tabler="user-circle"
                                class="size-6 md:size-9 {{ request()->routeIs('home') ? 'text-white' : 'text-blue' }}"></i></div>
                        <ul tabindex="0"
                            class="dropdown-content menu bg-white rounded-lg shadow-lg border border-slate-200 z-[1] w-52 p-2 mt-2 !right-0 !left-auto top-10">
                            <li><a class="py-3 rounded-lg hover:bg-slate-50" href="javascript:void(0)"
                                    onclick="login_modal.showModal()"><i data-tabler="user" class="size-5"></i>Login</a>
                            </li>
                            <li><a class="py-3 rounded-lg hover:bg-slate-50" href="javascript:void(0)"
                                    onclick="signup_modal.showModal()"><i data-tabler="user-plus"
                                        class="size-5"></i>Signup</a></li>
                        </ul>
                    </div>
                </div>
        </nav>
    </div>
</header>
<x-frontend.modal id="login_modal">
    <h1 class="font-semibold text-xl sm:text-2xl md:text-3xl leading-snug text-center text-slate-800">Welcome Back</h1>
    <p class="font-normal text-sm sm:text-base text-center text-slate-500">Log in to your account to continue</p>
    <form class="w-full space-y-4 mt-7">
        <div class="form-control w-full">
            <label class="form-label">Email</label>
            <input type="email" placeholder="Enter email address" class="form-input" />
        </div>
        <div class="form-control w-full">
            <label class="form-label">Password</label>
            <input type="password" placeholder="Enter your password" class="form-input" />
        </div>
        <div class="w-full flex justify-center mt-6">
            <a href="javascript:void(0)" onclick="login_modal.close(); forgot_password_modal.showModal()"
                class="font-semibold text-base text-center text-blue-600">Forgot password?</a>
        </div>
        <button class="btn btn-primary w-full mt-6">Continue</button>
        <p class="mt-5 text-center font-normal text-base text-[#4a5565]">
            Don't have an account? <a href="javascript:void(0)" onclick="login_modal.close(); signup_modal.showModal()"
                class="font-semibold text-base text-blue-600">Sign Up</a>
        </p>
    </form>
</x-frontend.modal>
<x-frontend.modal id="signup_modal">
    <h1 class="font-semibold text-xl sm:text-2xl md:text-3xl leading-snug text-center text-slate-800">
        Create an Account
    </h1>
    <p class="font-normal text-sm sm:text-base text-center text-slate-500">
        Sign up to start booking your travel
    </p>
    <div class="w-full space-y-4 mt-7">
        <!-- Full Name Field -->
        <div class="form-control w-full">
            <label class="form-label">Full Name</label>
            <input type="text" placeholder="John Doe" class="form-input" />
        </div>
        <!-- Email Field -->
        <div class="form-control w-full">
            <label class="form-label">Email</label>
            <input type="email" placeholder="Enter email address" class="form-input" />
        </div>
        <!-- Password Field -->
        <div class="form-control w-full">
            <label class="form-label">Password</label>
            <input type="password" placeholder="Create a password" class="form-input" />
        </div>
        <!-- Confirm Password Field -->
        <div class="form-control w-full">
            <label class="form-label">Confirm Password</label>
            <input type="password" placeholder="Confirm your password" class="form-input" />
        </div>
        <!-- Terms -->
        <div class="flex items-center gap-2">
            <input type="checkbox" class="checkbox checkbox-sm checkbox-primary" />
            <span class="text-xs sm:text-sm text-slate-500">I agree to the <a href="#"
                    class="text-blue-600 font-medium">Terms & Conditions</a> and <a href="#"
                    class="text-blue-600 font-medium">Privacy Policy</a></span>
        </div>
    </div>
    <!-- Submit Button -->
    <button class="btn btn-primary w-full mt-6">
        Sign Up
    </button>
    <!-- Footer -->
    <p class="mt-5 font-normal text-base text-[#4a5565]">
        Already have an account? <a href="javascript:void(0)" onclick="signup_modal.close(); login_modal.showModal()"
            class="font-semibold text-base text-blue-600">Log
            In</a>
    </p>
</x-frontend.modal>
