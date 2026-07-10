@extends('layouts.crm')

@section('title', 'Branch Management')

@section('content')

<div class="bg-white rounded-xl shadow p-6">

    <div class="flex justify-between items-center mb-6">

        <h2 class="text-2xl font-bold">
            Branch Management
        </h2>

        <a href="{{ route('branches.create') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
            + Add Branch
        </a>

    </div>

    <table class="w-full">

        <thead>
            <tr class="border-b">
                <th class="text-left py-3">ID</th>
                <th class="text-left py-3">Code</th>
                <th class="text-left py-3">Name</th>
                <th class="text-left py-3">Status</th>
                <th class="text-left py-3">Actions</th>
            </tr>
        </thead>

        <tbody>

        @forelse($branches as $branch)

            <tr class="border-b">

                <td class="py-3">{{ $branch->id }}</td>
                <td>{{ $branch->code }}</td>
                <td>{{ $branch->name }}</td>

                <td>
                    @if($branch->is_active)
                        <span class="text-green-600 font-semibold">Active</span>
                    @else
                        <span class="text-red-600 font-semibold">Inactive</span>
                    @endif
                </td>

                <td class="space-x-2">

    <a href="{{ route('branches.edit', $branch) }}"
       class="inline-flex items-center px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">
        Edit
    </a>

    <form action="{{ route('branches.destroy', $branch) }}"
          method="POST"
          class="inline"
          onsubmit="return confirm('Are you sure you want to delete this branch?');">

        @csrf
        @method('DELETE')

        <button
            type="submit"
            class="inline-flex items-center px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700">

            Delete

        </button>

    </form>

</td>

            </tr>

        @empty

            <tr>
                <td colspan="5" class="text-center py-10 text-gray-500">
                    No branches found.
                </td>
            </tr>

        @endforelse

        </tbody>

    </table>

</div>

@endsection