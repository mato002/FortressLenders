@extends('layouts.admin')

@section('title', 'Aptitude Test Questions')

@section('header-description', 'Manage aptitude test questions for candidate assessments.')

@section('header-actions')
    <a href="{{ route('admin.aptitude-test.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold text-white bg-teal-600 hover:bg-teal-700">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Add Question
    </a>
@endsection

@section('content')
    @if (session('success'))
        <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
            {{ session('success') }}
        </div>
    @endif

    <!-- Section Counts -->
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mb-6">
        @foreach($sectionCounts as $section => $count)
            @php
                $colors = [
                    'numerical' => 'from-blue-500 to-indigo-600',
                    'logical' => 'from-purple-500 to-pink-600',
                    'verbal' => 'from-green-500 to-teal-600',
                    'scenario' => 'from-orange-500 to-red-600',
                ];
                $labels = [
                    'numerical' => 'Numerical',
                    'logical' => 'Logical',
                    'verbal' => 'Verbal',
                    'scenario' => 'Scenario',
                ];
            @endphp
            <div class="bg-gradient-to-br {{ $colors[$section] }} rounded-xl p-4 text-white">
                <p class="text-sm opacity-90 mb-1">{{ $labels[$section] }}</p>
                <p class="text-3xl font-bold">{{ $count }}</p>
            </div>
        @endforeach
    </div>

    <!-- Filters -->
    <form method="GET" class="mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Job Post</label>
                    <select name="job_post_id" class="w-full px-4 py-2 border border-gray-200 rounded-lg">
                        <option value="">All Job Posts</option>
                        <option value="global" @selected(request('job_post_id') === 'global')>Global Questions Only</option>
                        @foreach($jobPosts as $jobPost)
                            <option value="{{ $jobPost->id }}" @selected(request('job_post_id') == $jobPost->id)>
                                {{ $jobPost->title }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Section</label>
                    <select name="section" class="w-full px-4 py-2 border border-gray-200 rounded-lg">
                        <option value="">All Sections</option>
                        <option value="numerical" @selected(request('section') === 'numerical')>Numerical</option>
                        <option value="logical" @selected(request('section') === 'logical')>Logical</option>
                        <option value="verbal" @selected(request('section') === 'verbal')>Verbal</option>
                        <option value="scenario" @selected(request('section') === 'scenario')>Scenario</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="is_active" class="w-full px-4 py-2 border border-gray-200 rounded-lg">
                        <option value="all">All</option>
                        <option value="1" @selected(request('is_active') === '1')>Active</option>
                        <option value="0" @selected(request('is_active') === '0')>Inactive</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700">
                        Filter
                    </button>
                </div>
            </div>
        </div>
    </form>

    <!-- Questions Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Section</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Question</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Job Post</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Points</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($questions as $question)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                                {{ ucfirst($question->section) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900 max-w-md truncate">
                                {{ Str::limit($question->question, 80) }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($question->jobPost)
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-purple-100 text-purple-800">
                                    {{ $question->jobPost->title }}
                                </span>
                            @else
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800">
                                    Global
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $question->points }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-medium rounded-full {{ $question->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $question->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.aptitude-test.edit', ['aptitude_test' => $question]) }}" class="text-blue-600 hover:text-blue-900">Edit</a>
                                <form action="{{ route('admin.aptitude-test.toggle-status', ['question' => $question]) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-yellow-600 hover:text-yellow-900">
                                        {{ $question->is_active ? 'Deactivate' : 'Activate' }}
                                    </button>
                                </form>
                                <form action="{{ route('admin.aptitude-test.destroy', ['aptitude_test' => $question]) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this question?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            No questions found. <a href="{{ route('admin.aptitude-test.create') }}" class="text-teal-600 hover:text-teal-700">Create your first question</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $questions->links() }}
    </div>
@endsection

