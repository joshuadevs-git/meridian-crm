@extends('layouts.crm')

@section('title', '')

@section('content')

<div class="max-w-4xl">

    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('leaves.index') }}"
           class="w-9 h-9 flex items-center justify-center rounded-xl bg-white border border-gray-100 shadow-sm text-gray-500 hover:bg-gray-50 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h2 class="text-xl font-bold text-gray-800">Add Leave Request</h2>
            <p class="text-sm text-gray-400 mt-0.5">Submit a new employee leave request</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">

        <form action="{{ route('leaves.store') }}" method="POST">

            @include('leaves._form')

        </form>

    </div>

</div>

@endsection