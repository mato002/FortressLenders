@php use Illuminate\Support\Str; @endphp
@extends('layouts.admin')

@section('title', 'Loan Application Details')
@section('header-description', 'Review the full details of this loan application.')

@section('content')
    <div class="space-y-6">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h1 class="text-xl font-semibold text-slate-900">Loan Application</h1>
                <p class="text-sm text-slate-500 mt-1">
                    Submitted {{ $loanApplication->created_at->format('M d, Y H:i') }}
                </p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.loan-applications.index') }}" class="px-3 py-2 text-sm rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50">
                    Back to list
                </a>
            </div>
        </div>

        @if (session('status'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                {{ session('status') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main details -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 sm:p-6">
                    <h2 class="text-sm font-semibold text-slate-700 uppercase tracking-wide mb-4">Client Personal Information</h2>
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                        <div>
                            <dt class="text-slate-500">Full Name</dt>
                            <dd class="text-slate-900 font-medium">{{ $loanApplication->full_name }}</dd>
                        </div>
                        <div>
                            <dt class="text-slate-500">Phone</dt>
                            <dd class="text-slate-900 font-medium">{{ $loanApplication->phone }}</dd>
                        </div>
                        <div>
                            <dt class="text-slate-500">Email</dt>
                            <dd class="text-slate-900">{{ $loanApplication->email ?? 'Not provided' }}</dd>
                        </div>
                        <div>
                            <dt class="text-slate-500">Date of Birth</dt>
                            <dd class="text-slate-900">
                                {{ optional($loanApplication->date_of_birth)->format('d M Y') ?? 'Not provided' }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-slate-500">Town</dt>
                            <dd class="text-slate-900">{{ $loanApplication->town ?? 'Not provided' }}</dd>
                        </div>
                        <div>
                            <dt class="text-slate-500">Residence</dt>
                            <dd class="text-slate-900">{{ $loanApplication->residence ?? 'Not provided' }}</dd>
                        </div>
                        <div>
                            <dt class="text-slate-500">Client Type</dt>
                            <dd class="text-slate-900">
                                {{ $loanApplication->client_type ? ucfirst($loanApplication->client_type) : 'Not specified' }}
                            </dd>
                        </div>
                    </dl>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 sm:p-6">
                    <h2 class="text-sm font-semibold text-slate-700 uppercase tracking-wide mb-4">Loan Information</h2>
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                        <div>
                            <dt class="text-slate-500">Loan Type</dt>
                            <dd class="text-slate-900 font-medium">{{ $loanApplication->loan_type }}</dd>
                        </div>
                        <div>
                            <dt class="text-slate-500">Amount Requested</dt>
                            <dd class="text-slate-900 font-medium">KES {{ number_format($loanApplication->amount_requested, 2) }}</dd>
                        </div>
                        <div>
                            <dt class="text-slate-500">Repayment Period</dt>
                            <dd class="text-slate-900">{{ $loanApplication->repayment_period }}</dd>
                        </div>
                        <div>
                            <dt class="text-slate-500">Agreed to Terms</dt>
                            <dd class="text-slate-900">
                                {{ $loanApplication->agreed_to_terms ? 'Yes' : 'No' }}
                            </dd>
                        </div>
                    </dl>

                    <div class="mt-4">
                        <dt class="text-slate-500 text-sm mb-1">Purpose of Loan</dt>
                        <dd class="text-slate-900 text-sm whitespace-pre-line bg-slate-50 rounded-xl p-3 min-h-[60px]">
                            {{ $loanApplication->purpose ?: 'Not provided' }}
                        </dd>
                    </div>
                </div>
            </div>

            <!-- Status / admin notes -->
            <div class="space-y-6">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 sm:p-6">
                    <h2 class="text-sm font-semibold text-slate-700 uppercase tracking-wide mb-4">Status</h2>

                    @php
                        $status = $loanApplication->status;
                        $statusClasses = match($status) {
                            'pending' => 'bg-amber-50 text-amber-700',
                            'in_review' => 'bg-blue-50 text-blue-700',
                            'approved' => 'bg-emerald-50 text-emerald-700',
                            'rejected' => 'bg-rose-50 text-rose-700',
                            default => 'bg-slate-50 text-slate-700',
                        };
                    @endphp

                    <div class="flex items-center justify-between mb-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $statusClasses }}">
                            {{ Str::headline($status) }}
                        </span>
                        @if($loanApplication->handled_at)
                            <p class="text-xs text-slate-500">
                                Updated {{ $loanApplication->handled_at->diffForHumans() }}
                            </p>
                        @endif
                    </div>

                    <form method="POST" action="{{ route('admin.loan-applications.update', $loanApplication) }}" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div class="space-y-1">
                            <label for="status" class="text-xs font-medium text-slate-600">Update Status</label>
                            <select id="status" name="status"
                                    class="w-full text-sm border-slate-200 rounded-xl px-3 py-2 focus:ring-2 focus:ring-teal-600 focus:border-transparent">
                                <option value="pending" @selected($loanApplication->status === 'pending')>Pending</option>
                                <option value="in_review" @selected($loanApplication->status === 'in_review')>In review</option>
                                <option value="approved" @selected($loanApplication->status === 'approved')>Approved</option>
                                <option value="rejected" @selected($loanApplication->status === 'rejected')>Rejected</option>
                            </select>
                        </div>

                        <div class="space-y-1">
                            <label for="admin_notes" class="text-xs font-medium text-slate-600">Internal Notes</label>
                            <textarea id="admin_notes" name="admin_notes" rows="4"
                                      class="w-full text-sm border-slate-200 rounded-xl px-3 py-2 focus:ring-2 focus:ring-teal-600 focus:border-transparent"
                                      placeholder="Add any internal comments for your team">{{ old('admin_notes', $loanApplication->admin_notes) }}</textarea>
                        </div>

                        <button type="submit"
                                class="w-full inline-flex justify-center items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold text-white bg-teal-700 hover:bg-teal-800 focus:ring-2 focus:ring-offset-1 focus:ring-teal-600">
                            Save Changes
                        </button>
                    </form>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-rose-100 p-4">
                    <form method="POST" action="{{ route('admin.loan-applications.destroy', $loanApplication) }}"
                          onsubmit="return confirm('Are you sure you want to delete this application? This action cannot be undone.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="w-full inline-flex justify-center items-center gap-2 px-3 py-2 rounded-xl text-xs font-semibold text-rose-700 bg-rose-50 hover:bg-rose-100 border border-rose-200">
                            Delete Application
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Message Section -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 sm:p-6 space-y-4">
            <h2 class="text-lg font-semibold text-slate-900">Send Message to Applicant</h2>
            
            @if(session('status'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-lg">
                    {{ session('status') }}
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.loan-applications.message', $loanApplication) }}" class="space-y-4" id="message-form">
                @csrf
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Message Channel <span class="text-red-500">*</span></label>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <label class="flex items-center p-3 border-2 border-slate-200 rounded-lg cursor-pointer hover:border-teal-500 transition-colors message-channel-option">
                            <input type="radio" name="channel" value="email" class="sr-only" checked onchange="updateRecipient()">
                            <div class="flex items-center gap-3 w-full">
                                <svg class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                <span class="font-medium">Email</span>
                            </div>
                        </label>
                        <label class="flex items-center p-3 border-2 border-slate-200 rounded-lg cursor-pointer hover:border-teal-500 transition-colors message-channel-option">
                            <input type="radio" name="channel" value="sms" class="sr-only" onchange="updateRecipient()">
                            <div class="flex items-center gap-3 w-full">
                                <svg class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                </svg>
                                <span class="font-medium">SMS</span>
                            </div>
                        </label>
                        <label class="flex items-center p-3 border-2 border-slate-200 rounded-lg cursor-pointer hover:border-teal-500 transition-colors message-channel-option">
                            <input type="radio" name="channel" value="whatsapp" class="sr-only" onchange="updateRecipient()">
                            <div class="flex items-center gap-3 w-full">
                                <svg class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                </svg>
                                <span class="font-medium">WhatsApp</span>
                            </div>
                        </label>
                    </div>
                </div>

                <div>
                    <label for="recipient" class="block text-sm font-medium text-slate-700 mb-1">Recipient <span class="text-red-500">*</span></label>
                    <input type="text" id="recipient" name="recipient" value="{{ $loanApplication->email }}" required
                           class="w-full px-4 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-600 focus:border-transparent">
                    <p class="text-xs text-slate-500 mt-1" id="recipient-hint">Enter email address</p>
                </div>

                <div>
                    <label for="message" class="block text-sm font-medium text-slate-700 mb-1">Message <span class="text-red-500">*</span></label>
                    <textarea id="message" name="message" rows="6" required maxlength="5000"
                              class="w-full px-4 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-600 focus:border-transparent"
                              placeholder="Type your message here..."></textarea>
                    <p class="text-xs text-slate-500 mt-1">
                        <span id="char-count">0</span> / 5000 characters
                        <span id="sms-count" class="hidden"> (Approx. <span id="sms-messages">0</span> SMS)</span>
                    </p>
                </div>

                <div class="flex justify-end gap-3">
                    <button type="button" onclick="document.getElementById('message-form').reset(); updateRecipient();" 
                            class="px-4 py-2 border border-slate-200 rounded-xl text-slate-700 font-semibold hover:bg-slate-50">
                        Clear
                    </button>
                    <button type="submit" class="px-6 py-2 bg-teal-700 text-white rounded-xl font-semibold hover:bg-teal-800">
                        Send Message
                    </button>
                </div>
            </form>
        </div>

        <!-- Message History -->
        @php
            $messages = $loanApplication->messages ?? collect();
        @endphp
        @if($messages->count() > 0)
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 sm:p-6 space-y-4">
                <h2 class="text-lg font-semibold text-slate-900">Message History</h2>
                <div class="space-y-3">
                    @foreach($messages->sortByDesc('created_at') as $message)
                        <div class="border border-slate-200 rounded-lg p-4 {{ $message->status === 'failed' ? 'bg-red-50' : 'bg-slate-50' }}">
                            <div class="flex items-start justify-between mb-2">
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold
                                        @if($message->status === 'sent') bg-emerald-100 text-emerald-800
                                        @elseif($message->status === 'failed') bg-red-100 text-red-800
                                        @else bg-amber-100 text-amber-800
                                        @endif">
                                        {{ strtoupper($message->channel) }} - {{ Str::headline($message->status) }}
                                    </span>
                                    <span class="text-xs text-slate-500">
                                        {{ $message->created_at->format('M d, Y g:i A') }}
                                    </span>
                                </div>
                                <span class="text-xs text-slate-500">
                                    By {{ $message->sender->name ?? 'Admin' }}
                                </span>
                            </div>
                            <p class="text-sm text-slate-700 mb-2">
                                <strong>To:</strong> {{ $message->recipient }}
                            </p>
                            <div class="text-sm text-slate-800 whitespace-pre-line bg-white rounded p-3 border border-slate-200">
                                {{ $message->message }}
                            </div>
                            @if($message->error_message)
                                <p class="text-xs text-red-600 mt-2">
                                    <strong>Error:</strong> {{ $message->error_message }}
                                </p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    @push('styles')
    <style>
        .message-channel-option input:checked + div {
            color: #0d9488;
        }
        .message-channel-option:has(input:checked) {
            border-color: #14b8a6;
            background-color: #f0fdfa;
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        function updateRecipient() {
            const channel = document.querySelector('input[name="channel"]:checked')?.value;
            if (!channel) return;
            
            const recipientInput = document.getElementById('recipient');
            const recipientHint = document.getElementById('recipient-hint');
            const channelOptions = document.querySelectorAll('.message-channel-option');

            // Update visual selection
            channelOptions.forEach(option => {
                const input = option.querySelector('input[type="radio"]');
                if (input && input.checked) {
                    option.classList.add('border-teal-500', 'bg-teal-50');
                    option.querySelector('svg').classList.remove('text-slate-600');
                    option.querySelector('svg').classList.add('text-teal-600');
                    option.querySelector('span').classList.remove('text-slate-700');
                    option.querySelector('span').classList.add('text-teal-700');
                } else {
                    option.classList.remove('border-teal-500', 'bg-teal-50');
                    option.querySelector('svg').classList.add('text-slate-600');
                    option.querySelector('svg').classList.remove('text-teal-600');
                    option.querySelector('span').classList.add('text-slate-700');
                    option.querySelector('span').classList.remove('text-teal-700');
                }
            });

            // Update recipient field based on channel
            if (channel === 'email') {
                recipientInput.type = 'email';
                recipientInput.value = '{{ $loanApplication->email }}';
                recipientHint.textContent = 'Enter email address';
            } else {
                recipientInput.type = 'tel';
                recipientInput.value = '{{ $loanApplication->phone ?? "" }}';
                recipientHint.textContent = 'Enter phone number (e.g., 0712345678 or +254712345678)';
            }
        }

        // Character counter and SMS calculator
        document.getElementById('message').addEventListener('input', function() {
            const message = this.value;
            const charCount = message.length;
            const charCountEl = document.getElementById('char-count');
            const smsCountEl = document.getElementById('sms-count');
            const smsMessagesEl = document.getElementById('sms-messages');
            const channel = document.querySelector('input[name="channel"]:checked')?.value;

            charCountEl.textContent = charCount;

            // Show SMS count for SMS channel
            if (channel === 'sms') {
                // Standard SMS is 160 characters, longer messages are split
                const smsCount = Math.ceil(charCount / 160);
                smsMessagesEl.textContent = smsCount;
                smsCountEl.classList.remove('hidden');
            } else {
                smsCountEl.classList.add('hidden');
            }
        });

        // Update on channel change
        document.querySelectorAll('input[name="channel"]').forEach(radio => {
            radio.addEventListener('change', function() {
                updateRecipient();
                // Trigger character count update
                document.getElementById('message').dispatchEvent(new Event('input'));
            });
        });

        // Initialize
        updateRecipient();
    </script>
    @endpush
@endsection



