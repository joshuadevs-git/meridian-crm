@extends('layouts.crm')

@section('title', 'Dashboard')

@section('content')

<div class="grid grid-cols-4 gap-6">

    <div class="bg-white rounded-xl shadow p-6">
        <h2 class="text-gray-500">Branches</h2>

        <p class="text-4xl font-bold mt-3">
            0
        </p>
    </div>

    <div class="bg-white rounded-xl shadow p-6">
        <h2 class="text-gray-500">Customers</h2>

        <p class="text-4xl font-bold mt-3">
            0
        </p>
    </div>

    <div class="bg-white rounded-xl shadow p-6">
        <h2 class="text-gray-500">Leads</h2>

        <p class="text-4xl font-bold mt-3">
            0
        </p>
    </div>

    <div class="bg-white rounded-xl shadow p-6">
        <h2 class="text-gray-500">Deals</h2>

        <p class="text-4xl font-bold mt-3">
            0
        </p>
    </div>

</div>

@endsection