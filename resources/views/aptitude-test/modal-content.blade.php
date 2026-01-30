@php
    $isCandidate = isset($isCandidateView) && $isCandidateView;
@endphp

<div class="aptitude-test-modal-content">
    <!-- Header -->
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-2">Aptitude Test</h2>
        <p class="text-gray-600">Position: <span class="font-semibold">{{ $application->jobPost->title ?? 'N/A' }}</span></p>
        <p class="text-sm text-gray-500 mt-1">Time Limit: 30 minutes | Total Questions: {{ $questions->count() }}</p>
    </div>

    <!-- Timer -->
    <div class="mb-6 bg-teal-50 border border-teal-200 rounded-xl p-4">
        <div class="flex items-center justify-between">
            <span class="text-sm font-medium text-teal-900">Time Remaining:</span>
            <span id="timer" class="text-2xl font-bold text-teal-700">30:00</span>
        </div>
    </div>

    <!-- Test Form -->
    <form id="testForm" method="POST" action="{{ route('aptitude-test.submit', $application) }}">
        @csrf
        
        @php
            $sectionOrder = ['numerical', 'logical', 'verbal', 'scenario'];
            $currentSection = null;
        @endphp

        @foreach($questions as $index => $question)
            @if($currentSection !== $question->section)
                @if($currentSection !== null)
                    </div>
                @endif
                @php
                    $currentSection = $question->section;
                    $sectionTitles = [
                        'numerical' => 'Section A: Numerical & Analytical',
                        'logical' => 'Section B: Logical Reasoning',
                        'verbal' => 'Section C: Verbal & Comprehension',
                        'scenario' => 'Section D: Job-Fit Scenarios'
                    ];
                @endphp
                <div class="mb-8">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4 pb-2 border-b-2 border-teal-500">
                        {{ $sectionTitles[$question->section] ?? ucfirst($question->section) }}
                    </h3>
            @endif

            <div class="mb-8 p-6 bg-gray-50 rounded-xl border border-gray-200">
                <div class="flex items-start justify-between mb-4">
                    <span class="text-sm font-medium text-gray-500">Question {{ $index + 1 }}</span>
                    <span class="text-xs text-gray-400">{{ $question->points }} points</span>
                </div>
                
                <p class="text-gray-900 font-medium mb-4 leading-relaxed">{{ $question->question }}</p>
                
                @if($question->isMultipleChoice() && !empty($question->options))
                    {{-- Multiple Choice Questions --}}
                    <div class="space-y-3">
                        @foreach($question->options as $key => $option)
                            <label class="flex items-start p-3 rounded-lg border-2 border-gray-200 hover:border-teal-400 cursor-pointer transition">
                                <input type="radio" 
                                       name="answers[{{ $question->id }}]" 
                                       value="{{ $key }}"
                                       class="mt-1 mr-3 w-4 h-4 text-teal-600 focus:ring-teal-500"
                                       @if(old("answers.{$question->id}") === $key) checked @endif>
                                <span class="flex-1 text-gray-700">
                                    <span class="font-semibold mr-2">{{ strtoupper($key) }}.</span>
                                    {{ $option }}
                                </span>
                            </label>
                        @endforeach
                    </div>
                @elseif($question->isCalculation())
                    {{-- Calculation Questions --}}
                    <div class="space-y-3">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Enter your answer (numeric value):
                        </label>
                        <input type="text" 
                               name="answers[{{ $question->id }}]" 
                               value="{{ old("answers.{$question->id}") }}"
                               placeholder="Enter your calculated answer"
                               class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-gray-900"
                               inputmode="numeric"
                               pattern="[0-9]*\.?[0-9]*">
                        <p class="text-xs text-gray-500 mt-1">
                            Enter the numeric result of your calculation
                        </p>
                    </div>
                @else
                    {{-- Text/Open-ended Questions --}}
                    <div class="space-y-3">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Enter your answer:
                        </label>
                        <textarea name="answers[{{ $question->id }}]" 
                                  rows="4"
                                  placeholder="Type your answer here..."
                                  class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-gray-900">{{ old("answers.{$question->id}") }}</textarea>
                        <p class="text-xs text-gray-500 mt-1">
                            Provide a detailed answer. This will be reviewed manually.
                        </p>
                    </div>
                @endif
            </div>
        @endforeach
        </div>

        <!-- Submit Button -->
        <div class="mt-8 pt-6 border-t border-gray-200">
            <button type="submit" 
                    id="submitBtn"
                    class="w-full sm:w-auto px-8 py-3 bg-teal-600 hover:bg-teal-700 text-white font-semibold rounded-xl transition shadow-lg hover:shadow-xl">
                Submit Test
            </button>
            <p class="text-sm text-gray-500 mt-3">Make sure you've answered all questions before submitting.</p>
        </div>
    </form>
</div>

<script>
(function() {
    let timeLeft = 30 * 60; // 30 minutes in seconds
    const timerElement = document.getElementById('timer');
    const form = document.getElementById('testForm');
    let timerInterval;

    function updateTimer() {
        const minutes = Math.floor(timeLeft / 60);
        const seconds = timeLeft % 60;
        if (timerElement) {
            timerElement.textContent = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
        }
        
        if (timeLeft <= 0) {
            clearInterval(timerInterval);
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Time is up!',
                    text: 'Your test will be submitted automatically.',
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    if (form) form.submit();
                });
            } else if (form) {
                form.submit();
            }
            return;
        }
        
        // Warning at 5 minutes
        if (timeLeft === 5 * 60 && typeof Swal !== 'undefined') {
            if (timerElement) timerElement.classList.add('text-red-600');
            Swal.fire({
                icon: 'warning',
                title: '5 Minutes Remaining!',
                text: 'Please complete your test soon.',
                timer: 3000,
                showConfirmButton: false
            });
        }
        
        timeLeft--;
    }

    // Start timer
    if (timerElement && form) {
        timerInterval = setInterval(updateTimer, 1000);
        updateTimer();

        // Prevent accidental navigation
        window.addEventListener('beforeunload', function(e) {
            const submitBtn = form.querySelector('[type="submit"]');
            if (submitBtn && !submitBtn.disabled) {
                e.preventDefault();
                e.returnValue = '';
            }
        });

        // Confirm submission
        form.addEventListener('submit', function(e) {
            const radioInputs = form.querySelectorAll('input[type="radio"]');
            const checkedInputs = form.querySelectorAll('input[type="radio"]:checked');
            const textInputs = form.querySelectorAll('input[type="text"][name^="answers"], textarea[name^="answers"]');
            const answeredTextInputs = Array.from(textInputs).filter(input => input.value.trim() !== '');
            
            const totalQuestions = {{ $questions->count() }};
            const answered = checkedInputs.length + answeredTextInputs.length;
            
            if (answered < totalQuestions) {
                e.preventDefault();
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Incomplete Test',
                        html: `You have answered <strong>${answered}</strong> out of <strong>${totalQuestions}</strong> questions.<br><br>Are you sure you want to submit?`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#14b8a6',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'Yes, submit',
                        cancelButtonText: 'Continue answering',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            clearInterval(timerInterval);
                            form.submit();
                        }
                    });
                } else {
                    if (!confirm(`You have answered ${answered} out of ${totalQuestions} questions. Are you sure you want to submit?`)) {
                        e.preventDefault();
                    } else {
                        clearInterval(timerInterval);
                    }
                }
                return false;
            }
            
            clearInterval(timerInterval);
            const submitBtn = form.querySelector('[type="submit"]');
            if (submitBtn) submitBtn.disabled = true;
        });
    }
})();
</script>
