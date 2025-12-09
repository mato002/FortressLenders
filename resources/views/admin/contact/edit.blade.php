@extends('layouts.admin')

@section('title', 'Contact Page Settings')

@section('header-description', 'Control the hero background image for the public Contact page.')

@section('header-actions')
    <a href="{{ route('contact') }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold text-teal-800 bg-teal-50 hover:bg-teal-100 border border-teal-100">
        Preview Contact Page
    </a>
@endsection

@section('content')
    <div class="max-w-3xl mx-auto space-y-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">
            <h2 class="text-lg font-semibold text-gray-900">Hero Background Image</h2>
            <p class="text-sm text-gray-600">
                Upload an image that will appear behind the “Contact Us” hero section on the public website.
                A wide image works best (e.g. 1600×600).
            </p>

            <form action="{{ route('admin.contact.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Current Image</label>
                    @if ($settings->hero_image_path)
                        <div class="rounded-xl overflow-hidden border border-gray-100 bg-gray-50">
                            <img src="{{ asset('storage/'.$settings->hero_image_path) }}" alt="Contact hero background" class="w-full h-48 object-cover">
                        </div>
                    @else
                        <p class="text-sm text-gray-500">No custom image set yet. The default teal gradient will be used.</p>
                    @endif
                </div>

                <div class="space-y-2">
                    <label for="hero_image" class="block text-sm font-medium text-gray-700">Upload New Image</label>
                    <input
                        id="hero_image"
                        name="hero_image"
                        type="file"
                        accept="image/*"
                        class="block w-full text-sm text-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100"
                    >
                    @error('hero_image')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-500">Max size 4MB. JPEG or PNG recommended.</p>
                </div>

                <div class="pt-4">
                    <button type="submit" class="inline-flex items-center gap-2 px-5 py-2 rounded-xl text-sm font-semibold text-white bg-teal-600 hover:bg-teal-700">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection








