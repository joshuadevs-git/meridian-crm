@extends('layouts.crm')

@section('title', '')

@section('content')

<div class="max-w-5xl">

    <div class="flex items-center gap-3 mb-6">

        <a href="{{ route('employees.index') }}"
           class="w-9 h-9 flex items-center justify-center rounded-xl bg-white border border-gray-100 shadow-sm text-gray-500 hover:bg-gray-50 transition">

            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M15 19l-7-7 7-7"/>
            </svg>

        </a>

        <div>

            <h2 class="text-xl font-bold text-gray-800">
                Add Employee
            </h2>

            <p class="text-sm text-gray-400 mt-0.5">
                Create a new employee record
            </p>

        </div>

    </div>


    {{-- Validation Errors --}}

    @if ($errors->any())

        <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-5">

            <div class="flex items-start gap-3">

                <div class="flex-shrink-0">

                    <svg class="w-5 h-5 text-red-600"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>

                    </svg>

                </div>

                <div>

                    <h3 class="text-sm font-semibold text-red-800">
                        Please fix the following errors:
                    </h3>

                    <ul class="mt-2 text-sm text-red-700 list-disc list-inside space-y-1">

                        @foreach ($errors->all() as $error)

                            <li>
                                {{ $error }}
                            </li>

                        @endforeach

                    </ul>

                </div>

            </div>

        </div>

    @endif


    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">

        <form
            action="{{ route('employees.store') }}"
            method="POST">

            @csrf

            @include('employees._form')


            <div class="mt-8 flex gap-3 pt-6 border-t border-gray-100">

                <button
                    type="submit"
                    class="bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium px-6 py-2.5 rounded-xl shadow-sm transition">

                    Save Employee

                </button>


                <a
                    href="{{ route('employees.index') }}"
                    class="bg-white border border-gray-200 hover:bg-gray-50 text-gray-600 text-sm font-medium px-6 py-2.5 rounded-xl transition">

                    Cancel

                </a>

            </div>

        </form>

    </div>

</div>

@endsection