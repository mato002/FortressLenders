<div class="py-10 px-6">
    <div class="max-w-xl mx-auto text-center">
        <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $title ?? 'Notice' }}</h3>
        <p class="text-gray-600 mb-6">{{ $message ?? 'Unable to load aptitude test.' }}</p>
        <div class="flex items-center justify-center gap-3">
            <button type="button" onclick="closeAptitudeTestModal()" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-800 rounded-lg hover:bg-gray-200 transition-colors text-sm font-semibold">
                Close
            </button>
        </div>
    </div>
</div>

