# Database Design

## Tables Structure

### Users
- id (PK)
- name
- email
- password (hashed)
- role (enum: 'admin', 'receptionist', 'maintenance_agent', 'maintenance_chief')
- created_at
- updated_at

### Rooms
- id (PK)
- room_number
- floor
- type
- status (enum: 'available', 'occupied', 'maintenance')
- created_at
- updated_at

### QR_Codes
- id (PK)
- room_id (FK)
- unique_code
- expiry_date
- status (enum: 'active', 'inactive')
- created_at
- updated_at

### Complaints
- id (PK)
- qr_code_id (FK)
- client_name
- description
- urgency_level (enum: 'low', 'medium', 'high')
- status (enum: 'pending', 'in_progress', 'resolved', 'out_of_stock', 'blocked')
- created_at
- updated_at

### Tasks
- id (PK)
- complaint_id (FK)
- assigned_to (FK to users)
- approved_by (FK to users)
- notes
- status (enum: 'pending', 'in_progress', 'resolved', 'out_of_stock', 'blocked')
- created_at
- updated_at
- completed_at

### Products
- id (PK)
- name
- category
- purchase_date
- expected_lifespan (in days)
- status (enum: 'active', 'end_of_life_approaching', 'end_of_life')
- created_at
- updated_at

### Product_Maintenance
- id (PK)
- product_id (FK)
- task_id (FK)
- maintenance_type
- notes
- created_at
- updated_at

## Relationships

1. A Room has many QR_Codes (one-to-many)
2. A QR_Code has many Complaints (one-to-many)
3. A Complaint has one Task (one-to-one)
4. A Task can involve multiple Products (many-to-many through Product_Maintenance)
5. A User can be assigned to many Tasks (one-to-many)
6. A User can approve many Tasks (one-to-many) 