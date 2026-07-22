<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meridian CRM</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    
</head>

<body class="bg-gray-100">

<div class="flex min-h-screen">

    {{-- Sidebar --}}
    <aside class="w-64 bg-slate-900 text-white">

        <div class="text-2xl font-bold p-6 border-b border-slate-700">
            Meridian CRM
        </div>

        <nav class="p-4 space-y-2">

            <a href="{{ route('dashboard') }}"
                class="block rounded-lg px-4 py-2 hover:bg-slate-700">
                Dashboard
            </a>

            <div class="pt-4 text-xs uppercase text-gray-400">
                Management
            </div>

            <a href="{{ route('branches.index') }}"
                class="block rounded-lg px-4 py-2 hover:bg-slate-700">
                Branches
            </a>

        <a href="{{ route('employees.index') }}"
        class="block rounded-lg px-4 py-2 hover:bg-slate-700">
        Employees
          </a>

          <a href="{{ route('attendances.index') }}"
    class="block rounded-lg px-4 py-2 hover:bg-slate-700">
    Attendance
</a>


<div class="pt-4 text-xs uppercase text-gray-400">
    Reports
</div>

<a href="{{ route('reports.attendance') }}"
   class="block rounded-lg px-4 py-2 hover:bg-slate-700">
    Attendance Report
</a>    

        </nav>

    </aside>

    {{-- Main Content --}}
    <div class="flex-1">

        {{-- Navbar --}}
        <header class="bg-white shadow px-8 py-4">

            <div class="flex justify-between items-center">

                <h1 class="text-2xl font-bold">
                    @yield('title')
                </h1>

                <div>
                    {{ Auth::user()->name }}
                </div>

            </div>

        </header>

        {{-- Page Content --}}
        <main class="p-8">

    @if(session('success'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
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