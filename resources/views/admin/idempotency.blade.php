@extends('admin.layout')

@section('content')
<h1 class="text-2xl font-bold mb-4">Idempotency Keys</h1>

<div class="bg-white p-4 rounded shadow">
  <table class="w-full text-sm">
    <thead class="text-left text-xs text-gray-500">
      <tr><th class="p-2">ID</th><th>Key</th><th>Resource</th><th>Resource ID</th><th>Snapshot</th><th>Created</th></tr>
    </thead>
    <tbody>
      @foreach($ikeys as $item)
        <tr class="border-t">
          <td class="p-2">{{ $item->id }}</td>
          <td class="p-2 font-mono text-xs">{{ $item->key }}</td>
          <td class="p-2">{{ $item->resource_type }}</td>
          <td class="p-2">{{ $item->resource_id }}</td>
          <td class="p-2"><pre class="text-xs">{{ json_encode($item->response_snapshot) }}</pre></td>
          <td class="p-2">{{ $item->created_at }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>

  <div class="mt-4">{{ $ikeys->links() }}</div>
</div>
@endsection
