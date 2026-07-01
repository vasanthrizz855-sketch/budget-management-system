CREATE DATABASE IF NOT EXISTS management_pro;
USE management_pro;

-- =====================================================
-- 1. USERS
-- =====================================================

CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('Admin') DEFAULT 'Admin',
    status ENUM('Active','Inactive') DEFAULT 'Active',
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- =====================================================
-- 2. CUSTOMERS
-- =====================================================

CREATE TABLE customers (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    customer_code VARCHAR(50) UNIQUE NOT NULL,
    customer_name VARCHAR(150) NOT NULL,
    email VARCHAR(150),
    phone VARCHAR(20),
    address TEXT,
    gst_number VARCHAR(50),
    status ENUM('Active','Inactive') DEFAULT 'Active',
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- =====================================================
-- 3. SUPPLIERS
-- =====================================================

CREATE TABLE suppliers (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    supplier_code VARCHAR(50) UNIQUE NOT NULL,
    supplier_name VARCHAR(150) NOT NULL,
    email VARCHAR(150),
    phone VARCHAR(20),
    address TEXT,
    gst_number VARCHAR(50),
    status ENUM('Active','Inactive') DEFAULT 'Active',
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- =====================================================
-- 4. PRODUCTS
-- =====================================================

CREATE TABLE products (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    product_code VARCHAR(50) UNIQUE NOT NULL,
    product_name VARCHAR(200) NOT NULL,
    description TEXT,
    unit_price DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    tax_percentage DECIMAL(5,2) DEFAULT 0.00,
    status ENUM('Active','Inactive') DEFAULT 'Active',
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- =====================================================
-- 5. PURCHASE INVOICES
-- =====================================================

CREATE TABLE purchase_invoices (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    invoice_no VARCHAR(100) UNIQUE NOT NULL,
    supplier_id BIGINT UNSIGNED NOT NULL,
    invoice_date DATE NOT NULL,
    subtotal DECIMAL(15,2) DEFAULT 0.00,
    tax_amount DECIMAL(15,2) DEFAULT 0.00,
    discount_amount DECIMAL(15,2) DEFAULT 0.00,
    grand_total DECIMAL(15,2) DEFAULT 0.00,
    payment_status ENUM('Paid','Pending','Overdue') DEFAULT 'Pending',
    remarks TEXT,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_purchase_supplier
        FOREIGN KEY (supplier_id)
        REFERENCES suppliers(id)
        ON DELETE CASCADE
);

-- =====================================================
-- 6. PURCHASE INVOICE ITEMS
-- =====================================================

CREATE TABLE purchase_invoice_items (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    purchase_invoice_id BIGINT UNSIGNED NOT NULL,
    product_id BIGINT UNSIGNED NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    price DECIMAL(15,2) DEFAULT 0.00,
    tax DECIMAL(15,2) DEFAULT 0.00,
    total DECIMAL(15,2) DEFAULT 0.00,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_purchase_invoice
        FOREIGN KEY (purchase_invoice_id)
        REFERENCES purchase_invoices(id)
        ON DELETE CASCADE,
    CONSTRAINT fk_purchase_product
        FOREIGN KEY (product_id)
        REFERENCES products(id)
        ON DELETE CASCADE
);

-- =====================================================
-- 7. SALES INVOICES
-- =====================================================

CREATE TABLE sales_invoices (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    invoice_no VARCHAR(100) UNIQUE NOT NULL,
    customer_id BIGINT UNSIGNED NOT NULL,
    invoice_date DATE NOT NULL,
    subtotal DECIMAL(15,2) DEFAULT 0.00,
    tax_amount DECIMAL(15,2) DEFAULT 0.00,
    discount_amount DECIMAL(15,2) DEFAULT 0.00,
    grand_total DECIMAL(15,2) DEFAULT 0.00,
    payment_status ENUM('Paid','Pending','Overdue') DEFAULT 'Pending',
    remarks TEXT,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_sales_customer
        FOREIGN KEY (customer_id)
        REFERENCES customers(id)
        ON DELETE CASCADE
);

-- =====================================================
-- 8. SALES INVOICE ITEMS
-- =====================================================

CREATE TABLE sales_invoice_items (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    sales_invoice_id BIGINT UNSIGNED NOT NULL,
    product_id BIGINT UNSIGNED NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    price DECIMAL(15,2) DEFAULT 0.00,
    tax DECIMAL(15,2) DEFAULT 0.00,
    total DECIMAL(15,2) DEFAULT 0.00,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_sales_invoice
        FOREIGN KEY (sales_invoice_id)
        REFERENCES sales_invoices(id)
        ON DELETE CASCADE,
    CONSTRAINT fk_sales_product
        FOREIGN KEY (product_id)
        REFERENCES products(id)
        ON DELETE CASCADE
);

-- =====================================================
-- 9. BUDGETS
-- =====================================================

CREATE TABLE budgets (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    budget_name VARCHAR(150) NOT NULL,
    budget_type ENUM('Monthly','Quarterly','Yearly') NOT NULL,
    allocated_amount DECIMAL(15,2) DEFAULT 0.00,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    notes TEXT,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- =====================================================
-- 10. EXPENSES
-- =====================================================

CREATE TABLE expenses (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    budget_id BIGINT UNSIGNED NOT NULL,
    expense_name VARCHAR(200) NOT NULL,
    expense_amount DECIMAL(15,2) DEFAULT 0.00,
    expense_date DATE NOT NULL,
    description TEXT,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_expense_budget
        FOREIGN KEY (budget_id)
        REFERENCES budgets(id)
        ON DELETE CASCADE
);

-- =====================================================
-- 11. PAYMENTS
-- =====================================================

CREATE TABLE payments (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    invoice_type ENUM('Purchase','Sales') NOT NULL,
    invoice_id BIGINT UNSIGNED NOT NULL,
    payment_method ENUM(
        'Cash',
        'UPI',
        'Bank Transfer',
        'Credit Card',
        'Debit Card'
    ) NOT NULL,
    payment_date DATE NOT NULL,
    amount DECIMAL(15,2) DEFAULT 0.00,
    remarks TEXT,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);