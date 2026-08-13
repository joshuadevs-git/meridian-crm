@extends('layouts.crm')

@section('title', '')

@section('content')


<div class="bg-white rounded-2xl shadow-sm border border-gray-100">

    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 p-6 border-b border-gray-100">

        <div>
            <h2 class="text-xl font-bold text-gray-800">Employee Management</h2>
            <p class="text-sm text-gray-400 mt-1">Manage employee records, roles and branches</p>
        </div>

        <div class="flex flex-wrap items-center gap-2">

            <a href="{{ route('employees.export') }}"
               class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-small px-4 py-2.5 rounded-xl transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3"/>
                </svg>
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
                    class="text-sm text-gray-500 rounded-xl border border-gray-200 px-3 py-2 file:mr-2 file:py-1 file:px-2 file:rounded-lg file:border-0 file:bg-gray-100 file:text-gray-600 file:text-xs">

                <button
                    type="submit"
                    class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-small px-4 py-2.5 rounded-xl transition shrink-0">
                    Import
                </button>
            </form>

            <a href="{{ route('employees.create') }}"
               class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-small px-4 py-2.5 rounded-xl shadow-sm transition shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add Employee
            </a>

        </div>

    </div>

    {{-- Filters --}}
    <div class="p-6 border-b border-gray-100 bg-gray-50/50">

        <form
            id="filterForm"
            method="GET"
            action="{{ route('employees.index') }}">

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">

                <input
                    id="searchInput"
                    type="text"
                    name="search"
                    value="{{ $search }}"
                    placeholder="Search employee..."
                    class="rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200">

                <select
                    id="branchSelect"
                    name="branch"
                    class="rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200">

                    <option value="">All Branches</option>

                    @foreach($branches as $item)
                        <option value="{{ $item->id }}" {{ $branch == $item->id ? 'selected' : '' }}>
                            {{ $item->name }}
                        </option>
                    @endforeach

                </select>

                <select
                    id="statusSelect"
                    name="status"
                    class="rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200">

                    <option value="">All Status</option>
                    <option value="1" {{ $status === '1' ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ $status === '0' ? 'selected' : '' }}>Inactive</option>

                </select>

                <select
                    id="sortSelect"
                    name="sort"
                    class="rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200">

                    <option value="latest" {{ $sort == 'latest' ? 'selected' : '' }}>Newest First</option>
                    <option value="oldest" {{ $sort == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                    <option value="name_asc" {{ $sort == 'name_asc' ? 'selected' : '' }}>Name (A–Z)</option>
                    <option value="name_desc" {{ $sort == 'name_desc' ? 'selected' : '' }}>Name (Z–A)</option>
                    <option value="salary_high" {{ $sort == 'salary_high' ? 'selected' : '' }}>Salary (Highest)</option>
                    <option value="salary_low" {{ $sort == 'salary_low' ? 'selected' : '' }}>Salary (Lowest)</option>
                    <option value="hire_new" {{ $sort == 'hire_new' ? 'selected' : '' }}>Hire Date (Newest)</option>
                    <option value="hire_old" {{ $sort == 'hire_old' ? 'selected' : '' }}>Hire Date (Oldest)</option>

                </select>

                <a
                    href="{{ route('employees.index') }}"
                    class="bg-emerald-600  hover:bg-emerald-700 text-white text-sm font-medium rounded-lg flex items-center justify-center transition">
                    Clear Filters
                </a>

            </div>

        </form>

    </div>

    <div class="overflow-x-auto">

        <table class="min-w-full text-sm">

            <thead class="bg-gray-50 text-gray-400 uppercase text-xs">
                <tr>
                    <th class="text-left px-6 py-3">Employee No.</th>
                    <th class="text-left px-6 py-3">Name</th>
                    <th class="text-left px-6 py-3">Branch</th>
                    <th class="text-left px-6 py-3">Department</th>
                    <th class="text-left px-6 py-3">Status</th>
                    <th class="text-right px-6 py-3">Actions</th>
                </tr>
            </thead>

            <tbody>

            @forelse($employees as $employee)

                <tr class="border-b border-gray-50 hover:bg-gray-50">

                    <td class="px-6 py-4 text-gray-600 font-mono text-xs">{{ $employee->employee_no }}</td>

                    <td class="px-6 py-4 font-medium text-gray-800">
                        {{ $employee->first_name }} {{ $employee->last_name }}
                    </td>

                    <td class="px-6 py-4 text-gray-600">
                        {{ $employee->branch->name }}
                    </td>

                    <td class="px-6 py-4 text-gray-600">
                        {{ $employee->department }}
                    </td>

                    <td class="px-6 py-4">
                        @if($employee->is_active)
                            <span class="px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-600 text-xs font-medium">Active</span>
                        @else
                            <span class="px-2.5 py-1 rounded-full bg-red-50 text-red-500 text-xs font-medium">Inactive</span>
                        @endif
                    </td>

                    <td class="px-6 py-4">
                        <div class="flex items-center justify-end gap-2">

                            <a href="{{ route('employees.edit', $employee) }}"
                               class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-50 hover:bg-gray-100 text-gray-600 text-xs font-medium rounded-lg transition">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
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
                    <td colspan="6" class="text-center py-12 text-gray-400">
                        No employees found.
                    </td>
                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

    @if(method_exists($employees, 'links'))
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $employees->links() }}
        </div>
    @endif

</div>


{{-- ========================================================= --}}
{{-- Temporary Password Modal --}}
{{-- ========================================================= --}}

