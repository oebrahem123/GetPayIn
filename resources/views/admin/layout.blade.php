<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Admin Dashboard - GetPayIn</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 text-gray-800">
  <div class="min-h-screen flex">
    <aside class="w-64 bg-white border-r">
      <div class="p-4 text-xl font-semibold">Admin Dashboard</div>
      <nav class="p-4">
        <a href="{{ url('/admin') }}" class="block py-2 px-3 rounded hover:bg-gray-50">Home</a>
        <a href="{{ url('/admin/products') }}" class="block py-2 px-3 rounded hover:bg-gray-50">Products</a>
        <a href="{{ url('/admin/holds') }}" class="block py-2 px-3 rounded hover:bg-gray-50">Holds</a>
        <a href="{{ url('/admin/orders') }}" class="block py-2 px-3 rounded hover:bg-gray-50">Orders</a>
        <a href="{{ url('/admin/idempotency') }}" class="block py-2 px-3 rounded hover:bg-gray-50">Idempotency Keys</a>
      </nav>
    </aside>

    <main class="flex-1 p-6">
      <div class="container mx-auto">
        @yield('content')
      </div>
    </main>
  </div>
</body>
</html>
