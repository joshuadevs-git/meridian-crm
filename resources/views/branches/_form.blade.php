<div class="mb-4">
    <label class="block mb-2 font-medium">Branch Name</label>

    <input
        type="text"
        name="name"
        value="{{ old('name', $branch->name ?? '') }}"
        class="w-full border rounded-lg p-3">

    @error('name')
        <p class="text-red-500 text-sm">{{ $message }}</p>
    @enderror
</div>

<div class="mb-4">
    <label class="block mb-2 font-medium">Branch Code</label>

    <input
        type="text"
        name="code"
        value="{{ old('code', $branch->code ?? '') }}"
        class="w-full border rounded-lg p-3">

    @error('code')
        <p class="text-red-500 text-sm">{{ $message }}</p>
    @enderror
</div>

<div class="mb-4">
    <label class="block mb-2 font-medium">Address</label>

    <textarea
        name="address"
        class="w-full border rounded-lg p-3">{{ old('address', $branch->address ?? '') }}</textarea>
</div>

<div class="mb-4">
    <label class="block mb-2 font-medium">Phone</label>

    <input
        type="text"
        name="phone"
        value="{{ old('phone', $branch->phone ?? '') }}"
        class="w-full border rounded-lg p-3">
</div>

<div class="mb-6">
    <label class="block mb-2 font-medium">Email</label>

    <input
        type="email"
        name="email"
        value="{{ old('email', $branch->email ?? '') }}"
        class="w-full border rounded-lg p-3">
</div>

<div class="mb-6">
    <label class="inline-flex items-center">

        <input
            type="checkbox"
            name="is_active"
            value="1"
            {{ old('is_active', $branch->is_active ?? true) ? 'checked' : '' }}
            class="mr-2">

        Active Branch

    </label>
</div>

<button
    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg">

    {{ $button }}

</button>