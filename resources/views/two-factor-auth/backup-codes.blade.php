@extends('layouts.website')

@section('title', 'Save Backup Codes')

@section('content')
    <div class="max-w-md mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-2">Save Your Backup Codes</h1>
            <p class="text-gray-600 mb-6">Store these codes in a safe place. You can use them to access your account if you lose your authenticator.</p>

            <div class="space-y-6">
                <!-- Warning -->
                <div class="p-4 bg-amber-50 border border-amber-200 rounded-lg">
                    <p class="text-sm text-amber-900 font-semibold">Each code can only be used once. Save them securely!</p>
                </div>

                <!-- Backup Codes -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="grid grid-cols-2 gap-2">
                        @foreach($backupCodes as $code)
                            <div class="font-mono text-sm text-gray-900">{{ $code }}</div>
                        @endforeach
                    </div>
                </div>

                <!-- Download/Copy -->
                <div class="flex gap-2">
                    <button onclick="copyToClipboard()" class="flex-1 px-4 py-2 rounded-lg text-sm font-semibold text-teal-600 border border-teal-200 hover:bg-teal-50">
                        Copy Codes
                    </button>
                    <button onclick="downloadCodes()" class="flex-1 px-4 py-2 rounded-lg text-sm font-semibold text-teal-600 border border-teal-200 hover:bg-teal-50">
                        Download
                    </button>
                </div>

                <!-- Confirm -->
                <a href="{{ route('profile.edit') }}" class="block w-full text-center px-4 py-2 rounded-lg text-sm font-semibold text-white bg-teal-800 hover:bg-teal-900">
                    I've Saved My Codes
                </a>
            </div>
        </div>
    </div>

    <script>
        function copyToClipboard() {
            const codes = document.querySelector('.grid').innerText;
            navigator.clipboard.writeText(codes).then(() => {
                alert('Codes copied to clipboard!');
            });
        }

        function downloadCodes() {
            const codes = document.querySelector('.grid').innerText;
            const element = document.createElement('a');
            element.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(codes));
            element.setAttribute('download', 'fortress-lenders-backup-codes.txt');
            element.style.display = 'none';
            document.body.appendChild(element);
            element.click();
            document.body.removeChild(element);
        }
    </script>
@endsection
