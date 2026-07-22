@extends('layouts.crm')

@section('title', '')

@section('content')

<div class="max-w-5xl">

    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('employees.index') }}"
           class="w-9 h-9 flex items-center justify-center rounded-xl bg-white border border-gray-100 shadow-sm text-gray-500 hover:bg-gray-50 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h2 class="text-xl font-bold text-gray-800">Add Employee</h2>
            <p class="text-sm text-gray-400 mt-0.5">Create a new employee record</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">

        <form action="{{ route('employees.store') }}" method="POST">

            @csrf

            @include('employees._form')

            <div class="mt-8 flex gap-3 pt-6 border-t border-gray-100">

                <button
                    type="submit"
                    class="bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium px-6 py-2.5 rounded-xl shadow-sm transition">
                    Save Employee
                </button>

                <a href="{{ route('employees.index') }}"
                   class="bg-white border border-gray-200 hover:bg-gray-50 text-gray-600 text-sm font-medium px-6 py-2.5 rounded-xl transition">
                    Cancel
                </a>

            </div>

        </form>

    </div>

</div>

@endsection