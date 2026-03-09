<header class="{{ request()->routeIs('home') ? 'bg-blue-950' : 'bg-white' }} py-1 md:py-2.5">
   <div class="container">
      <nav>
         <div class="w-full flex justify-between items-center gap-4">
            <div class="flex flex-wrap items-center justify-center md:justify-start gap-4 lg:gap-8">
               <a href="{{url('/')}}" class="header-logo md:w-[140px] w-[110px]">
                   @include('include.logo')
               </a>
            </div>
            <div class="flex items-center justify-center shrink-0 ">
               <div class="dropdown">
                  <div tabindex="0" role="button" class=""><i data-tabler="user-circle"
                        class="size-6 md:size-9 text-white"></i></div>
                  <ul tabindex="0"
                     class="dropdown-content menu bg-white rounded-lg shadow-lg border border-slate-200 z-[1] w-52 p-2 mt-2 !right-0 !left-auto top-10">
                     <li><a class="py-3 rounded-lg hover:bg-slate-50" href="javascript:void(0)" onclick="login_modal.showModal()"><i data-tabler="user" class="size-5"></i>Login</a></li>
                     <li><a class="py-3 rounded-lg hover:bg-slate-50" href="javascript:void(0)" onclick="signup_modal.showModal()"><i data-tabler="user-plus" class="size-5"></i>Signup</a></li>
                  </ul>
               </div>
            </div>
      </nav>
   </div>
</header>