@php use Illuminate\Support\Str; @endphp
@extends('layouts.admin')

@section('title', 'Activity Log Details')

@section('header-description', 'View detailed information about this activity log entry.')

@section('header-actions')
    <a href="{{ route('admin.activity-logs.index') }}" class="inline-flex items-center gap-2 px-4 py-2 border border-slate-200 rounded-xl text-sm font-semibold text-slate-600 hover:bg-slate-50">
        ← Back to Logs
    </a>
@endsection

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Main Details -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- User Information -->
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-2">User</h3>
                    @if($activityLog->user)
                        <div class="space-y-1">
                            <p class="font-semibold text-gray-900">{{ $activityLog->user->name }}</p>
                            <p class="text-sm text-gray-600">{{ $activityLog->user->email }}</p>
                        </div>
                    @else
                        <p class="text-gray-400 italic">System / Guest</p>
                    @endif
                </div>

                <!-- Action -->
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-2">Action</h3>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold
                        @class([
                            'bg-green-100 text-green-800' => in_array($activityLog->action, ['login', 'create']),
                            'bg-blue-100 text-blue-800' => in_array($activityLog->action, ['update', 'view']),
                            'bg-red-100 text-red-800' => in_array($activityLog->action, ['delete', 'logout', 'login_failed']),
                            'bg-amber-100 text-amber-800' => !in_array($activityLog->action, ['login', 'create', 'update', 'view', 'delete', 'logout', 'login_failed']),
                        ])">
                        {{ Str::headline($activityLog->action) }}
                    </span>
                </div>

                <!-- Date & Time -->
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-2">Date & Time</h3>
                    <p class="font-semibold text-gray-900">{{ $activityLog->created_at->format('F d, Y g:i A') }}</p>
                    <p class="text-sm text-gray-500">{{ $activityLog->created_at->diffForHumans() }}</p>
                </div>

                <!-- IP Address -->
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-2">IP Address</h3>
                    <p class="font-mono text-sm text-gray-900">{{ $activityLog->ip_address ?? '—' }}</p>
                </div>

                <!-- Route -->
                @if($activityLog->route)
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-2">Route</h3>
                    <p class="font-mono text-sm text-gray-900">{{ $activityLog->route }}</p>
                </div>
                @endif

                <!-- Model Type -->
                @if($activityLog->model_type)
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-2">Related Model</h3>
                    <p class="text-sm text-gray-900">{{ class_basename($activityLog->model_type) }}</p>
                    @if($activityLog->model_id)
                        <p class="text-xs text-gray-500">ID: {{ $activityLog->model_id }}</p>
                    @endif
                </div>
                @endif
            </div>

            <!-- Description -->
            <div>
                <h3 class="text-sm font-medium text-gray-500 mb-2">Description</h3>
                <p class="text-gray-900 whitespace-pre-line">{{ $activityLog->description }}</p>
            </div>

            <!-- User Agent -->
            @if($activityLog->user_agent)
            <div>
                <h3 class="text-sm font-medium text-gray-500 mb-2">User Agent</h3>
                <p class="text-sm text-gray-900 break-words">{{ $activityLog->user_agent }}</p>
            </div>
            @endif

            <!-- Metadata -->
            @if($activityLog->metadata && count($activityLog->metadata) > 0)
            <div>
                <h3 class="text-sm font-medium text-gray-500 mb-2">Additional Information</h3>
                <div class="bg-gray-50 rounded-xl border border-gray-200 p-4">
                    <pre class="text-xs text-gray-800 whitespace-pre-wrap font-mono overflow-x-auto">{{ json_encode($activityLog->metadata, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                </div>
            </div>
            @endif
        </div>
    </div>
@endsection


