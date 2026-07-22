@extends('layouts.crm')

@section('title', 'Leave Request')

@section('content')

<div class="max-w-4xl mx-auto py-8">

    {{-- Page Header --}}
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <div class="w-11 h-11 rounded-xl bg-green-50 flex items-center justify-center">
                <svg class="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
            <div>
                <h1 class="text-xl font-semibold text-gray-900">
                    {{ isset($leave) ? 'Edit Leave Request' : 'New Leave Request' }}
                </h1>
                <p class="text-sm text-gray-500 mt-0.5">Fill in the details below to submit a leave request.</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8">

        <div class="flex items-center justify-between mb-6 pb-6 border-b border-gray-100">
            <p class="text-sm font-medium text-gray-700">Request Details</p>

            @php
                $currentStatus = old('status', $leave->status ?? 'Pending');
                $statusStyles = [
                    'Pending'  => 'text-amber-700 bg-amber-50',
                    'Approved' => 'text-green-700 bg-green-50',
                    'Rejected' => 'text-red-700 bg-red-50',
                ];
                $statusDot = [
                    'Pending'  => 'bg-amber-500',
                    'Approved' => 'bg-green-500',
                    'Rejected' => 'bg-red-500',
                ];
                $statusClass = $statusStyles[$currentStatus] ?? 'text-gray-700 bg-gray-50';
                $dotClass = $statusDot[$currentStatus] ?? 'bg-gray-400';
            @endphp

            <span class="inline-flex items-center gap-1.5 text-xs font-medium {{ $statusClass }} px-3 py-1.5 rounded-full">
                <span class="w-1.5 h-1.5 rounded-full {{ $dotClass }}"></span>
                {{ $currentStatus }}
            </span>
        </div>

            <div class="grid md:grid-cols-2 gap-5">

                {{-- Employee --}}
                <div class="md:col-span-2">
                    <label class="flex items-center gap-1.5 mb-2 font-medium text-sm text-gray-700">
                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Employee
                    </label>
                    <select
                        name="employee_id"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-700 bg-gray-50/50 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent focus:bg-white transition"
                        required>

                        <option value="">Select Employee</option>

                        @foreach($employees as $employee)
                            <option
                                  value="{{ $employee->id }}"
            {{ old('employee_id', $leave->employee_id ?? '') == $employee->id ? 'selected' : '' }}
                                {{ $employee->employee_no }} - {{ $employee->first_name }} {{ $employee->last_name }}
                            </option>
                        @endforeach

                    </select>
                    @error('employee_id')
                        <p class="text-xs text-red-500 mt-1.5 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Leave Type --}}
                <div>
                    <label class="flex items-center gap-1.5 mb-2 font-medium text-sm text-gray-700">
                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Leave Type
                    </label>
                    <select
                        name="leave_type"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-700 bg-gray-50/50 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent focus:bg-white transition">

                        @foreach([
                            'Vacation Leave',
                            'Sick Leave',
                            'Emergency Leave',
                            'Maternity Leave',
                            'Paternity Leave',
                            'Others'
                        ] as $type)
                            <option
                                value="{{ $type }}"
                    @selected(old('leave_type', $leave->leave_type ?? '') == $type)>
                                {{ $type }}
                            </option>
                        @endforeach

                    </select>
                </div>

                {{-- Status --}}
                <div>
                    <label class="flex items-center gap-1.5 mb-2 font-medium text-sm text-gray-700">
                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Status
                    </label>
                    <select
                        name="status"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-700 bg-gray-50/50 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent focus:bg-white transition">

                        @foreach(['Pending', 'Approved', 'Rejected'] as $status)
                            <option
                                value="{{ $status }}"
                    @selected(old('status', $leave->status ?? 'Pending') == $status)>
                                {{ $status }}
                            </option>
                        @endforeach

                    </select>
                </div>

                {{-- Start Date --}}
                <div>
                    <label class="flex items-center gap-1.5 mb-2 font-medium text-sm text-gray-700">
                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Start Date
                    </label>
                    <input
                        type="date"
                        name="start_date"
                        value="{{ old('start_date', optional($leave->start_date)->format('Y-m-d')) }}"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-700 bg-gray-50/50 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent focus:bg-white transition"
                        required>
                    @error('start_date')
                        <p class="text-xs text-red-500 mt-1.5 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- End Date --}}
                <div>
                    <label class="flex items-center gap-1.5 mb-2 font-medium text-sm text-gray-700">
                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        End Date
                    </label>
                    <input
                        type="date"
                        name="end_date"
                        value="{{ old('end_date', optional($leave->end_date)->format('Y-m-d')) }}"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-700 bg-gray-50/50 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent focus:bg-white transition"
                        required>
                    @error('end_date')
                        <p class="text-xs text-red-500 mt-1.5 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Reason --}}
                <div class="md:col-span-2">
                    <label class="flex items-center gap-1.5 mb-2 font-medium text-sm text-gray-700">
                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Reason
                    </label>
                    <textarea
                        name="reason"
                        rows="3"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-700 bg-gray-50/50 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent focus:bg-white transition resize-none"
                        placeholder="Briefly describe the reason for leave...">{{ old('reason', $leave->reason ?? '') }}</textarea>
                </div>

            </div>

            <div class="flex items-center justify-end gap-3 mt-8 pt-6 border-t border-gray-100">
                <a href="{{ route('leaves.index') }}"
                   class="px-4 py-2.5 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-50 transition">
                    Cancel
                </a>
                <button type="submit"
                    class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 transition shadow-sm">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                    {{ isset($leave) ? 'Update Request' : 'Submit Request' }}
                </button>
            </div>

        </form>

    </div>

</div>

