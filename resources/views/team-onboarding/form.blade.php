<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Team Onboarding - Fortress Lenders Ltd</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gray-100 text-gray-900 antialiased">
    <div class="min-h-screen flex flex-col items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-xl bg-white rounded-2xl shadow-xl border border-gray-200 p-6 sm:p-8 md:p-10">
            <div class="text-center mb-8">
                <h1 class="text-2xl sm:text-3xl font-bold text-teal-800 mb-2">Join Our Team</h1>
                <p class="text-gray-600 text-sm">Add your profile to the Fortress Lenders team page</p>
            </div>

            <p class="text-gray-600 text-sm mb-6">Fill in your details below. Your profile will be reviewed by our team before being added to the website.</p>

            @if ($errors->any())
                <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-700">
                    <p class="font-semibold mb-1">Please fix the following:</p>
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('team.onboarding.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name <span class="text-red-500">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500"
                        placeholder="Your full name">
                </div>

                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Role / Title</label>
                    <input type="text" id="role" name="role" value="{{ old('role') }}"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500"
                        placeholder="e.g. Branch Manager, Credit Officer">
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500"
                        placeholder="your.email@example.com">
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone') }}"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500"
                        placeholder="e.g. +254 7XX XXX XXX">
                </div>

                <div>
                    <label for="linkedin_url" class="block text-sm font-medium text-gray-700 mb-1">LinkedIn Profile URL</label>
                    <input type="url" id="linkedin_url" name="linkedin_url" value="{{ old('linkedin_url') }}"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500"
                        placeholder="https://linkedin.com/in/yourprofile">
                </div>

                <div>
                    <label for="bio" class="block text-sm font-medium text-gray-700 mb-1">Short Bio</label>
                    <textarea id="bio" name="bio" rows="4"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 resize-none"
                        placeholder="A brief introduction about yourself">{{ old('bio') }}</textarea>
                </div>

                <div>
                    <label for="photo" class="block text-sm font-medium text-gray-700 mb-1">Profile Photo (optional)</label>
                    <input type="file" id="photo" name="photo" accept="image/jpeg,image/png,image/jpg"
                        class="block w-full text-sm text-gray-700 file:mr-3 file:py-2 file:px-3 file:rounded file:border-0 file:bg-teal-50 file:text-teal-700">
                    <p class="text-xs text-gray-500 mt-1">JPG or PNG, max 4 MB.</p>
                </div>

                <div class="pt-2">
                    <button type="submit"
                        class="w-full px-6 py-3 bg-teal-700 hover:bg-teal-800 text-white font-semibold rounded-lg">
                        Submit My Profile
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
