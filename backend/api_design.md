# API Endpoints

## Authentication
- `POST /api/auth/login` - User login
- `POST /api/auth/logout` - User logout
- `GET /api/auth/user` - Get current user info

## Rooms
- `GET /api/rooms` - List all rooms
- `GET /api/rooms/{id}` - Get room details
- `POST /api/rooms` - Create new room
- `PUT /api/rooms/{id}` - Update room
- `DELETE /api/rooms/{id}` - Delete room

## QR Codes
- `GET /api/qrcodes` - List all QR codes
- `GET /api/qrcodes/{id}` - Get QR code details
- `POST /api/qrcodes` - Generate new QR code
- `PUT /api/qrcodes/{id}` - Update QR code status
- `GET /api/qrcodes/room/{roomId}` - Get QR codes for a specific room
- `GET /api/qrcodes/validate/{code}` - Validate a QR code

## Complaints
- `GET /api/complaints` - List all complaints (admin/staff)
- `GET /api/complaints/{id}` - Get complaint details
- `POST /api/complaints` - Submit new complaint (client)
- `PUT /api/complaints/{id}` - Update complaint
- `GET /api/complaints/qrcode/{qrCodeId}` - Get complaints from specific QR code
- `GET /api/complaints/status/{status}` - Filter complaints by status

## Tasks
- `GET /api/tasks` - List all tasks
- `GET /api/tasks/{id}` - Get task details
- `POST /api/tasks` - Create new task
- `PUT /api/tasks/{id}` - Update task
- `PUT /api/tasks/{id}/status` - Update task status
- `GET /api/tasks/user/{userId}` - Get tasks assigned to specific user
- `GET /api/tasks/status/{status}` - Filter tasks by status

## Products
- `GET /api/products` - List all products
- `GET /api/products/{id}` - Get product details
- `POST /api/products` - Add new product
- `PUT /api/products/{id}` - Update product
- `DELETE /api/products/{id}` - Delete product
- `GET /api/products/lifespan/expiring` - Get products approaching end of life

## Statistics
- `GET /api/stats/complaints` - Get complaints statistics
- `GET /api/stats/tasks` - Get tasks completion statistics
- `GET /api/stats/products` - Get product lifecycle statistics 