@if ($errors->any())
    <div class="mb-5 rounded-lg bg-red-50 border border-red-200 p-4">
        <ul class="list-disc ml-5 text-red-600 text-sm space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="space-y-5">

    {{-- Name --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">
            Name
        </label>

        <input
            type="text"
            name="name"
            value="{{ old('name', $user->name ?? '') }}"
            required
            class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200"
        >
    </div>

    {{-- Email --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">
            Email
        </label>

        <input
            type="email"
            name="email"
            value="{{ old('email', $user->email ?? '') }}"
            required
            class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200"
        >
    </div>

    {{-- Role --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">
            Role
        </label>

        <select
            name="role_id"
            id="role_id"
            required
            class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200"
        >
            <option value="">Select Role</option>

            @foreach($roles as $role)
                <option
                    value="{{ $role->id }}"
                    @selected(old('role_id', $user->role_id ?? '') == $role->id)
                >
                    {{ $role->name }}
                </option>
            @endforeach
        </select>
    </div>

</div>

<div class="mt-6 pt-6 border-t border-gray-100 flex gap-3">

    <button
        type="submit"
        class="bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium px-6 py-2.5 rounded-xl shadow-sm transition"
    >
        {{ isset($user) ? 'Update User' : 'Create User' }}
    </button>

    <a
        href="{{ route('users.index') }}"
        class="bg-white border border-gray-200 hover:bg-gray-50 text-gray-600 text-sm font-medium px-6 py-2.5 rounded-xl transition"
    >
        Cancel
    </a>

</div>