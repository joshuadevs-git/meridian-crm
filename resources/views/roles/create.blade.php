@extends('layouts.crm')

@section('title', 'Add Role')

@section('content')

<div class="max-w-3xl">

    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('roles.index') }}"
           class="w-9 h-9 flex items-center justify-center rounded-xl bg-white border border-gray-100 shadow-sm text-gray-500 hover:bg-gray-50 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h2 class="text-xl font-bold text-gray-800">Add Role</h2>
            <p class="text-sm text-gray-400 mt-0.5">Create a new role for your team</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">

        <form action="{{ route('roles.store') }}" method="POST">

            @include('roles._form')

        </form>

    </div>

</div>

@endsection