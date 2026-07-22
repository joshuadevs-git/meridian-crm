<div class="flex items-start gap-4">
    <label class="w-36 shrink-0 pt-2.5 text-sm font-medium text-gray-600">Branch Name</label>

    <div class="flex-1">
        <input
            type="text"
            name="name"
            value="{{ old('name', $branch->name ?? '') }}"
            class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200">

        @error('name')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
</div>

<div class="flex items-start gap-4">
    <label class="w-36 shrink-0 pt-2.5 text-sm font-medium text-gray-600">Branch Code</label>

    <div class="flex-1">
        <input
            type="text"
            name="code"
            value="{{ old('code', $branch->code ?? '') }}"
            class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200">

        @error('code')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
</div>

<div class="flex items-start gap-4">
    <label class="w-36 shrink-0 pt-2.5 text-sm font-medium text-gray-600">Address</label>

    <div class="flex-1">
        <textarea
            name="address"
            rows="2"
            class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200">{{ old('address', $branch->address ?? '') }}</textarea>

        @error('address')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
</div>

<div class="flex items-start gap-4">
    <label class="w-36 shrink-0 pt-2.5 text-sm font-medium text-gray-600">Phone</label>

    <div class="flex-1">
        <input
            type="text"
            name="phone"
            value="{{ old('phone', $branch->phone ?? '') }}"
            class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200">

        @error('phone')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
</div>

<div class="flex items-start gap-4">
    <label class="w-36 shrink-0 pt-2.5 text-sm font-medium text-gray-600">Email</label>

    <div class="flex-1">
        <input
            type="email"
            name="email"
            value="{{ old('email', $branch->email ?? '') }}"
            class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200">

        @error('email')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
</div>

<div class="flex items-center gap-4">
    <label class="w-36 shrink-0 text-sm font-medium text-gray-600">Status</label>

    <label class="flex-1 inline-flex items-center gap-2 text-sm text-gray-600">
        <input
            type="checkbox"
            name="is_active"
            value="1"
            {{ old('is_active', $branch->is_active ?? true) ? 'checked' : '' }}
            class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-200">
        Active Branch
    </label>
</div>

<div class="flex items-center gap-4 pt-2">
    <div class="w-36 shrink-0"></div>

    <button
        type="submit"
        class="bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium px-6 py-2.5 rounded-lg shadow-sm transition">
        {{ $button }}
    </button>
</div>