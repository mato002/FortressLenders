@extends('layouts.website')

@section('title', 'Apply for ' . $job->title . ' - Fortress Lenders Ltd')

@section('content')
    <!-- Hero Section -->
    <section class="bg-gradient-to-br from-teal-800 via-teal-700 to-teal-900 text-white py-12 sm:py-16 md:py-20">
        <div class="w-full px-4 sm:px-6 lg:px-8 xl:px-16 2xl:px-24 text-center">
            <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold mb-3 sm:mb-4 px-4">Apply for {{ $job->title }}</h1>
            <p class="text-lg sm:text-xl text-teal-100 px-4">Complete the application form below</p>
        </div>
    </section>

    <!-- Application Form -->
    <section class="py-12 sm:py-16 md:py-20 bg-gray-50">
        <div class="w-full px-4 sm:px-6 lg:px-8 xl:px-16 2xl:px-32 max-w-5xl mx-auto">
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 sm:p-8 md:p-10">
                <!-- Progress Indicator -->
                <div class="mb-8">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full bg-teal-800 text-white flex items-center justify-center font-semibold" id="step-1-indicator">1</div>
                            <span class="ml-3 text-sm font-medium text-gray-900">Personal & Education</span>
                        </div>
                        <div class="flex-1 mx-4 h-1 bg-gray-200">
                            <div class="h-1 bg-teal-800 transition-all duration-300" id="progress-bar" style="width: 0%"></div>
                        </div>
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center font-semibold" id="step-2-indicator">2</div>
                            <span class="ml-3 text-sm font-medium text-gray-600">Support Details</span>
                        </div>
                        <div class="flex-1 mx-4 h-1 bg-gray-200">
                            <div class="h-1 bg-gray-200"></div>
                        </div>
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center font-semibold" id="step-3-indicator">3</div>
                            <span class="ml-3 text-sm font-medium text-gray-600">References & Agreement</span>
                        </div>
                    </div>
                </div>

                @if ($errors->any())
                    <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-700">
                        <p class="font-semibold mb-1">Please review the highlighted fields and try again.</p>
                        <ul class="list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('careers.apply.store', $job) }}" method="POST" enctype="multipart/form-data" id="application-form">
                    @csrf

                    <!-- Page 1: Personal & Education -->
                    <div id="page-1" class="form-page">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">Personal Information & Education</h2>

                        <div class="space-y-6">
                            <!-- Personal Details -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name <span class="text-red-500">*</span></label>
                                    <input type="text" id="name" name="name" value="{{ old('name') }}" required
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-800 focus:border-transparent">
                                    @error('name')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                </div>

                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number <span class="text-red-500">*</span></label>
                                    <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" required
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-800 focus:border-transparent">
                                    @error('phone')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                </div>

                                <div class="md:col-span-2">
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address <span class="text-red-500">*</span></label>
                                    <input type="email" id="email" name="email" value="{{ old('email') }}" required
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-800 focus:border-transparent">
                                    @error('email')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                </div>
                            </div>

                            <!-- Education Details -->
                            <div class="border-t border-gray-200 pt-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Education</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="education_level" class="block text-sm font-medium text-gray-700 mb-1">Education Level</label>
                                        <select id="education_level" name="education_level"
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-800 focus:border-transparent">
                                            <option value="">Select Education Level</option>
                                            <option value="High School" {{ old('education_level') == 'High School' ? 'selected' : '' }}>High School</option>
                                            <option value="Certificate" {{ old('education_level') == 'Certificate' ? 'selected' : '' }}>Certificate</option>
                                            <option value="Diploma" {{ old('education_level') == 'Diploma' ? 'selected' : '' }}>Diploma</option>
                                            <option value="Bachelor's Degree" {{ old('education_level') == "Bachelor's Degree" ? 'selected' : '' }}>Bachelor's Degree</option>
                                            <option value="Master's Degree" {{ old('education_level') == "Master's Degree" ? 'selected' : '' }}>Master's Degree</option>
                                            <option value="PhD" {{ old('education_level') == 'PhD' ? 'selected' : '' }}>PhD</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label for="area_of_study" class="block text-sm font-medium text-gray-700 mb-1">Area of Study</label>
                                        <input type="text" id="area_of_study" name="area_of_study" value="{{ old('area_of_study') }}"
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-800 focus:border-transparent"
                                               placeholder="e.g., Business Administration">
                                    </div>

                                    <div>
                                        <label for="institution" class="block text-sm font-medium text-gray-700 mb-1">Institution</label>
                                        <input type="text" id="institution" name="institution" value="{{ old('institution') }}"
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-800 focus:border-transparent">
                                    </div>

                                    <div>
                                        <label for="education_status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                        <select id="education_status" name="education_status"
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-800 focus:border-transparent">
                                            <option value="">Select Status</option>
                                            <option value="Completed" {{ old('education_status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                                            <option value="In Progress" {{ old('education_status') == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                            <option value="Ongoing" {{ old('education_status') == 'Ongoing' ? 'selected' : '' }}>Ongoing</option>
                                        </select>
                                    </div>

                                    <div class="md:col-span-2">
                                        <label for="other_achievements" class="block text-sm font-medium text-gray-700 mb-1">Other Achievements</label>
                                        <textarea id="other_achievements" name="other_achievements" rows="3"
                                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-800 focus:border-transparent">{{ old('other_achievements') }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Work Experience -->
                            <div class="border-t border-gray-200 pt-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Work Experience</h3>
                                
                                <div class="mb-4">
                                    <label class="flex items-center">
                                        <input type="checkbox" id="currently_working" name="currently_working" value="1" {{ old('currently_working') ? 'checked' : '' }}
                                               class="rounded border-gray-300 text-teal-800 focus:ring-teal-800">
                                        <span class="ml-2 text-sm text-gray-700">I am currently working</span>
                                    </label>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="current_job_title" class="block text-sm font-medium text-gray-700 mb-1">Current Job Title</label>
                                        <input type="text" id="current_job_title" name="current_job_title" value="{{ old('current_job_title') }}"
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-800 focus:border-transparent">
                                    </div>

                                    <div>
                                        <label for="current_company" class="block text-sm font-medium text-gray-700 mb-1">Current Company</label>
                                        <input type="text" id="current_company" name="current_company" value="{{ old('current_company') }}"
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-800 focus:border-transparent">
                                    </div>

                                    <div class="md:col-span-2">
                                        <label for="duties_and_responsibilities" class="block text-sm font-medium text-gray-700 mb-1">Duties and Responsibilities</label>
                                        <textarea id="duties_and_responsibilities" name="duties_and_responsibilities" rows="4"
                                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-800 focus:border-transparent">{{ old('duties_and_responsibilities') }}</textarea>
                                    </div>

                                    <div class="md:col-span-2">
                                        <label for="other_experiences" class="block text-sm font-medium text-gray-700 mb-1">Other Experiences</label>
                                        <textarea id="other_experiences" name="other_experiences" rows="3"
                                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-800 focus:border-transparent">{{ old('other_experiences') }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- CV Upload -->
                            <div class="border-t border-gray-200 pt-6">
                                <label for="cv" class="block text-sm font-medium text-gray-700 mb-1">Upload CV <span class="text-red-500">*</span></label>
                                <p class="text-sm text-gray-500 mb-3">Accepted formats: PDF, DOC, DOCX (Max: 5MB)</p>
                                
                                <div class="relative">
                                    <input type="file" id="cv" name="cv" accept=".pdf,.doc,.docx" required
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-800 focus:border-transparent file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-teal-800 file:text-white hover:file:bg-teal-900 file:cursor-pointer"
                                           onchange="displayFileName(this)">
                                    @error('cv')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                    
                                    <!-- File name display -->
                                    <div id="cv-file-name" class="mt-2 text-sm text-gray-600 hidden">
                                        <div class="flex items-center gap-2 p-2 bg-teal-50 border border-teal-200 rounded-lg">
                                            <svg class="w-5 h-5 text-teal-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            <span id="cv-file-name-text" class="flex-1"></span>
                                            <button type="button" onclick="clearFileInput()" class="text-red-600 hover:text-red-800">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end mt-8">
                            <button type="button" onclick="nextPage()" class="px-6 py-3 bg-teal-800 text-white rounded-lg hover:bg-teal-900 transition-colors font-semibold">
                                Next: Support Details →
                            </button>
                        </div>
                    </div>

                    <!-- Page 2: Support Details -->
                    <div id="page-2" class="form-page hidden">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">Support Details</h2>

                        <div class="space-y-6">
                            <div>
                                <label for="support_details" class="block text-sm font-medium text-gray-700 mb-1">Support Details</label>
                                <p class="text-sm text-gray-500 mb-3">Please provide any additional information that would support your application.</p>
                                <textarea id="support_details" name="support_details" rows="8"
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-800 focus:border-transparent">{{ old('support_details') }}</textarea>
                            </div>
                        </div>

                        <div class="flex justify-between mt-8">
                            <button type="button" onclick="prevPage()" class="px-6 py-3 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors font-semibold">
                                ← Previous
                            </button>
                            <button type="button" onclick="nextPage()" class="px-6 py-3 bg-teal-800 text-white rounded-lg hover:bg-teal-900 transition-colors font-semibold">
                                Next: References & Agreement →
                            </button>
                        </div>
                    </div>

                    <!-- Page 3: References & Agreement -->
                    <div id="page-3" class="form-page hidden">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">References & Agreement</h2>

                        <div class="space-y-6">
                            <!-- References -->
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">References</h3>
                                <p class="text-sm text-gray-500 mb-4">Please provide at least two professional references</p>
                                
                                <div id="referrers-container">
                                    <div class="referrer-entry mb-4 p-4 border border-gray-200 rounded-lg">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                                                <input type="text" name="referrers[0][name]" 
                                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-800 focus:border-transparent">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Position</label>
                                                <input type="text" name="referrers[0][position]" 
                                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-800 focus:border-transparent">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Company</label>
                                                <input type="text" name="referrers[0][company]" 
                                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-800 focus:border-transparent">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Contact</label>
                                                <input type="text" name="referrers[0][contact]" 
                                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-800 focus:border-transparent">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" onclick="addReferrer()" class="text-teal-800 hover:text-teal-900 font-semibold text-sm">
                                    + Add Another Reference
                                </button>
                            </div>

                            <!-- Notice Period -->
                            <div>
                                <label for="notice_period" class="block text-sm font-medium text-gray-700 mb-1">Notice Period</label>
                                <input type="text" id="notice_period" name="notice_period" value="{{ old('notice_period') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-800 focus:border-transparent"
                                       placeholder="e.g., 2 weeks, 1 month">
                            </div>

                            <!-- Application Message -->
                            <div>
                                <label for="application_message" class="block text-sm font-medium text-gray-700 mb-1">Application Message</label>
                                <p class="text-sm text-gray-500 mb-3">Any additional message you'd like to include with your application</p>
                                <textarea id="application_message" name="application_message" rows="5"
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-800 focus:border-transparent">{{ old('application_message') }}</textarea>
                            </div>

                            <!-- Agreement -->
                            <div class="border-t border-gray-200 pt-6">
                                <label class="flex items-start">
                                    <input type="checkbox" id="agreement_accepted" name="agreement_accepted" value="1" required
                                           class="mt-1 rounded border-gray-300 text-teal-800 focus:ring-teal-800">
                                    <span class="ml-2 text-sm text-gray-700">
                                        I confirm that all information provided is accurate and I agree to the terms and conditions <span class="text-red-500">*</span>
                                    </span>
                                </label>
                                @error('agreement_accepted')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div class="flex justify-between mt-8">
                            <button type="button" onclick="prevPage()" class="px-6 py-3 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors font-semibold">
                                ← Previous
                            </button>
                            <button type="submit" class="px-6 py-3 bg-teal-800 text-white rounded-lg hover:bg-teal-900 transition-colors font-semibold">
                                Submit Application
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

    @push('scripts')
    <script>
        let currentPage = 1;
        let referrerCount = 1;

        function updateProgress() {
            const progress = ((currentPage - 1) / 2) * 100;
            document.getElementById('progress-bar').style.width = progress + '%';

            // Update step indicators
            for (let i = 1; i <= 3; i++) {
                const indicator = document.getElementById(`step-${i}-indicator`);
                const label = indicator.nextElementSibling;
                if (i < currentPage) {
                    indicator.className = 'w-10 h-10 rounded-full bg-teal-800 text-white flex items-center justify-center font-semibold';
                    label.className = 'ml-3 text-sm font-medium text-gray-900';
                } else if (i === currentPage) {
                    indicator.className = 'w-10 h-10 rounded-full bg-teal-600 text-white flex items-center justify-center font-semibold';
                    label.className = 'ml-3 text-sm font-medium text-gray-900';
                } else {
                    indicator.className = 'w-10 h-10 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center font-semibold';
                    label.className = 'ml-3 text-sm font-medium text-gray-600';
                }
            }
        }

        function showPage(page) {
            for (let i = 1; i <= 3; i++) {
                document.getElementById(`page-${i}`).classList.toggle('hidden', i !== page);
            }
            currentPage = page;
            updateProgress();
        }

        function nextPage() {
            if (currentPage < 3) {
                // Validate current page before proceeding
                if (validatePage(currentPage)) {
                    showPage(currentPage + 1);
                }
            }
        }

        function prevPage() {
            if (currentPage > 1) {
                showPage(currentPage - 1);
            }
        }

        function validatePage(page) {
            const pageElement = document.getElementById(`page-${page}`);
            const requiredFields = pageElement.querySelectorAll('[required]');
            let isValid = true;

            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    field.classList.add('border-red-500');
                    isValid = false;
                } else {
                    field.classList.remove('border-red-500');
                }
            });

            if (!isValid) {
                alert('Please fill in all required fields before proceeding.');
            }

            return isValid;
        }

        function addReferrer() {
            const container = document.getElementById('referrers-container');
            const newEntry = document.createElement('div');
            newEntry.className = 'referrer-entry mb-4 p-4 border border-gray-200 rounded-lg';
            newEntry.innerHTML = `
                <div class="flex justify-between items-center mb-4">
                    <h4 class="font-semibold text-gray-900">Reference ${referrerCount + 1}</h4>
                    <button type="button" onclick="removeReferrer(this)" class="text-red-600 hover:text-red-800 text-sm font-semibold">Remove</button>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                        <input type="text" name="referrers[${referrerCount}][name]" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-800 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Position</label>
                        <input type="text" name="referrers[${referrerCount}][position]" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-800 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Company</label>
                        <input type="text" name="referrers[${referrerCount}][company]" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-800 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Contact</label>
                        <input type="text" name="referrers[${referrerCount}][contact]" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-800 focus:border-transparent">
                    </div>
                </div>
            `;
            container.appendChild(newEntry);
            referrerCount++;
        }

        function removeReferrer(button) {
            button.closest('.referrer-entry').remove();
        }

        // CV File Upload Functions
        function displayFileName(input) {
            const fileNameDiv = document.getElementById('cv-file-name');
            const fileNameText = document.getElementById('cv-file-name-text');
            
            if (input.files && input.files[0]) {
                const file = input.files[0];
                const fileName = file.name;
                const fileSize = (file.size / 1024 / 1024).toFixed(2); // Convert to MB
                
                // Validate file size (5MB max)
                if (file.size > 5 * 1024 * 1024) {
                    alert('File size exceeds 5MB. Please upload a smaller file.');
                    input.value = '';
                    fileNameDiv.classList.add('hidden');
                    return;
                }
                
                // Validate file type
                const allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
                if (!allowedTypes.includes(file.type)) {
                    alert('Invalid file type. Please upload a PDF, DOC, or DOCX file.');
                    input.value = '';
                    fileNameDiv.classList.add('hidden');
                    return;
                }
                
                fileNameText.textContent = `${fileName} (${fileSize} MB)`;
                fileNameDiv.classList.remove('hidden');
            } else {
                fileNameDiv.classList.add('hidden');
            }
        }

        function clearFileInput() {
            const fileInput = document.getElementById('cv');
            const fileNameDiv = document.getElementById('cv-file-name');
            fileInput.value = '';
            fileNameDiv.classList.add('hidden');
        }

        // Initialize
        updateProgress();
    </script>
    @endpush
@endsection

