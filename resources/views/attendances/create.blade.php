@extends('layouts.crm')

@section('title', 'Add Attendance')

@section('content')

<div class="bg-white rounded-xl shadow p-6">

    <h2 class="text-2xl font-bold mb-6">
        Add Attendance
    </h2>

    <form action="{{ route('attendances.store') }}" method="POST">

        @include('attendances._form')

    </form>

</div>

@endsection