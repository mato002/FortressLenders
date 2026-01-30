@extends('layouts.admin')

@section('title', 'Job Applications Report')
@section('header-description', 'Detailed analysis of job applications, funnel, and hiring metrics.')

@section('header-actions')
    <form method="GET" class="flex gap-2">
        <input type="date" name="start_date" value="{{ $startDate }}" class="px-4 py-2 border border-gray-300 rounded-lg text-sm">
        <input type="date" name="end_date" value="{{ $endDate }}" class="px-4 py-2 border border-gray-300 rounded-lg text-sm">
        <button type="submit" class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-semibold text-gray-700 hover:bg-gray-50">Filter</button>
    </form>
    <form action="{{ route('admin.reports.export-job-applications') }}" method="GET" class="inline">
        <input type="hidden" name="start_date" value="{{ $startDate }}">
        <input type="hidden" name="end_date" value="{{ $endDate }}">
        <select name="format" class="px-3 py-2 border border-gray-300 rounded-lg text-sm">
            <option value="csv">Export as CSV</option>
            <option value="pdf">Export as PDF</option>
        </select>
        <button type="submit" class="px-4 py-2 rounded-lg text-sm font-semibold text-white bg-teal-800 hover:bg-teal-900">Export</button>
    </form>
@endsection

@section('content')
    <div class="space-y-6">
        <!-- Summary Stats -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
                <p class="text-xs text-gray-600 font-semibold">Total Applications</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ $funnel['total'] }}</p>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
                <p class="text-xs text-gray-600 font-semibold">Sieving Passed</p>
                <p class="text-2xl font-bold text-blue-600 mt-1">{{ $funnel['sieving_passed'] }}</p>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
                <p class="text-xs text-gray-600 font-semibold">Aptitude Passed</p>
                <p class="text-2xl font-bold text-purple-600 mt-1">{{ $funnel['aptitude_passed'] }}</p>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
                <p class="text-xs text-gray-600 font-semibold">Interview Passed</p>
                <p class="text-2xl font-bold text-orange-600 mt-1">{{ $funnel['interview_passed'] }}</p>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
                <p class="text-xs text-gray-600 font-semibold">Hired</p>
                <p class="text-2xl font-bold text-green-600 mt-1">{{ $funnel['hired'] }}</p>
            </div>
        </div>

        <!-- Funnel Chart -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-6">Application Funnel</h3>
            <div class="space-y-4">
                @php $total = $funnel['total'] ?: 1; @endphp
                @foreach(['submitted' => 'Submitted', 'sieving_passed' => 'Sieving', 'aptitude_passed' => 'Aptitude', 'interview_passed' => 'Interview', 'hired' => 'Hired'] as $key => $label)
                    @php $count = $funnel[$key] ?? 0; $percent = ($count / $total) * 100; @endphp
                    <div>
                        <div class="flex justify-between mb-1">
                            <span class="text-sm font-medium">{{ $label }}: {{ $count }} ({{ round($percent) }}%)</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            <div class="bg-gradient-to-r from-teal-400 to-teal-600 h-3 rounded-full" style="width: {{ $percent }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- By Job Position -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Performance by Job Position</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-200 bg-gray-50">
                            <th class="text-left py-3 px-4 font-semibold text-gray-700">Job Title</th>
                            <th class="text-right py-3 px-4 font-semibold text-gray-700">Applications</th>
                            <th class="text-right py-3 px-4 font-semibold text-gray-700">Hired</th>
                            <th class="text-right py-3 px-4 font-semibold text-gray-700">Conversion %</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($byJob as $job)
                            <tr class="border-b border-gray-100 hover:bg-gray-50">
                                <td class="py-3 px-4 text-gray-900 font-medium">{{ $job['job'] }}</td>
                                <td class="text-right py-3 px-4 text-gray-600">{{ $job['count'] }}</td>
                                <td class="text-right py-3 px-4 text-green-600 font-semibold">{{ $job['hired'] }}</td>
                                <td class="text-right py-3 px-4">
                                    @php $rate = $job['count'] > 0 ? round(($job['hired'] / $job['count']) * 100) : 0; @endphp
                                    <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">{{ $rate }}%</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Applications Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">All Applications</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-200 bg-gray-50">
                            <th class="text-left py-3 px-4 font-semibold text-gray-700">Name</th>
                            <th class="text-left py-3 px-4 font-semibold text-gray-700">Job Position</th>
                            <th class="text-left py-3 px-4 font-semibold text-gray-700">Status</th>
                            <th class="text-left py-3 px-4 font-semibold text-gray-700">Applied</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($applications->take(100) as $app)
                            <tr class="border-b border-gray-100 hover:bg-gray-50">
                                <td class="py-3 px-4 text-gray-900 font-medium">{{ $app->full_name }}</td>
                                <td class="py-3 px-4 text-gray-600">{{ $app->jobPost->title ?? 'N/A' }}</td>
                                <td class="py-3 px-4">
                                    <span class="inline-flex px-2 py-1 rounded text-xs font-semibold bg-blue-100 text-blue-700">
                                        {{ ucfirst(str_replace('_', ' ', $app->status)) }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-gray-600">{{ $app->created_at->format('Y-m-d') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-8 text-center text-gray-500">No applications found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
