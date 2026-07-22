<x-app-layout>

    <div class="p-6">

        <h2 class="text-2xl font-bold mb-6">
            Edit Leave Request
        </h2>

        <form action="{{ route('leaves.update', $leave->id) }}" method="POST">

            @csrf
            @method('PUT')

            @include('leaves._form')

        </form>

    </div>

</x-app-layout>