-- ============================================
-- ComplaintHub Database Setup
-- Run this in phpMyAdmin or MySQL terminal
-- ============================================

-- Create and select the database
CREATE DATABASE IF NOT EXISTS complainthub;
USE complainthub;

-- -----------------------------------------------
-- TABLE: companies
-- Stores registered company accounts
-- -----------------------------------------------
CREATE TABLE IF NOT EXISTS companies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    company_name VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

-- -----------------------------------------------
-- TABLE: complaints
-- Stores complaints submitted by users
-- -----------------------------------------------
CREATE TABLE IF NOT EXISTS complaints (
    id INT AUTO_INCREMENT PRIMARY KEY,
    company_id INT NOT NULL,
    user_name VARCHAR(100) NOT NULL,
    user_email VARCHAR(150) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE CASCADE
);

-- -----------------------------------------------
-- Sample Data (optional - for testing)
-- -----------------------------------------------
INSERT INTO companies (company_name, email, password) VALUES
('Safaricom PLC', 'safaricom@demo.com', MD5('demo1234')),
('KCB Bank', 'kcb@demo.com', MD5('demo1234'));

INSERT INTO complaints (company_id, user_name, user_email, message) VALUES
(1, 'John Kamau', 'john@example.com', 'My internet has been down for three days with no response from support.'),
(1, 'Aisha Mwangi', 'aisha@example.com', 'I was overcharged on my last bill and cannot get a refund.'),
(2, 'Peter Omondi', 'peter@example.com', 'My loan application has been pending for over a month with no update.');
