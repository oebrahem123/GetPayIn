GetPayIn – Backend Task
This project is a backend assignment built with Laravel 10, implementing a complete flow for holding product stock, creating orders, and processing payment webhooks with idempotency support.

-> Features Implemented
1️⃣ Hold API
Creates a temporary product reservation
Locks stock for a limited time
Prevents overselling
Returns hold_id and expires_at

2️⃣ Order API
Creates an order from a valid hold
Marks hold as used
Calculates total amount
Ensures no race conditions using DB transactions

3️⃣ Payment Webhook
Validates payload signature (HMAC SHA256)
Uses idempotency keys to prevent duplicate processing
Updates product stock & order status
Supports both success and failed events

4️ Release Expired Holds
Custom Artisan command:

->php artisan holds:release-expired

Finds expired holds
Marks them as expired
Restores reserved stock safely

-API Endpoints
POST /api/holds
Creates a new hold
_Body:
-> {
  "product_id": 1,
  "qty": 2
  }

_POST /api/orders
Creates order from hold
Body:
-> {
  "hold_id": 5
   }

_POST /api/payments/webhook
Handles payment notifications
Headers:
-> Content-Type: application/json
X-Signature: {HMAC_SHA256_SIGNATURE}

_Body:
-> {
  "idempotency_key": "abc123",
  "order_id": 1,
  "status": "success",
  "provider_id": "prov_001"
   }

- Postman Collection
A ready-to-use Postman collection is included covering:
Holds
Orders
Webhook
Signature verification

- Tech Stack
PHP 8.2
Laravel 10
MySQL
Postman
Git / GitHub

-> Developer
Omar Ebrahem
Backend Developer (Laravel)
