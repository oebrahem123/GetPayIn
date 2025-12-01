@extends('admin.layout')

@section('content')
<h1 class="text-2xl font-bold mb-4">Holds</h1>

<div class="bg-white p-4 rounded shadow">
  <table class="w-full text-sm">
    <thead class="text-left text-xs text-gray-500">
      <tr><th class="p-2">ID</th><th>Product</th><th>Qty</th><th>Status</th><th>Expires</th><th>Updated</th></tr>
    </thead>
    <tbody>
      @foreach($holds as $item)
        <tr class="border-t">
          <td class="p-2">{{ $item->id }}</td>
          <td class="p-2">{{ $item->product ? $item->product->name : '—' }} (id:{{ $item->product_id }})</td>
          <td class="p-2">{{ $item->quantity }}</td>
          <td class="p-2">{{ $item->status }}</td>
          <td class="p-2">{{ $item->expires_at }}</td>
          <td class="p-2">{{ $item->updated_at }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>

  <div class="mt-4">{{ $holds->links() }}</div>
</div>
@endsection
