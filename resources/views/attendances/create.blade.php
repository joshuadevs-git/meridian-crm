@extends('layouts.crm')

@section('title', '')

@section('content')

<div class="max-w-4xl">

    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('attendances.index') }}"
           class="w-9 h-9 flex items-center justify-center rounded-xl bg-white border border-gray-100 shadow-sm text-gray-500 hover:bg-gray-50 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h2 class="text-xl font-bold text-gray-800">Add Attendance</h2>
            <p class="text-sm text-gray-400 mt-0.5">Record a new employee attendance entry</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">

        <form action="{{ route('attendances.store') }}" method="POST">

            @include('attendances._form')

        </form>

    </div>

</div>

@endsection