@extends('layouts.crm')

@section('title', 'Add Employee')

@section('content')

<div class="bg-white rounded-xl shadow p-6 max-w-5xl">

    <h2 class="text-2xl font-bold mb-6">
        Add Employee
    </h2>

    <form action="{{ route('employees.store') }}" method="POST">

        @csrf

        @include('employees._form')

        <div class="mt-6 flex gap-3">

            <button
                type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg">

                Save Employee

            </button>

            <a href="{{ route('employees.index') }}"
               class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded-lg">

                Cancel

            </a>

        </div>

    </form>

</div>

@endsection