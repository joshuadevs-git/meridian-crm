@extends('layouts.crm')

@section('title', 'Edit Attendance')

@section('content')

<div class="bg-white rounded-xl shadow p-6">

    <h2 class="text-2xl font-bold mb-6">
        Edit Attendance
    </h2>

    <form action="{{ route('attendances.update', $attendance) }}" method="POST">
    @csrf
    @method('PUT')

    @include('attendances._form')
</form>

</div>

@endsection