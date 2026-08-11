@extends('layouts.crm')

@section('title', 'User Management')

@section('content')

<div class="space-y-6">

    <div class="flex items-center justify-between">

        <div>
            <h2 class="text-xl font-bold text-gray-800">
                User Management
            </h2>

            <p class="text-sm text-gray-400 mt-1">
                Manage system users and their assigned roles.
            </p>
        </div>

        <a href="{{ route('users.create') }}"
           class="bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-xl text-sm font-medium shadow-sm">
            + Add User
        </a>

    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

        <table class="w-full text-sm">

            <thead class="bg-gray-50 border-b border-gray-100">

                <tr>
                    <th class="text-left px-6 py-4 font-semibold text-gray-600">
                        ID
                    </th>

                    <th class="text-left px-6 py-4 font-semibold text-gray-600">
                        Name
                    </th>

                    <th class="text-left px-6 py-4 font-semibold text-gray-600">
                        Email
                    </th>

                    <th class="text-left px-6 py-4 font-semibold text-gray-600">
                        Role
                    </th>

                    <th class="text-right px-6 py-4 font-semibold text-gray-600">
                        Actions
                    </th>
                </tr>

            </thead>

            <tbody class="divide-y divide-gray-100">

                @forelse($users as $user)

                    <tr class="hover:bg-gray-50">

                        <td class="px-6 py-4 text-gray-500">
                            {{ $user->id }}
                        </td>

                        <td class="px-6 py-4 font-medium text-gray-800">
                            {{ $user->name }}
                        </td>

                        <td class="px-6 py-4 text-gray-500">
                            {{ $user->email }}
                        </td>

                        <td class="px-6 py-4">

                            @if($user->role)
                                <span class="inline-flex px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-medium">
                                    {{ $user->role->name }}
                                </span>
                            @else
                                <span class="text-red-500 text-xs">
                                    No Role
                                </span>
                            @endif

                        </td>

                        <td class="px-6 py-4 text-right">

                            <a href="{{ route('users.edit', $user) }}"
                               class="text-blue-600 hover:text-blue-800 text-xs font-medium mr-3">
                                Edit
                            </a>

                            <form
                                action="{{ route('users.destroy', $user) }}"
                                method="POST"
                                class="inline"
                                onsubmit="return confirm('Delete this user?');"
                            >

                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="text-red-600 hover:text-red-800 text-xs font-medium"
                                >
                                    Delete
                                </button>

                            </form>

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="5"
                            class="px-6 py-10 text-center text-gray-400">
                            No users found.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

        <div class="px-6 py-4 border-t border-gray-100">
            {{ $users->links() }}
        </div>

    </div>

</div>

@endsection