@section('title')
Food Market - Makanan
@endsection
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Food') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-10">
                <a href="{{ route('foods.create') }}"
                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    + Tambah Makanan
                </a>
            </div>
            <div class="bg-white">
                <table class="table-auto w-full">
                    <thead>
                        <tr>
                            <th class="border px-6 py-4">Nomor</th>
                            <th class="border px-6 py-4">Nama</th>
                            <th class="border px-6 py-4">Harga</th>
                            <th class="border px-6 py-4">Rate</th>
                            <th class="border px-6 py-4">Type</th>
                            <th class="border px-6 py-4">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($foods as $item)
                        <tr>
                            <td class="border px-6 py-4">{{ $loop->iteration }}</td>
                            <td class="border px-6 py-4">{{ $item->name }}</td>
                            <td class="border px-6 py-4">{{ $item->price }}</td>
                            <td class="border px-6 py-4">{{ $item->rating }}</td>
                            <td class="border px-6 py-4">
                                @if($item->type == 'new_food')
                                    New Food
                                @elseif($item->type == 'recommended')
                                    Recommended
                                @else
                                    Popular
                                @endif
                            </td>
                            <td class="border px-6 py-4 text-center">
                                <a href="{{ route('foods.edit',$item->id) }}" class="inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 mx-2 rounded">
                                    Edit</a>
                                    <form action="{{ route('foods.destroy', $item->id) }}" method="post" class="inline-block">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 mx-2 rounded">
                                            Delete
                                        </button>
                                    </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="border text-center p-5">Data tidak ditemukan</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="text-center mt-5">
                {{ $foods->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
