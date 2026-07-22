@extends('layouts.crm')

@section('title', '')

@section('content')

<div class="max-w-2xl mx-auto">

    <div class="flex items-center gap-3 mb-4">
        <a href="{{ route('branches.index') }}"
           class="w-8 h-8 flex items-center justify-center rounded-lg bg-white border border-gray-100 shadow-sm text-gray-500 hover:bg-gray-50 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h2 class="text-lg font-bold text-gray-800">Add New Branch</h2>
            <p class="text-xs text-gray-400 mt-0.5">Create a new branch location</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">

        {{--
            Landscape layout: wrap your input groups inside _form.blade.php in a
            grid so fields sit side-by-side instead of stacking, e.g.:

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Branch Code</label>
                    <input class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-emerald-200">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Branch Name</label>
                    <input class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-emerald-200">
                </div>
            </div>

            Use a full-width div (no grid, or col-span-2) for fields like
            "Address" that should span the whole row.
        --}}

        <form action="{{ route('branches.store') }}" method="POST" class="space-y-4">

            @csrf

            @php
                $button = 'Save Branch';
            @endphp

            @include('branches._form')

        </form>

    </div>

</div>

@endsection