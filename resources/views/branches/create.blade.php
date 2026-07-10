@extends('layouts.crm')

@section('title', 'Create Branch')

@section('content')

<div class="max-w-3xl mx-auto bg-white rounded-xl shadow p-8">

    <h2 class="text-2xl font-bold mb-6">
        Add New Branch
    </h2>

    <form action="{{ route('branches.store') }}" method="POST">

        @csrf

        @php
            $button = 'Save Branch';
        @endphp

        @include('branches._form')

    </form>

</div>

@endsection