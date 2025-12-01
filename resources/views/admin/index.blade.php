@extends('admin.layout')

@section('content')
  <h1 class="text-2xl font-bold mb-4">Overview</h1>

  <div class="grid grid-cols-4 gap-4 mb-6">
    <div class="bg-white p-4 rounded shadow">
      <div class="text-sm text-gray-500">Products</div>
      <div class="text-2xl font-bold">{{ $counts['products'] }}</div>
    </div>
    <div class="bg-white p-4 rounded shadow">
      <div class="text-sm text-gray-500">Holds</div>
      <div class="text-2xl font-bold">{{ $counts['holds'] }}</div>
    </div>
    <div class="bg-white p-4 rounded shadow">
      <div class="text-sm text-gray-500">Orders</div>
      <div class="text-2xl font-bold">{{ $counts['orders'] }}</div>
    </div>
    <div class="bg-white p-4 rounded shadow">
      <div class="text-sm text-gray-500">Idempotency Keys</div>
      <div class="text-2xl font-bold">{{ $counts['ikeys'] }}</div>
    </div>
  </div>

  <div class="bg-white p-4 rounded shadow">
    <h2 class="font-semibold mb-2">Recent Orders</h2>
    <table class="w-full text-sm">
      <thead class="text-left text-xs text-gray-500">
        <tr><th class="p-2">#</th><th>Hold</th><th>Status</th><th>Qty</th><th>Total</th><th>Provider</th><th>Created</th></tr>
      </thead>
      <tbody>
        @foreach($recentOrders as $item)
          <tr class="border-t">
            <td class="p-2">{{ $item->id }}</td>
            <td class="p-2">{{ $item->hold_id }}</td>
            <td class="p-2">{{ $item->status }}</td>
            <td class="p-2">{{ $item->quantity }}</td>
            <td class="p-2">{{ $item->total_amount }}</td>
            <td class="p-2">{{ $item->payment_provider_id }}</td>
            <td class="p-2">{{ $item->created_at }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
@endsection
