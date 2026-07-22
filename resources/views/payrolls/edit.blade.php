@extends('layouts.crm')

@section('content')

<div class="p-6">

    <h2 class="text-2xl font-bold mb-6">
        Edit Payroll
    </h2>

    <form
        action="{{ route('payrolls.update', $payroll) }}"
        method="POST">

        @csrf
        @method('PUT')

        @include('payrolls._form')

    </form>

</div>

@endsection