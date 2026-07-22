<x-app-layout>

    <div class="p-6">

        <h2 class="text-2xl font-bold mb-6">
            Create Leave Request
        </h2>

        <form action="{{ route('leaves.store') }}" method="POST">

            @csrf

            @include('leaves._form')

        </form>

    </div>

</x-app-layout>