@extends('admin.layout')

@section('content')
<h1 class="text-2xl font-bold mb-4">Products</h1>

<div class="bg-white p-4 rounded shadow">
  <table class="w-full text-sm">
    <thead class="text-left text-xs text-gray-500">
      <tr><th class="p-2">ID</th><th>Name</th><th>Price</th><th>Stock</th><th>Reserved</th><th>Updated</th></tr>
    </thead>
    <tbody>
      @foreach($products as $item)
        <tr class="border-t">
          <td class="p-2">{{ $item->id }}</td>
          <td class="p-2">{{ $item->name }}</td>
          <td class="p-2">{{ $item->price }}</td>
          <td class="p-2">{{ $item->stock }}</td>
          <td class="p-2">{{ $item->reserved }}</td>
          <td class="p-2">{{ $item->updated_at }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>

  <div class="mt-4">{{ $products->links() }}</div>
</div>
@endsection