@if(session()->has('temporary_password'))

    <div
        id="temporaryPasswordModal"
        class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/60 backdrop-blur-sm px-4"
    >

        <div
            class="w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden"
        >

            {{-- Header --}}
            <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100">

                <div>
                    <h2 class="text-lg font-bold text-gray-800">
                        Employee Account Created
                    </h2>

                    <p class="text-xs text-gray-400 mt-1">
                        Save the temporary password before closing.
                    </p>
                </div>

                <button
                    type="button"
                    onclick="closeTemporaryPasswordModal()"
                    class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-400 hover:bg-gray-100 hover:text-gray-600 transition"
                >
                    ✕
                </button>

            </div>


            {{-- Body --}}
            <div class="p-6 space-y-5">

                {{-- Success --}}
                <div class="bg-emerald-50 border border-emerald-100 rounded-xl p-4">

                    <p class="text-sm font-semibold text-emerald-700">
                        ✓ Login account successfully created.
                    </p>

                    <p class="text-xs text-emerald-600 mt-1">
                        The employee must change this password on first login.
                    </p>

                </div>


                {{-- Employee --}}
                <div>

                    <p class="text-xs uppercase tracking-wide text-gray-400">
                        Employee
                    </p>

                    <p class="text-sm font-semibold text-gray-800 mt-1">
                        {{ session('created_employee_name') }}
                    </p>

                </div>


                {{-- Email --}}
                <div>

                    <p class="text-xs uppercase tracking-wide text-gray-400">
                        Login Email
                    </p>

                    <p class="text-sm text-gray-700 mt-1">
                        {{ session('created_employee_email') }}
                    </p>

                </div>


                {{-- Temporary Password --}}
                <div>

                    <p class="text-xs uppercase tracking-wide text-gray-400 mb-2">
                        Temporary Password
                    </p>

                    <div class="flex items-center gap-2">

                        <div
                            id="temporaryPassword"
                            class="flex-1 bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 font-mono text-sm font-semibold text-gray-800 select-all"
                        >
                            {{ session('temporary_password') }}
                        </div>

                        <button
                            type="button"
                            id="copyPasswordButton"
                            onclick="copyTemporaryPassword()"
                            class="px-4 py-3 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-xl transition"
                        >
                            Copy
                        </button>

                    </div>

                    <p
                        id="copyMessage"
                        class="hidden text-xs text-emerald-600 mt-2"
                    >
                        ✓ Password copied to clipboard.
                    </p>

                </div>


                {{-- Warning --}}
                <div class="bg-amber-50 border border-amber-100 rounded-xl p-4">

                    <p class="text-xs text-amber-700 leading-relaxed">
                        <strong>Important:</strong>
                        This temporary password is displayed only once.
                        Copy it and securely provide it to the employee.
                    </p>

                </div>

            </div>


            {{-- Footer --}}
            <div class="flex justify-end gap-3 px-6 py-4 bg-gray-50 border-t border-gray-100">

                <button
                    type="button"
                    onclick="closeTemporaryPasswordModal()"
                    class="px-5 py-2.5 bg-white border border-gray-200 hover:bg-gray-100 text-gray-600 text-sm font-medium rounded-xl transition"
                >
                    Close
                </button>

            </div>

        </div>

    </div>


    <script>

        function copyTemporaryPassword() {

            const passwordElement =
                document.getElementById('temporaryPassword');

            const button =
                document.getElementById('copyPasswordButton');

            const message =
                document.getElementById('copyMessage');

            if (!passwordElement) {
                return;
            }

            const password =
                passwordElement.innerText.trim();

            navigator.clipboard.writeText(password)
                .then(function () {

                    button.innerText = 'Copied!';

                    message.classList.remove('hidden');

                    setTimeout(function () {

                        button.innerText = 'Copy';

                        message.classList.add('hidden');

                    }, 2000);

                })
                .catch(function () {

                    // Fallback for browsers where clipboard API is blocked
                    const range = document.createRange();

                    range.selectNodeContents(passwordElement);

                    const selection = window.getSelection();

                    selection.removeAllRanges();

                    selection.addRange(range);

                    try {
                        document.execCommand('copy');

                        button.innerText = 'Copied!';

                        message.classList.remove('hidden');

                    } catch (error) {

                        alert('Please copy the password manually.');

                    }

                    selection.removeAllRanges();

                });
        }


        function closeTemporaryPasswordModal() {

            const modal =
                document.getElementById('temporaryPasswordModal');

            if (modal) {

                modal.remove();

            }

        }


        // Close when clicking outside the modal
        document
            .getElementById('temporaryPasswordModal')
            ?.addEventListener('click', function(event) {

                if (event.target === this) {

                    closeTemporaryPasswordModal();

                }

            });


        // Allow ESC key to close
        document.addEventListener('keydown', function(event) {

            if (event.key === 'Escape') {

                closeTemporaryPasswordModal();

            }

        });

    </script>

@endif

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const form = document.getElementById('filterForm');
    const search = document.getElementById('searchInput');
    const branch = document.getElementById('branchSelect');
    const status = document.getElementById('statusSelect');
    const sort = document.getElementById('sortSelect');

    let timer;

    search.addEventListener('input', function () {
        clearTimeout(timer);
        timer = setTimeout(() => form.submit(), 400);
    });

    branch.addEventListener('change', function () {
        form.submit();
    });

    status.addEventListener('change', function () {
        form.submit();
    });

    sort.addEventListener('change', function () {
        form.submit();
    });

});
</script>
@endpush




