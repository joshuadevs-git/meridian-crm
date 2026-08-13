@extends('layouts.crm')

@section('title', '')

@section('content')

<div class="bg-white rounded-2xl shadow-sm border border-gray-100">

    <div class="flex justify-between items-center p-6 border-b border-gray-100">

        <div>
            <h2 class="text-xl font-bold text-gray-800">Branch Management</h2>
            <p class="text-sm text-gray-400 mt-1">Manage all your company branches</p>
        </div>

        <a href="{{ route('branches.create') }}"
           class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-small px-4 py-2.5 rounded-xl shadow-sm transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Add Branch
        </a>

    </div>

    <div class="overflow-x-auto">

        <table class="min-w-full text-sm">

            <thead class="bg-gray-50 text-gray-400 uppercase text-xs">
                <tr>
                    <th class="text-left px-6 py-3">Code</th>
                    <th class="text-left px-6 py-3">Name</th>
                    <th class="text-left px-6 py-3">Status</th>
                    <th class="text-right px-6 py-3">Actions</th>
                </tr>
            </thead>

            <tbody>

            @forelse($branches as $branch)

                <tr class="border-b border-gray-50 hover:bg-gray-50">

                    <td class="px-6 py-4 text-gray-600 font-mono text-xs">{{ $branch->code }}</td>
                    <td class="px-6 py-4 font-medium text-gray-800">{{ $branch->name }}</td>

                    <td class="px-6 py-4">
                        @if($branch->is_active)
                            <span class="px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-600 text-xs font-medium">Active</span>
                        @else
                            <span class="px-2.5 py-1 rounded-full bg-red-50 text-red-500 text-xs font-medium">Inactive</span>
                        @endif
                    </td>

                    <td class="px-6 py-4">
                        <div class="flex justify-end gap-2">

                            <a href="{{ route('branches.edit', $branch) }}"
                               class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-50 hover:bg-gray-100 text-gray-600 text-xs font-medium rounded-lg transition">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
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
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-600 text-xs font-medium rounded-lg transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Delete
                                </button>

                            </form>

                        </div>
                    </td>

                </tr>

            @empty

                <tr>
                    <td colspan="5" class="text-center py-12 text-gray-400">
                        No branches found.
                    </td>
                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

    @if(method_exists($branches, 'links'))
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $branches->links() }}
        </div>
    @endif

</div>

@endsection