@extends('layouts.crm')

@section('title', 'Employee Management')

@section('content')

<div class="bg-white rounded-xl shadow p-6">

    <div class="flex justify-between items-center mb-6">

        <h2 class="text-2xl font-bold">
            Employee Management
        </h2>

        <div class="flex gap-2">

    <a href="{{ route('employees.export') }}"
       class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
        Export Excel
    </a>

    <form action="{{ route('employees.import') }}"
      method="POST"
      enctype="multipart/form-data"
      class="flex items-center gap-2">

    @csrf

    <input
        type="file"
        name="file"
        accept=".xlsx,.xls,.csv"
        required
        class="border rounded px-2 py-1">

    <button
        class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg">

        Import

    </button>

</form>

    <a href="{{ route('employees.create') }}"
       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
        + Add Employee
    </a>
    

</div>

    </div>

    

   <form
    id="filterForm"
    method="GET"
    action="{{ route('employees.index') }}"
    class="mb-6">

    <div class="grid md:grid-cols-4 gap-3">

        <input
            id="searchInput"
            type="text"
            name="search"
            value="{{ $search }}"
            placeholder="Search employee..."
            class="rounded-lg border-gray-300">

        <select
            id="branchSelect"
            name="branch"
            class="rounded-lg border-gray-300">

            <option value="">All Branches</option>

            @foreach($branches as $item)

                <option
                    value="{{ $item->id }}"
                    {{ $branch == $item->id ? 'selected' : '' }}>

                    {{ $item->name }}

                </option>

            @endforeach

        </select>

        <select
            id="statusSelect"
            name="status"
            class="rounded-lg border-gray-300">

            <option value="">All Status</option>

            <option value="1" {{ $status === '1' ? 'selected' : '' }}>
                Active
            </option>

            <option value="0" {{ $status === '0' ? 'selected' : '' }}>
                Inactive
            </option>

        </select>


        <select
    id="sortSelect"
    name="sort"
    class="rounded-lg border-gray-300">

    <option value="latest" {{ $sort == 'latest' ? 'selected' : '' }}>
        Newest First
    </option>

    <option value="oldest" {{ $sort == 'oldest' ? 'selected' : '' }}>
        Oldest First
    </option>

    <option value="name_asc" {{ $sort == 'name_asc' ? 'selected' : '' }}>
        Name (A–Z)
    </option>

    <option value="name_desc" {{ $sort == 'name_desc' ? 'selected' : '' }}>
        Name (Z–A)
    </option>

    <option value="salary_high" {{ $sort == 'salary_high' ? 'selected' : '' }}>
        Salary (Highest)
    </option>

    <option value="salary_low" {{ $sort == 'salary_low' ? 'selected' : '' }}>
        Salary (Lowest)
    </option>

    <option value="hire_new" {{ $sort == 'hire_new' ? 'selected' : '' }}>
        Hire Date (Newest)
    </option>

    <option value="hire_old" {{ $sort == 'hire_old' ? 'selected' : '' }}>
        Hire Date (Oldest)
    </option>

</select>

        <a
            href="{{ route('employees.index') }}"
            class="bg-gray-600 hover:bg-gray-700 text-white rounded-lg flex items-center justify-center">

            Clear Filters

        </a>

    </div>

</form>

    <table class="w-full">

        <thead>

            <tr class="border-b">

                <th class="text-left py-3">Employee No.</th>
                <th class="text-left py-3">Name</th>
                <th class="text-left py-3">Branch</th>
                <th class="text-left py-3">Department</th>
                <th class="text-left py-3">Status</th>
                <th class="text-left py-3">Actions</th>

            </tr>

        </thead>

        <tbody>

        @forelse($employees as $employee)

            <tr class="border-b">

                <td class="py-3">{{ $employee->employee_no }}</td>

                <td>
                    {{ $employee->first_name }} {{ $employee->last_name }}
                </td>

                <td>
                    {{ $employee->branch->name }}
                </td>

                <td>
                    {{ $employee->department }}
                </td>

                <td>

                    @if($employee->is_active)

                        <span class="text-green-600 font-semibold">
                            Active
                        </span>

                    @else

                        <span class="text-red-600 font-semibold">
                            Inactive
                        </span>

                    @endif

                </td>

                <td>

    <td class="space-x-2">

    <a href="{{ route('employees.edit', $employee) }}"
       class="inline-flex items-center px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">
        Edit
    </a>

    <form action="{{ route('employees.destroy', $employee) }}"
          method="POST"
          class="inline"
          onsubmit="return confirm('Are you sure you want to delete this employee?');">

        @csrf
        @method('DELETE')

        <button
            type="submit"
            class="inline-flex items-center px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700">

            Delete

        </button>

    </form>

</td>

</td>

            </tr>

        @empty

            <tr>

                <td colspan="5" class="text-center py-10 text-gray-500">
                    No employees found.
                </td>

            </tr>

        @endforelse

        </tbody>

    </table>

</div>


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const form = document.getElementById('filterForm');
    const search = document.getElementById('searchInput');
    const branch = document.getElementById('branchSelect');
    const status = document.getElementById('statusSelect');

    let timer;

    search.addEventListener('input', function () {
        clearTimeout(timer);

        timer = setTimeout(() => {
            form.submit();
        }, 400);
    });

    branch.addEventListener('change', function () {
        form.submit();
    });

    status.addEventListener('change', function () {
        form.submit();
    });

});

const sort = document.getElementById('sortSelect');

sort.addEventListener('change', function () {
    form.submit();
});
</script>
@endpush

@endsection