@extends('layouts.crm')

@section('title', '')

@section('content')

<div class="max-w-3xl">

    <div class="flex items-center gap-3 mb-6">

        <a href="{{ route('users.index') }}"
           class="w-9 h-9 flex items-center justify-center rounded-xl bg-white border border-gray-100 shadow-sm text-gray-500 hover:bg-gray-50">
            ←
        </a>

        <div>
            <h2 class="text-xl font-bold text-gray-800">
                Add User
            </h2>

            <p class="text-sm text-gray-400">
                Create a new system user and assign a role.
            </p>
        </div>

    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">

        <form action="{{ route('users.store') }}" method="POST">

            @csrf

            @include('users._form')

        </form>

    </div>

</div>

@endsection