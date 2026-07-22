@extends('layouts.crm')

@section('title', 'Edit Leave Request')

@section('content')

<div class="max-w-4xl">

    <h2 class="text-xl font-bold mb-6">
        Edit Leave Request
    </h2>

    <form action="{{ route('leaves.update', $leave->id) }}" method="POST">

        @csrf
        @method('PUT')

        @include('leaves._form', ['leave' => $leave])

    </form>

</div>

@endsection