@extends('layouts.admin')

@section('title', 'API Settings')

@section('header-description', 'Manage API keys and endpoints for third-party integrations.')

@section('content')
    <div class="max-w-3xl mx-auto space-y-6">
        @if (session('status'))
            <div class="bg-teal-50 border border-teal-200 text-teal-900 px-4 py-3 rounded-xl">
                {{ session('status') }}
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
            <div>
                <h2 class="text-lg font-semibold text-gray-900 mb-2">API Configuration</h2>
                <p class="text-sm text-gray-600">
                    Configure API keys, secrets, and endpoints for third-party services. Keep your credentials secure and never share them publicly.
                </p>
            </div>

            <form action="{{ route('admin.api.update') }}" method="POST" class="space-y-6">
                @csrf

                <div class="space-y-2">
                    <label for="api_name" class="block text-sm font-medium text-gray-700">API Name</label>
                    <input
                        type="text"
                        id="api_name"
                        name="api_name"
                        value="{{ old('api_name', $settings->api_name) }}"
                        placeholder="e.g., Payment Gateway API, SMS Service API"
                        class="block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                    >
                    @error('api_name')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-500">A descriptive name for this API integration.</p>
                </div>

                <div class="space-y-2">
                    <label for="api_key" class="block text-sm font-medium text-gray-700">API Key</label>
                    <input
                        type="text"
                        id="api_key"
                        name="api_key"
                        value="{{ old('api_key', $settings->api_key) }}"
                        placeholder="Enter your API key"
                        class="block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent font-mono text-sm"
                    >
                    @error('api_key')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-500">Your API key or public key for authentication.</p>
                </div>

                <div class="space-y-2">
                    <label for="api_secret" class="block text-sm font-medium text-gray-700">API Secret</label>
                    <input
                        type="password"
                        id="api_secret"
                        name="api_secret"
                        value="{{ old('api_secret', $settings->api_secret) }}"
                        placeholder="Enter your API secret"
                        class="block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent font-mono text-sm"
                    >
                    @error('api_secret')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-500">Your API secret key. This field is masked for security.</p>
                    <button
                        type="button"
                        onclick="toggleSecretVisibility()"
                        class="text-xs text-teal-600 hover:text-teal-700 font-medium"
                    >
                        <span id="toggle-text">Show</span> Secret
                    </button>
                </div>

                <div class="space-y-2">
                    <label for="api_endpoint" class="block text-sm font-medium text-gray-700">API Endpoint URL</label>
                    <input
                        type="url"
                        id="api_endpoint"
                        name="api_endpoint"
                        value="{{ old('api_endpoint', $settings->api_endpoint) }}"
                        placeholder="https://api.example.com/v1"
                        class="block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                    >
                    @error('api_endpoint')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-500">The base URL for the API endpoint.</p>
                </div>

                <div class="flex items-center space-x-3">
                    <input
                        type="checkbox"
                        id="is_active"
                        name="is_active"
                        value="1"
                        {{ old('is_active', $settings->is_active) ? 'checked' : '' }}
                        class="w-4 h-4 text-teal-600 border-gray-300 rounded focus:ring-teal-500"
                    >
                    <label for="is_active" class="text-sm font-medium text-gray-700">Enable this API integration</label>
                </div>
                @error('is_active')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror

                <div class="space-y-2">
                    <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                    <textarea
                        id="notes"
                        name="notes"
                        rows="4"
                        placeholder="Add any notes or instructions about this API integration..."
                        class="block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                    >{{ old('notes', $settings->notes) }}</textarea>
                    @error('notes')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-500">Optional notes or instructions for this API configuration.</p>
                </div>

                <div class="pt-4 border-t border-gray-200">
                    <button type="submit" class="inline-flex items-center gap-2 px-5 py-2 rounded-xl text-sm font-semibold text-white bg-teal-600 hover:bg-teal-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Save API Settings
                    </button>
                </div>
            </form>
        </div>

        <div class="bg-amber-50 border border-amber-200 rounded-2xl p-4">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-amber-600 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <div class="text-sm text-amber-800">
                    <p class="font-semibold mb-1">Security Reminder</p>
                    <p>API keys and secrets are sensitive information. Make sure to:</p>
                    <ul class="list-disc list-inside mt-2 space-y-1">
                        <li>Never commit API credentials to version control</li>
                        <li>Use environment variables for production deployments</li>
                        <li>Rotate keys regularly and when compromised</li>
                        <li>Limit API key permissions to only what's necessary</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function toggleSecretVisibility() {
            const secretInput = document.getElementById('api_secret');
            const toggleText = document.getElementById('toggle-text');
            
            if (secretInput.type === 'password') {
                secretInput.type = 'text';
                toggleText.textContent = 'Hide';
            } else {
                secretInput.type = 'password';
                toggleText.textContent = 'Show';
            }
        }
    </script>
    @endpush
@endsection


