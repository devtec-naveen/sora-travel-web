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
                                    <input type="text" class="form-input" placeholder="Full Name">
                                </div>
                                <div class="form-control md:col-span-2 lg:col-span-1">
                                    <label class="form-label">Phone number</label>
                                    <div class="w-full" wire:ignore>
                                        <input type="tel" id="contact-phone-input"
                                            placeholder="Phone number"
                                            class="form-input intl-phone-input"
                                            data-phone-code="+91"
                                            data-phone-number=""
                                            autocomplete="tel" />
                                    </div>
                                    <input type="hidden" id="contact-phone-code-hidden" />
                                    <input type="hidden" id="contact-phone-hidden" />
                                </div>
                                <div class="form-control md:col-span-2 lg:col-span-1">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-input" placeholder="Email">
                                </div>
                                <div class="form-control md:col-span-2 lg:col-span-1">
                                    <label class="form-label">Passport / ID Number <span
                                            class="text-slate-500 font-normal">(optional)</span></label>
                                    <input type="text" class="form-input" placeholder="Passport/ID Number" value="">
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
