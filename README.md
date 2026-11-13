# SAP / Enterprise Resource Planning (ERP) Backend System

A robust, enterprise-grade RESTful API backend built with **Laravel 10** and **PHP 8.2**, engineered for comprehensive Inventory Management, Point of Sale (POS), Supply Chain (GRN), Dynamic Pricing Control, and Multi-Branch Enterprise Operations.

---

## 🚀 Key Features & Modules

### 1. 🔐 Authentication & Access Control (RBAC)
- Token-based API authentication powered by **Laravel Sanctum**.
- Granular Role-Based Access Control (`admin`, `employee`, etc.).
- Secure session control and user profile management (`/api/me`).

### 2. 📊 Dynamic Dashboard & Real-Time Analytics
- Real-time business metrics and financial summaries.
- Low-stock warnings, purchase volume counters, and daily sales tracking.

### 3. 📦 Product & Catalog Management
- Hierarchical category and subcategory structures.
- Brand and unit management with bulk Excel/CSV import capabilities.
- Advanced product search, filtering, and dropdown helpers for responsive frontends.

### 4. 📈 Inventory, Live Stock & Bin Card Ledger
- **Multi-Branch Stock Tracking:** Monitor stock distribution across stores and branches.
- **Bin Card Ledger:** Detailed audit trails for every inventory inflow and outflow.
- **Batch Management & Adjustments:** Track batch-wise inventory and perform stock adjustments with automated logging.
- **Inter-Branch Transfers:** Seamless inventory movement between registered branches and stores.
- **Automated Alerts:** Low-stock threshold detection and adjustment reporting.

### 5. 🛒 Procurement & Supply Chain (GRN)
- Supplier management with contact directory and bulk import.
- Purchase Order / Goods Received Notes (GRN) workflow with automated sequential ID generation.
- **Purchase Returns:** Return defective items to suppliers with automatic stock reconciliation.

### 6. 🧾 Invoicing & Sales Operations
- Fast POS invoice creation and sequential invoice number management.
- Real-time inventory deduction upon checkout.
- Automated generation of Bin Card logs for each sale transaction.

### 7. 🔄 Returns & Credit Note System
- Search and verification of historical invoices for return processing.
- Multi-mode returns: refund or store credit note generation.
- Store credit deduction at checkout with verification.

### 8. 🏷️ Price Control & Price History Audit
- Centralized price regulation separate from standard product editing.
- Batch price update utilities.
- Historical price ledger recording previous prices, updated prices, timestamps, and authorized users.

### 9. 📑 Financial & Performance Reports
- **Sales Analysis Reports:** Sales broken down by product, category, date range, and payment types.
- **Profitability Reports:** Accurate net profit calculations based on dynamic cost price and selling price.
- **Adjustment & Transaction Reports:** Full accountability reports for store audits.

### 10. 🏢 Multi-Branch & Organizational Structure
- Branch, Department, Store, and Employee management.
- Banking details and payment account management.
- System parameter configuration (`InvPara`) for invoice styling, company headers, and fiscal settings.

---

## 🔗 Frontend Client Integration

This backend is designed to pair seamlessly with the frontend client:
👉 **Frontend Repository:** [https://github.com/sandun007/sap-frontend](https://github.com/sandun007/sap-frontend)

### Running Backend & Frontend Together:

1. **Start this Backend Server:**
   ```bash
   php artisan serve --port=8000
   ```
   *Runs at `http://127.0.0.1:8000` (or `http://localhost:8000`).*

2. **Configure the Frontend (`sap-frontend`):**
   In your `sap-frontend` project directory, configure your environment file to point to the backend API:
   ```env
   VITE_API_BASE_URL=http://localhost:8000/api
   ```

3. **CORS Support:**
   The backend allows cross-origin requests from frontend dev servers running on `http://localhost:5173`, `http://localhost:3000`, and `http://localhost:5174`.

4. **Default Seeded Admin Credentials:**
   - **Email:** `admin@example.com`
   - **Password:** `password`

---

## 🛠️ Technology Stack

- **Framework:** Laravel 10.x
- **Language:** PHP 8.2+
- **Database:** MySQL / MariaDB (Supports SQLite for testing)
- **Authentication:** Laravel Sanctum
- **Architecture:** RESTful API Design Pattern with Resource Controllers & Eloquent ORM

---

## 📋 Prerequisites

Before running the project locally, ensure you have:
- PHP >= 8.2
- Composer
- MySQL >= 8.0 or MariaDB >= 10.4
- Node.js & npm (optional, if building assets)

---

## ⚙️ Installation & Setup

1. **Clone the repository:**
   ```bash
   git clone -b sandun_br01 https://github.com/sandun007/sap-backend.git
   cd sap-backend
   ```

2. **Install PHP dependencies:**
   ```bash
   composer install
   ```

3. **Configure Environment:**
   ```bash
   cp .env.example .env
   ```
   Update `.env` with your database credentials:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=sap_db
   DB_USERNAME=root
   DB_PASSWORD=your_password
   ```

4. **Generate Application Key:**
   ```bash
   php artisan key:generate
   ```

5. **Run Migrations & Seeders:**
   ```bash
   php artisan migrate --seed
   ```
   > The seeder initializes default administrator accounts, master categories, demo suppliers, branches, and sample products.

6. **Start the Development Server:**
   ```bash
   php artisan serve
   ```
   The API will be available at `http://127.0.0.1:8000`.

---

## 📡 API Endpoints Overview

| Module | Method | Endpoint | Description |
|---|---|---|---|
| **Auth** | `POST` | `/api/login` | Authenticate and obtain Sanctum bearer token |
| **Auth** | `GET` | `/api/me` | Retrieve authenticated user profile |
| **Dashboard** | `GET` | `/api/dashboard/stats` | Business KPI counters and overview |
| **Products** | `GET` | `/api/products` | Paginated product list |
| **Inventory** | `GET` | `/api/stocks/history` | Bin Card inventory movement history |
| **Inventory** | `POST` | `/api/stocks/adjust-batches` | Batch-level inventory adjustments |
| **Invoicing** | `POST` | `/api/invoices` | Create invoice & deduct stock |
| **Returns** | `POST` | `/api/invoices/return` | Process return and issue credit note |
| **Purchases** | `GET` | `/api/purchases` | List Goods Received Notes (GRN) |
| **Price Control** | `POST` | `/api/price-control/batch-update` | Bulk adjust product price thresholds |
| **Reports** | `GET` | `/api/reports/sales` | Item-wise and date-wise sales reports |
| **Reports** | `GET` | `/api/reports/profit` | Comprehensive profitability report |

---

## 🔒 Security & Best Practices

- Standard CORS policies configured for decoupled frontend clients (React / Vue).
- Foreign key constraints with transactional rollback protection for high-value financial actions.
- Password hashing utilizing Bcrypt.

---

## 📄 License

This software is distributed under the [MIT License](LICENSE).
