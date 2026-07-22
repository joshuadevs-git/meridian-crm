@extends('layouts.crm')

@section('title', '')

@section('content')

<div class="max-w-3xl mx-auto bg-white rounded-xl shadow p-8">

    <h2 class="text-2xl font-bold mb-6">
        Edit Branch
    </h2>

    <form action="{{ route('branches.update', $branch) }}" method="POST">

        @csrf
        @method('PUT')

        @php
            $button = 'Update Branch';
        @endphp

        @include('branches._form')

    </form>

</div>

@endsection