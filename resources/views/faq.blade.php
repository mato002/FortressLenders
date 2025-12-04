@extends('layouts.website')

@section('title', 'Frequently Asked Questions - Fortress Lenders Ltd')

@section('content')
    <!-- Hero Section -->
    <section class="relative text-white py-12 sm:py-16 md:py-20 overflow-hidden bg-gradient-to-br from-teal-800 via-teal-700 to-teal-900">
        <div class="relative w-full px-4 sm:px-6 lg:px-8 xl:px-16 2xl:px-32 text-center">
            <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold mb-3 sm:mb-4 px-4">Frequently Asked Questions</h1>
            <p class="text-lg sm:text-xl text-teal-100 px-4">Find answers to common questions about our services</p>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-12 sm:py-16 md:py-20 bg-white">
        <div class="w-full px-4 sm:px-6 lg:px-8 xl:px-16 2xl:px-32">
            <div class="max-w-4xl mx-auto">
                @if($faqs->isEmpty())
                    <div class="text-center py-12">
                        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-gray-600 text-lg">No FAQs available at the moment. Please check back later.</p>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($faqs as $index => $faq)
                            <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                                <button 
                                    class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50 transition-colors focus:outline-none"
                                    onclick="toggleFaq({{ $index }})"
                                    aria-expanded="false"
                                    id="faq-button-{{ $index }}"
                                >
                                    <span class="font-semibold text-gray-900 pr-4">{{ $faq->question }}</span>
                                    <svg 
                                        class="w-5 h-5 text-teal-700 flex-shrink-0 transition-transform" 
                                        id="faq-icon-{{ $index }}"
                                        fill="none" 
                                        stroke="currentColor" 
                                        viewBox="0 0 24 24"
                                    >
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div 
                                    class="hidden px-6 pb-4 text-gray-600" 
                                    id="faq-answer-{{ $index }}"
                                >
                                    <div class="prose prose-sm max-w-none">
                                        {!! nl2br(e($faq->answer)) !!}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-12 sm:py-16 bg-gradient-to-br from-teal-700 to-teal-800 text-white">
        <div class="w-full px-4 sm:px-6 lg:px-8 xl:px-16 2xl:px-32">
            <div class="max-w-4xl mx-auto text-center">
                <h2 class="text-2xl sm:text-3xl font-bold mb-4">Still have questions?</h2>
                <p class="text-lg text-teal-100 mb-6">Our team is here to help. Contact us for more information.</p>
                <a href="{{ route('contact') }}" class="inline-block px-6 py-3 bg-white text-teal-800 rounded-lg font-semibold hover:bg-teal-50 transition-colors">
                    Contact Us
                </a>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    function toggleFaq(index) {
        const answer = document.getElementById(`faq-answer-${index}`);
        const icon = document.getElementById(`faq-icon-${index}`);
        const button = document.getElementById(`faq-button-${index}`);
        
        const isHidden = answer.classList.contains('hidden');
        
        if (isHidden) {
            answer.classList.remove('hidden');
            icon.style.transform = 'rotate(180deg)';
            button.setAttribute('aria-expanded', 'true');
        } else {
            answer.classList.add('hidden');
            icon.style.transform = 'rotate(0deg)';
            button.setAttribute('aria-expanded', 'false');
        }
    }
</script>
@endpush



