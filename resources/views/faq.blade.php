<x-frontend.main-layout>
    <main class="bg-slate-50 min-h-[800px]">
        <div class="self-stretch min-h-[100px] md:min-h-[182px] py-5 bg-gradient-to-b from-primary-800 to-primary-900 flex flex-col justify-center items-center gap-2.5">
            <div class="text-white text-xl sm:text-3xl md:text-4xl font-bold leading-tight md:leading-[48px] text-center">
                Frequently Asked Questions
            </div>
        </div>
        <div class="py-6 lg:py-12">
            <div class="container">
                @if($categories->isEmpty())
                    <div class="text-center text-slate-500 py-12">No FAQs available at the moment.</div>
                @else
                    <div class="tabs tabs-lift p-0 bg-transparent justify-start gap-4 gap-x-2">
                        @foreach($categories as $index => $category)
                            <label class="tab tabs flex-1 md:flex-none">
                                <input type="radio" name="faq_tabs" {{ $index === 0 ? 'checked' : '' }}>
                                {{ $category->name }}
                            </label>
                            <div class="tab-content">
                                <div class="flex flex-col gap-4">
                                    @forelse($category->faqs as $faq)
                                        <details class="p-4 bg-white rounded-xl shadow-[0px_1px_3px_0px_rgba(0,0,0,0.10)] shadow-[0px_1px_2px_-1px_rgba(0,0,0,0.10)]">
                                            <summary class="flex items-center gap-1 cursor-pointer list-none">
                                                <div class="flex-1 text-lg font-semibold text-slate-950 leading-7">
                                                    {{ $faq->question }}
                                                </div>
                                                <i data-tabler="chevron-down"
                                                    class="w-6 h-6 text-slate-950 transition-transform duration-200 details-chevron"
                                                    data-size="24">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="icon icon-tabler icon-tabler-chevron-down"
                                                        width="24" height="24" viewBox="0 0 24 24"
                                                        stroke-width="1.5" stroke="currentColor" fill="none"
                                                        stroke-linecap="round" stroke-linejoin="round"
                                                        style="display:inline-block;vertical-align:middle;stroke:currentcolor;">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                        <path d="M6 9l6 6l6 -6"></path>
                                                    </svg>
                                                </i>
                                            </summary>
                                            <div class="mt-2 text-sm font-normal text-slate-500 leading-5">
                                                {!! $faq->answer !!}
                                            </div>
                                        </details>
                                    @empty
                                        <div class="text-slate-400 text-sm py-4">No questions in this category yet.</div>
                                    @endforelse
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
        <style>
            details summary::-webkit-details-marker { display: none; }
            details[open] .details-chevron { transform: rotate(180deg); }
        </style>
    </main>
</x-frontend.main-layout>