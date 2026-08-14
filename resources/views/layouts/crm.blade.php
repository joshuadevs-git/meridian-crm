<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meridian CRM</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 text-gray-800">

<div class="relative min-h-screen overflow-hidden">

    {{-- Global ambient background — sits behind the glass sidebar/navbar so the
         blur actually has something to refract, on every page, not just the dashboard. --}}
    <div class="pointer-events-none fixed -top-32 -left-20 w-[30rem] h-[30rem] bg-emerald-200/40 rounded-full blur-3xl"></div>
    <div class="pointer-events-none fixed top-1/3 -right-24 w-[26rem] h-[26rem] bg-emerald-300/30 rounded-full blur-3xl"></div>
    <div class="pointer-events-none fixed bottom-0 left-1/4 w-96 h-96 bg-teal-200/25 rounded-full blur-3xl"></div>

    <div class="relative flex min-h-screen gap-4 p-4">

        {{-- Sidebar — floating glass panel instead of a flush flat bar --}}
        <aside class="w-64 shrink-0 flex flex-col rounded-3xl overflow-hidden
                       bg-white/70 backdrop-blur-xl border border-white/60
                       shadow-[0_8px_32px_rgba(31,41,55,0.10)]">

            {{-- Logo --}}
            <div class="flex items-center gap-2 p-6">
                <div class="relative w-9 h-9 rounded-xl flex items-center justify-center
                            bg-gradient-to-br from-emerald-400 to-emerald-600
                            shadow-[0_4px_14px_rgba(5,150,105,0.45)]">
                    <div class="absolute inset-x-1 top-0.5 h-1/2 bg-white/30 rounded-t-lg pointer-events-none"></div>
                    <svg class="relative w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <span class="text-lg font-bold text-gray-800">Meridian</span>
            </div>

            @php
                $navClass = fn ($routePattern) => 'group flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm transition-all duration-200 '
                    . (request()->routeIs($routePattern)
                        ? 'bg-gradient-to-r from-emerald-500 to-emerald-600 text-white font-medium shadow-[0_4px_14px_rgba(5,150,105,0.35)]'
                        : 'text-gray-500 hover:bg-white hover:shadow-sm hover:text-gray-800');

                $navIconClass = fn ($routePattern) => 'w-5 h-5 transition-transform duration-200 '
                    . (request()->routeIs($routePattern) ? 'text-white' : 'text-gray-400 group-hover:text-emerald-600 group-hover:scale-110');
            @endphp

            <nav class="flex-1 px-4 pb-6 space-y-1 overflow-y-auto">

                <p class="px-3 pt-2 pb-1 text-[11px] font-semibold uppercase tracking-wider text-gray-400">Menu</p>

                <a href="{{ route('dashboard') }}" class="{{ $navClass('dashboard') }}">
                    <svg class="{{ $navIconClass('dashboard') }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l9-9 9 9M5 10v10a1 1 0 001 1h4a1 1 0 001-1v-4a1 1 0 011-1h0a1 1 0 011 1v4a1 1 0 001 1h4a1 1 0 001-1V10"/>
                    </svg>
                    Dashboard
                </a>

                @if(auth()->user()->isAdmin() || auth()->user()->isHR())

                    <p class="px-3 pt-5 pb-1 text-[11px] font-semibold uppercase tracking-wider text-gray-400">Management</p>

                    <a href="{{ route('branches.index') }}" class="{{ $navClass('branches.*') }}">
                        <svg class="{{ $navIconClass('branches.*') }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21h18M5 21V7l8-4v18M13 21V11l6 3v7"/>
                        </svg>
                        Branches
                    </a>

                    <a href="{{ route('employees.index') }}" class="{{ $navClass('employees.*') }}">
                        <svg class="{{ $navIconClass('employees.*') }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-1.13a4 4 0 10-4-4 4 4 0 004 4zm6 0a4 4 0 10-4-4"/>
                        </svg>
                        Employees
                    </a>

                    <a href="{{ route('attendances.index') }}" class="{{ $navClass('attendances.*') }}">
                        <svg class="{{ $navIconClass('attendances.*') }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Attendance
                    </a>

                    <a href="{{ route('schedules.index') }}" class="{{ $navClass('schedules.*') }}">
                        <svg class="{{ $navIconClass('schedules.*') }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Employee Schedule
                    </a>

                    <a href="{{ route('employee-monitor.index') }}" class="{{ $navClass('employee-monitor.*') }}">
                        <svg class="{{ $navIconClass('employee-monitor.*') }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6z"/>
                        </svg>
                        Live Employee Monitor
                    </a>

                    <a href="{{ route('leaves.index') }}" class="{{ $navClass('leaves.*') }}">
                        <svg class="{{ $navIconClass('leaves.*') }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Leave Management
                    </a>

                    <a href="{{ route('payrolls.index') }}" class="{{ $navClass('payrolls.*') }}">
                        <svg class="{{ $navIconClass('payrolls.*') }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.66 0-3 .9-3 2s1.34 2 3 2 3 .9 3 2-1.34 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V6m0 10v-2m0-8a9 9 0 100 18 9 9 0 000-18z"/>
                        </svg>
                        Payroll Management
                    </a>

                    <p class="px-3 pt-5 pb-1 text-[11px] font-semibold uppercase tracking-wider text-gray-400">Reports</p>

                    <a href="{{ route('reports.attendance') }}" class="{{ $navClass('reports.attendance') }}">
                        <svg class="{{ $navIconClass('reports.attendance') }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-6h6v6m-9 4h12a2 2 0 002-2V7l-5-5H6a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                        Attendance Report
                    </a>

                    <a href="{{ route('reports.leaves') }}" class="{{ $navClass('reports.leaves') }}">
                        <svg class="{{ $navIconClass('reports.leaves') }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-6h6v6m-9 4h12a2 2 0 002-2V7l-5-5H6a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                        Leave Report
                    </a>

                    <a href="{{ route('reports.payroll') }}" class="{{ $navClass('reports.payroll') }}">
                        <svg class="{{ $navIconClass('reports.payroll') }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-6h6v6m-9 4h12a2 2 0 002-2V7l-5-5H6a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                        Payroll Report
                    </a>

                @endif

                @if(auth()->user()->isAdmin())

                    <p class="px-3 pt-5 pb-1 text-[11px] font-semibold uppercase tracking-wider text-gray-400">Administration</p>

                    <a href="{{ route('users.index') }}" class="{{ $navClass('users.*') }}">
                        <svg class="{{ $navIconClass('users.*') }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Users
                    </a>

                    <a href="{{ route('roles.index') }}" class="{{ $navClass('roles.*') }}">
                        <svg class="{{ $navIconClass('roles.*') }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        Roles
                    </a>

                @endif

                @if(auth()->user()->isEmployee())

                    <p class="px-3 pt-5 pb-1 text-[11px] font-semibold uppercase tracking-wider text-gray-400">My Records</p>

                    <a href="{{ route('my-attendance') }}" class="{{ $navClass('my-attendance') }}">
                        <svg class="{{ $navIconClass('my-attendance') }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        My Attendance
                    </a>

                    <a href="{{ route('my-schedule') }}" class="{{ $navClass('my-schedule') }}">
                        <svg class="{{ $navIconClass('my-schedule') }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke="currentColor"
                                stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5v12a2 2 0 002 2z"/>
                        </svg>
                        My Schedule
                    </a>

                    <a href="{{ route('my-leaves') }}" class="{{ $navClass('my-leaves') }}">
                        <svg class="{{ $navIconClass('my-leaves') }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        My Leaves
                    </a>

                    <a href="{{ route('my-payroll') }}" class="{{ $navClass('my-payroll') }}">
                        <svg class="{{ $navIconClass('my-payroll') }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.66 0-3 .9-3 2s1.34 2 3 2 3 .9 3 2-1.34 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V6m0 10v-2m0-8a9 9 0 100 18 9 9 0 000-18z"/>
                        </svg>
                        My Payroll
                    </a>

                @endif

            </nav>

        </aside>

        {{-- Main Content --}}
        <div class="flex-1 flex flex-col min-w-0 gap-4">

            {{-- Navbar — floating glass bar, matches the sidebar --}}
            <header class="rounded-3xl px-8 py-4
                            bg-white/70 backdrop-blur-xl border border-white/60
                            shadow-[0_8px_32px_rgba(31,41,55,0.10)]">

                <div class="flex justify-between items-center gap-4">

                    {{-- Page Title --}}
                    <div>
                        <h1 class="text-xl font-bold text-gray-800">
                            @yield('title')
                        </h1>
                    </div>

                    {{-- Search --}}
                    <div class="flex-1 max-w-md hidden md:block">

                        <div class="relative">

                            <svg
                                class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24">

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z"/>

                            </svg>

                            <input
                                type="text"
                                placeholder="Search..."
                                class="w-full bg-white/60 backdrop-blur-sm border border-white/70 rounded-xl pl-9 pr-4 py-2 text-sm text-gray-600 shadow-inner focus:outline-none focus:ring-2 focus:ring-emerald-300/60 focus:bg-white/90 transition">

                        </div>

                    </div>

                    {{-- Right Side --}}
                    <div class="flex items-center gap-5">

                        {{-- Notifications --}}
                        <button
                            type="button"
                            class="relative text-gray-400 hover:text-emerald-600 bg-white/50 hover:bg-white/90 border border-white/60 rounded-xl p-2 shadow-sm transition-all duration-200 hover:-translate-y-0.5">

                            <svg
                                class="w-5 h-5"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24">

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 10-12 0v3.2c0 .5-.2 1-.6 1.4L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>

                            </svg>

                            <span
                                class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full shadow-[0_0_6px_2px_rgba(239,68,68,0.6)]">
                            </span>

                        </button>

                        {{-- User Dropdown --}}
                        <div
                            class="relative"
                            x-data="{ open: false }">

                            {{-- User Button --}}
                            <button
                                type="button"
                                @click="open = !open"
                                @click.outside="open = false"
                                class="flex items-center gap-3 rounded-xl px-2 py-1.5 hover:bg-white/60 transition">

                                {{-- Avatar --}}
                                <div class="relative w-9 h-9 rounded-full flex items-center justify-center text-sm font-bold uppercase text-white
                                            bg-gradient-to-br from-emerald-400 to-emerald-600
                                            shadow-[0_4px_12px_rgba(5,150,105,0.45)] ring-2 ring-white/70">
                                    <div class="absolute inset-x-1 top-0.5 h-1/2 bg-white/30 rounded-t-full pointer-events-none"></div>
                                    <span class="relative">{{ substr(Auth::user()->name, 0, 1) }}</span>
                                </div>

                                {{-- Name --}}
                                <div class="hidden sm:block text-left">

                                    <p class="text-sm font-medium text-gray-700">
                                        {{ Auth::user()->name }}
                                    </p>

                                    <p class="text-xs text-gray-400">
                                        {{ Auth::user()->role->name ?? 'User' }}
                                    </p>

                                </div>

                                {{-- Arrow --}}
                                <svg
                                    class="w-4 h-4 text-gray-400 transition-transform"
                                    :class="{ 'rotate-180': open }"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24">

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M19 9l-7 7-7-7"/>

                                </svg>

                            </button>

                            {{-- Dropdown --}}
                            <div
                                x-show="open"
                                x-transition
                                class="absolute right-0 mt-2 w-56 rounded-2xl z-50 overflow-hidden
                                       bg-white/80 backdrop-blur-xl border border-white/60
                                       shadow-[0_12px_36px_rgba(31,41,55,0.18)]"
                                style="display: none;">

                                {{-- User Information --}}
                                <div class="px-4 py-3 border-b border-white/60">

                                    <p class="text-sm font-semibold text-gray-800">
                                        {{ Auth::user()->name }}
                                    </p>

                                    <p class="text-xs text-gray-400 mt-0.5">
                                        {{ Auth::user()->email }}
                                    </p>

                                </div>

                                {{-- Profile --}}
                                <a
                                    href="{{ route('my-profile') }}"
                                    class="flex items-center gap-3 px-4 py-3 text-sm text-gray-600 hover:bg-white/70 transition">

                                    <svg
                                        class="w-5 h-5 text-gray-400"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24">

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M5.121 17.804A9 9 0 1018.88 17.8M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>

                                    </svg>

                                    My Profile

                                </a>

                                {{-- Logout --}}
                                <form
                                    method="POST"
                                    action="{{ route('logout') }}">

                                    @csrf

                                    <button
                                        type="submit"
                                        class="w-full flex items-center gap-3 px-4 py-3 text-sm text-red-500 hover:bg-red-50/70 transition">

                                        <svg
                                            class="w-5 h-5"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24">

                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>

                                        </svg>

                                        Logout

                                    </button>

                                </form>

                            </div>

                        </div>

                    </div>

                </div>

            </header>

            {{-- Page Content --}}
            <main class="flex-1 rounded-3xl">

                @if(session('success'))
                    <div class="mb-6 bg-emerald-50/80 backdrop-blur-sm border border-emerald-200/70 text-emerald-700 px-4 py-3 rounded-xl text-sm shadow-sm">
                        {{ session('success') }}
                    </div>
                @endif

                @yield('content')

            </main>

        </div>

    </div>

</div>

@stack('scripts')
</body>
</html>