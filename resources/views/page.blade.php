<x-frontend.main-layout>
    <main class="bg-slate-50 min-h-[800px]">
        <div
            class="self-stretch min-h-[100px] md:min-h-[182px] py-5 bg-gradient-to-b from-primary-800 to-primary-900 flex flex-col justify-center items-center gap-2.5">
            <div
                class="text-white text-xl sm:text-3xl md:text-4xl font-bold leading-tight md:leading-[48px] text-center">
                {!! $page->page_title ?? '' !!}
            </div>
        </div>

        <div class="py-6 lg:py-12">
            <div class="container">
                <div class="flex flex-col gap-6">
                    {!! $page->content ?? '' !!}
                </div>
            </div>
        </div>
    </main>
</x-frontend.main-layout>
