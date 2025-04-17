# Frontend Architecture

## Component Structure

### Core Components
- `AppComponent` - Root component
- `HeaderComponent` - Top navigation bar
- `SidebarComponent` - Side navigation menu (for staff dashboard)
- `FooterComponent` - Page footer

### Authentication Components
- `LoginComponent` - Staff login page
- `AuthGuard` - Route guard for protected routes

### Public Components
- `QrScannerComponent` - Client QR code scanner
- `ComplaintFormComponent` - Complaint submission form
- `ComplaintTrackingComponent` - Track complaint status
- `SuccessComponent` - Confirmation page after submission

### Admin/Staff Components
- `DashboardComponent` - Main dashboard page
- `RoomManagementComponent` - CRUD operations for rooms
- `QrCodeGenerationComponent` - Generate QR codes for rooms
- `ComplaintListComponent` - View and manage complaints
- `TaskManagementComponent` - Assign and track maintenance tasks
- `ProductInventoryComponent` - Manage products and their lifecycles
- `ReportsComponent` - View statistics and reports

## Services
- `AuthService` - Handle authentication
- `RoomService` - Room-related API calls
- `QrCodeService` - QR code generation and validation
- `ComplaintService` - Submit and retrieve complaints
- `TaskService` - Task-related API calls
- `ProductService` - Product lifecycle management
- `StatisticsService` - Retrieve statistics data

## Models
- `User` - User data model
- `Room` - Room data model
- `QrCode` - QR code data model
- `Complaint` - Complaint data model
- `Task` - Task data model
- `Product` - Product data model

## Routing
- Public routes for QR scanning and complaint submission
- Protected routes for staff dashboard and admin functions
- Role-based route protection

## State Management
- Angular services with RxJS observables

## Additional Features
- Responsive design for mobile and desktop
- Real-time updates using websockets
- Offline capability for complaint submission
- Multilingual support (French and English)
- Dark mode support 