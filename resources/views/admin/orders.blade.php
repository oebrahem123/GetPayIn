@extends('admin.layout')

@section('content')
<h1 class="text-2xl font-bold mb-4">Orders</h1>

<div class="bg-white p-4 rounded shadow">
  <table class="w-full text-sm">
    <thead class="text-left text-xs text-gray-500">
      <tr><th class="p-2">ID</th><th>Hold</th><th>Status</th><th>Qty</th><th>Total</th><th>Provider</th><th>Updated</th></tr>
    </thead>
    <tbody>
      @foreach($orders as $item)
        <tr class="border-t">
          <td class="p-2">{{ $item->id }}</td>
          <td class="p-2">{{ $item->hold_id }}</td>
          <td class="p-2">{{ $item->status }}</td>
          <td class="p-2">{{ $item->quantity }}</td>
          <td class="p-2">{{ $item->total_amount }}</td>
          <td class="p-2">{{ $item->payment_provider_id }}</td>
          <td class="p-2">{{ $item->updated_at }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>

  <div class="mt-4">{{ $orders->links() }}</div>
</div>
@endsection
