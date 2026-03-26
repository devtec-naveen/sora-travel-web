<x-frontend.main-layout>
    <main class="bg-slate-50 min-h-[800px]">
        <div class="container py-6 lg:py-10">
            <div class="flex flex-col lg:flex-row gap-4 md:gap-6">
                @include('myaccount.sidebar')
                <div class="flex-1 min-w-0 flex flex-col gap-6">
                    <h1 class="text-slate-950 text-xl md:text-2xl font-semibold">Personal Information</h1>
                    <div class="card p-4 md:p-6 flex flex-col gap-6">
                        <div class="flex flex-col gap-5">
                            <h2 class="text-slate-950 text-lg font-semibold">Basic Information</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                                <div class="form-control md:col-span-2 lg:col-span-1">
                                    <label class="form-label">Full Name</label>
                                    <input type="text" class="form-input" placeholder="John Doe" value="John Doe">
                                </div>
                                <div class="form-control md:col-span-2 lg:col-span-1">
                                    <label class="form-label">Phone number</label>
                                    <div
                                        class="flex h-10 rounded-md border border-slate-200 bg-white shadow-sm overflow-hidden">
                                        <div
                                            class="flex items-center gap-1.5 px-2.5 border-r border-slate-200 text-slate-400 text-sm">
                                            <select
                                                class="appearance-none w-full h-full bg-transparent border-none focus:ring-0 pl-0 pr-6 cursor-pointer"
                                                style="outline:none;">
                                                <option>+1</option>
                                                <option>+44</option>
                                                <option>+91</option>
                                            </select>
                                            <i data-tabler="chevron-down"
                                                class="size-[18px] shrink-0 pointer-events-none -ml-5"></i>
                                        </div>
                                        <input type="tel"
                                            class="form-input border-0 shadow-none rounded-none flex-1 min-w-0"
                                            placeholder="2345 6789 890" value="2345 6789 890">
                                    </div>
                                </div>
                                <div class="form-control md:col-span-2 lg:col-span-1">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-input" placeholder="johndoe@gmail.com"
                                        value="johndoe@gmail.com">
                                </div>
                                <div class="form-control md:col-span-2 lg:col-span-1">
                                    <label class="form-label">Passport / ID Number <span
                                            class="text-slate-500 font-normal">(optional)</span></label>
                                    <input type="text" class="form-input" placeholder="A12345678" value="A12345678">
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-primary w-fit btn-sm">Update</button>
                    </div>
                </div>
            </div>
        </div>
    </main>
</x-frontend.main-layout>
