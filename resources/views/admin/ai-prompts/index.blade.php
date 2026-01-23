@extends('layouts.admin')

@section('title', 'AI Prompt Settings')

@section('header-description', 'Manage and customize AI prompts used for candidate evaluation and CV analysis.')

@section('content')
    <div class="max-w-7xl mx-auto space-y-6">
        @if (session('success'))
            <div class="bg-teal-50 border border-teal-200 text-teal-900 px-4 py-3 rounded-xl">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-900 px-4 py-3 rounded-xl">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Role Filter -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Filter by Role</h2>
            </div>
            <div class="flex flex-wrap gap-2">
                @foreach($roles as $roleKey => $roleName)
                    <a href="{{ route('admin.ai-prompts.index', ['role' => $roleKey]) }}" 
                       class="px-4 py-2 rounded-lg text-sm font-medium transition-colors
                       @if($selectedRole === $roleKey)
                           bg-teal-600 text-white
                       @else
                           bg-gray-100 text-gray-700 hover:bg-gray-200
                       @endif">
                        {{ $roleName }}
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Prompts List -->
        <div class="space-y-6">
            @foreach($prompts as $promptType => $prompt)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-1">
                            <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $prompt['name'] }}</h3>
                            <p class="text-sm text-gray-600 mb-2">{{ $prompt['description'] }}</p>
                            <div class="flex flex-wrap gap-2 text-xs text-gray-500">
                                <span><strong>When Used:</strong> {{ $prompt['when_used'] }}</span>
                                <span>•</span>
                                <span><strong>Location:</strong> {{ $prompt['location'] }}</span>
                                @if(isset($prompt['version']))
                                    <span>•</span>
                                    <span><strong>Version:</strong> {{ $prompt['version'] }}</span>
                                @endif
                                @if(isset($prompt['updated_at']))
                                    <span>•</span>
                                    <span><strong>Last Updated:</strong> {{ $prompt['updated_at']->format('M d, Y H:i') }}</span>
                                @endif
                            </div>
                        </div>
                        @if(isset($prompt['stored_id']))
                            <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-semibold bg-teal-100 text-teal-800">
                                Customized
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-semibold bg-gray-100 text-gray-800">
                                Default
                            </span>
                        @endif
                    </div>

                    <form method="POST" action="{{ route('admin.ai-prompts.update') }}" class="space-y-4">
                        @csrf
                        <input type="hidden" name="prompt_type" value="{{ $promptType }}">
                        <input type="hidden" name="role" value="{{ $selectedRole === 'default' ? null : $selectedRole }}">

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Prompt Content</label>
                            <textarea 
                                name="content" 
                                rows="12" 
                                required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-600 focus:border-transparent font-mono text-sm"
                            >{{ old('content', $prompt['content']) }}</textarea>
                            @error('content')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Description (Optional)</label>
                            <input 
                                type="text" 
                                name="description" 
                                value="{{ old('description', $prompt['description'] ?? '') }}"
                                maxlength="500"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-600 focus:border-transparent"
                                placeholder="Brief description of this prompt's purpose"
                            >
                            @error('description')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center gap-3">
                            <button 
                                type="submit" 
                                class="px-6 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors font-medium"
                            >
                                Save Prompt
                            </button>
                            
                            @if(isset($prompt['stored_id']))
                                <form method="POST" action="{{ route('admin.ai-prompts.reset') }}" class="inline">
                                    @csrf
                                    <input type="hidden" name="prompt_type" value="{{ $promptType }}">
                                    <input type="hidden" name="role" value="{{ $selectedRole === 'default' ? null : $selectedRole }}">
                                    <button 
                                        type="submit" 
                                        class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors font-medium reset-prompt-btn"
                                        data-prompt-type="{{ $promptType }}"
                                    >
                                        Reset to Default
                                    </button>
                                </form>
                            @endif
                        </div>
                    </form>
                </div>
            @endforeach
        </div>

        <!-- Info Box -->
        <div class="bg-blue-50 border border-blue-200 rounded-2xl p-6">
            <h3 class="text-lg font-semibold text-blue-900 mb-2">ℹ️ About AI Prompts</h3>
            <ul class="text-sm text-blue-800 space-y-2 list-disc list-inside">
                <li>Prompts are used by the AI service to analyze candidates and CVs</li>
                <li>You can customize prompts for specific roles (Admin, HR Manager, Client) or use default prompts for all roles</li>
                <li>When a prompt is customized, it will be used instead of the default</li>
                <li>Version tracking helps you keep track of prompt changes</li>
                <li>Use placeholders like <code class="bg-blue-100 px-1 rounded">{job_post->title}</code> to dynamically insert data</li>
            </ul>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle reset prompt button with SweetAlert
        document.querySelectorAll('.reset-prompt-btn').forEach(function(button) {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const form = this.closest('form');
                const promptType = this.getAttribute('data-prompt-type');
                
                Swal.fire({
                    title: 'Reset to Default?',
                    html: `Are you sure you want to reset this prompt to default?<br><br><strong>This will delete your custom version.</strong>`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Yes, reset it!',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Resetting...',
                            allowOutsideClick: false,
                            showConfirmButton: false,
                            didOpen: () => Swal.showLoading()
                        });
                        form.submit();
                    }
                });
            });
        });
    });
</script>
@endpush
