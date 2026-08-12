<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meridian CRM</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 text-gray-800">

<div class="flex min-h-screen">

    {{-- Sidebar --}}
    <aside class="w-64 bg-white border-r border-gray-100 flex flex-col">

        {{-- Logo --}}
        <div class="flex items-center gap-2 p-6">
            <div class="w-9 h-9 rounded-xl bg-emerald-600 flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
            <span class="text-lg font-bold text-gray-800">Meridian</span>
        </div>

        @php
            $navClass = fn ($routePattern) => 'flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm transition '
                . (request()->routeIs($routePattern)
                    ? 'bg-emerald-50 text-emerald-700 font-medium'
                    : 'text-gray-500 hover:bg-gray-50 hover:text-gray-800');
        @endphp

        <nav class="flex-1 px-4 pb-6 space-y-1 overflow-y-auto">

            <p class="px-3 pt-2 pb-1 text-[11px] font-semibold uppercase tracking-wider text-gray-400">Menu</p>

            <a href="{{ route('dashboard') }}" class="{{ $navClass('dashboard') }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l9-9 9 9M5 10v10a1 1 0 001 1h4a1 1 0 001-1v-4a1 1 0 011-1h0a1 1 0 011 1v4a1 1 0 001 1h4a1 1 0 001-1V10"/>
                </svg>
                Dashboard
            </a>

            @if(auth()->user()->isAdmin() || auth()->user()->isHR())

                <p class="px-3 pt-5 pb-1 text-[11px] font-semibold uppercase tracking-wider text-gray-400">Management</p>

                <a href="{{ route('branches.index') }}" class="{{ $navClass('branches.*') }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21h18M5 21V7l8-4v18M13 21V11l6 3v7"/>
                    </svg>
                    Branches
                </a>

                <a href="{{ route('employees.index') }}" class="{{ $navClass('employees.*') }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-1.13a4 4 0 10-4-4 4 4 0 004 4zm6 0a4 4 0 10-4-4"/>
                    </svg>
                    Employees
                </a>

                <a href="{{ route('attendances.index') }}" class="{{ $navClass('attendances.*') }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Attendance
                </a>

                <a href="{{ route('leaves.index') }}" class="{{ $navClass('leaves.*') }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Leave Management
                </a>

                <a href="{{ route('payrolls.index') }}" class="{{ $navClass('payrolls.*') }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.66 0-3 .9-3 2s1.34 2 3 2 3 .9 3 2-1.34 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V6m0 10v-2m0-8a9 9 0 100 18 9 9 0 000-18z"/>
                    </svg>
                    Payroll Management
                </a>

                <p class="px-3 pt-5 pb-1 text-[11px] font-semibold uppercase tracking-wider text-gray-400">Reports</p>

                <a href="{{ route('reports.attendance') }}" class="{{ $navClass('reports.attendance') }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-6h6v6m-9 4h12a2 2 0 002-2V7l-5-5H6a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                    Attendance Report
                </a>

                <a href="{{ route('reports.leaves') }}" class="{{ $navClass('reports.leaves') }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-6h6v6m-9 4h12a2 2 0 002-2V7l-5-5H6a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                    Leave Report
                </a>

                <a href="{{ route('reports.payroll') }}" class="{{ $navClass('reports.payroll') }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-6h6v6m-9 4h12a2 2 0 002-2V7l-5-5H6a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                    Payroll Report
                </a>

            @endif

            @if(auth()->user()->isAdmin())

                <p class="px-3 pt-5 pb-1 text-[11px] font-semibold uppercase tracking-wider text-gray-400">Administration</p>

                <a href="{{ route('users.index') }}" class="{{ $navClass('users.*') }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Users
                </a>

                <a href="{{ route('roles.index') }}" class="{{ $navClass('roles.*') }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    Roles
                </a>

            @endif

            @if(auth()->user()->isEmployee())

                <p class="px-3 pt-5 pb-1 text-[11px] font-semibold uppercase tracking-wider text-gray-400">My Records</p>

                <a href="{{ route('my-attendance') }}" class="{{ $navClass('my-attendance') }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    My Attendance
                </a>

                <a href="{{ route('my-leaves') }}" class="{{ $navClass('my-leaves') }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    My Leaves
                </a>

                <a href="{{ route('my-payroll') }}" class="{{ $navClass('my-payroll') }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.66 0-3 .9-3 2s1.34 2 3 2 3 .9 3 2-1.34 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V6m0 10v-2m0-8a9 9 0 100 18 9 9 0 000-18z"/>
                    </svg>
                    My Payroll
                </a>

            @endif

        </nav>

        {{-- Account --}}
        <div class="px-4 py-3 border-t border-gray-100 space-y-1">

            <a href="{{ route('my-profile') }}"
   class="{{ $navClass('my-profile') }}">
    My Profile
</a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button
                    type="submit"
                    class="w-full flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm text-red-500 hover:bg-red-50 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Logout
                </button>
            </form>

        </div>

        {{-- Bottom promo card --}}
        <div class="p-4">
            <div class="bg-emerald-600 rounded-2xl p-4 text-white text-center">
                <p class="text-sm font-semibold">Need help?</p>
                <p class="text-xs text-emerald-100 mt-1 mb-3">Check our documentation or contact support.</p>
                <a href="#" class="block bg-white text-emerald-700 text-xs font-semibold rounded-lg py-2 hover:bg-emerald-50 transition">
                    Get Support
                </a>
            </div>
        </div>

    </aside>

    {{-- Main Content --}}
    <div class="flex-1 flex flex-col min-w-0">

        {{-- Navbar --}}
        <header class="bg-white border-b border-gray-100 px-8 py-4">

            <div class="flex justify-between items-center gap-4">

                <div>
                    <h1 class="text-xl font-bold text-gray-800">@yield('title')</h1>
                </div>

                <div class="flex-1 max-w-md hidden md:block">
                    <div class="relative">
                        <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z"/>
                        </svg>
                        <input type="text" placeholder="Search..."
                               class="w-full bg-gray-50 border border-gray-100 rounded-xl pl-9 pr-4 py-2 text-sm text-gray-600 focus:outline-none focus:ring-2 focus:ring-emerald-200">
                    </div>
                </div>

                <div class="flex items-center gap-4">

                    <button class="relative text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 10-12 0v3.2c0 .5-.2 1-.6 1.4L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        <span class="absolute -top-1 -right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                    </button>

                    <div class="flex items-center gap-2">
                        <div class="w-9 h-9 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-sm font-bold uppercase">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        <span class="text-sm font-medium text-gray-700 hidden sm:block">
                            {{ Auth::user()->name }}
                        </span>
                    </div>

                </div>

            </div>

        </header>

        {{-- Page Content --}}
        <main class="p-8 flex-1">

            @if(session('success'))
                <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm">
                    {{ session('success') }}
                </div>
            @endif

            @yield('content')

        </main>

    </div>

</div>

@stack('scripts')
</body>
</html>