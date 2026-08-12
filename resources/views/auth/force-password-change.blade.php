@extends('layouts.crm')

@section('title', 'Change Password')

@section('content')

<div class="max-w-xl mx-auto">

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8">

        <h1 class="text-2xl font-bold text-gray-800">
            Change Your Password
        </h1>

        <p class="text-sm text-gray-500 mt-2">
            You are using a temporary password. Please create a new password before continuing.
        </p>

        @if($errors->any())
            <div class="mt-5 rounded-lg bg-red-50 border border-red-200 p-4">
                <ul class="list-disc ml-5 text-red-600 text-sm">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('password.force.update') }}" class="mt-6 space-y-5">

            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    New Password
                </label>

                <input
                    type="password"
                    name="password"
                    required
                    class="w-full rounded-lg border border-gray-200 px-3 py-2"
                >
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Confirm New Password
                </label>

                <input
                    type="password"
                    name="password_confirmation"
                    required
                    class="w-full rounded-lg border border-gray-200 px-3 py-2"
                >
            </div>

            <button
                type="submit"
                class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-medium py-2.5 rounded-xl"
            >
                Change Password
            </button>

        </form>

    </div>

</div>

@endsection