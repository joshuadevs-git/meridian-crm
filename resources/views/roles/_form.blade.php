@csrf

@if ($errors->any())
    <div class="mb-2 rounded-lg bg-red-50 border border-red-200 p-4">
        <ul class="list-disc ml-5 text-red-600 text-sm space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="flex items-start gap-4">
    <label class="w-32 shrink-0 pt-2.5 text-sm font-medium text-gray-600">Role Name</label>

    <div class="flex-1">
        <input
            type="text"
            name="name"
            value="{{ old('name', $role->name ?? '') }}"
            class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200"
            required>

        @error('name')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
</div>

<div class="mt-6 pt-6 border-t border-gray-100 flex gap-3">

    <button
        type="submit"
        class="bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium px-6 py-2.5 rounded-xl shadow-sm transition">
        {{ isset($role) ? 'Update Role' : 'Save Role' }}
    </button>

    <a href="{{ route('roles.index') }}"
       class="bg-white border border-gray-200 hover:bg-gray-50 text-gray-600 text-sm font-medium px-6 py-2.5 rounded-xl transition">
        Cancel
    </a>

</div>