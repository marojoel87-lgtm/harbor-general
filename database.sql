-- =========================================================
-- HARBOR GENERAL — Hospital Management System
-- Database schema + sample data
-- Import this file via phpMyAdmin, or run: mysql -u root -p < database.sql
-- =========================================================

CREATE DATABASE IF NOT EXISTS harbor_general CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE harbor_general;

-- ---------------------------------------------------------
-- STAFF (login accounts for the management dashboard)
-- ---------------------------------------------------------
DROP TABLE IF EXISTS staff;
CREATE TABLE staff (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(50) NOT NULL DEFAULT 'Administrator',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Default admin account. Username: admin | Password: admin123
-- Additional sample staff accounts also use password: admin123 — change these in production.
-- (Hashes below were generated and verified against PHP's password_verify())
INSERT INTO staff (full_name, username, email, password, role) VALUES
('System Administrator', 'admin', 'admin@harborgeneral.example', '$2b$12$ikfRHh6H0shS1eGkp0EHZeGuRBtnnvrpOMvnQlZWlJbx6VViJSvdW', 'Administrator'),
('Blessing Nwosu', 'blessing.nwosu', 'blessing.nwosu@harborgeneral.example', '$2b$12$ikfRHh6H0shS1eGkp0EHZeGuRBtnnvrpOMvnQlZWlJbx6VViJSvdW', 'Receptionist'),
('Tobi Adekunle', 'tobi.adekunle', 'tobi.adekunle@harborgeneral.example', '$2b$12$ikfRHh6H0shS1eGkp0EHZeGuRBtnnvrpOMvnQlZWlJbx6VViJSvdW', 'Nurse');

-- ---------------------------------------------------------
-- PATIENTS
-- ---------------------------------------------------------
DROP TABLE IF EXISTS patients;
CREATE TABLE patients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    gender ENUM('Male','Female','Other') NOT NULL,
    dob DATE NOT NULL,
    phone VARCHAR(30) NOT NULL,
    email VARCHAR(100) NULL,
    address TEXT NULL,
    emergency_contact VARCHAR(150) NULL,
    registration_date DATE NOT NULL DEFAULT (CURRENT_DATE),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO patients (full_name, gender, dob, phone, email, address, emergency_contact, registration_date) VALUES
('Amaka Johnson', 'Female', '1994-03-12', '08012345678', 'amaka.johnson@example.com', '12 Palm Street, Lagos', 'Tunde Johnson - 08098765432', '2026-08-01'),
('Chidi Eze', 'Male', '1988-11-02', '08023456789', 'chidi.eze@example.com', '5 Ocean Drive, Lagos', 'Ifeoma Eze - 08087654321', '2026-08-05'),
('Grace Adeyemi', 'Female', '2001-07-19', '08034567890', NULL, '9 Marina Close, Lagos', NULL, '2026-08-10');

-- ---------------------------------------------------------
-- DOCTORS
-- ---------------------------------------------------------
DROP TABLE IF EXISTS doctors;
CREATE TABLE doctors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    specialty VARCHAR(100) NOT NULL,
    department VARCHAR(100) NOT NULL,
    phone VARCHAR(30) NOT NULL,
    email VARCHAR(100) NULL,
    status ENUM('Available','Unavailable') NOT NULL DEFAULT 'Available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO doctors (full_name, specialty, department, phone, email, status) VALUES
('Sarah Okafor', 'General Physician', 'General Medicine', '08111222333', 'sarah.okafor@harborgeneral.example', 'Available'),
('Michael Adebayo', 'Cardiologist', 'Cardiology', '08122333444', 'michael.adebayo@harborgeneral.example', 'Available'),
('Ngozi Umeh', 'Pediatrician', 'Pediatrics', '08133444555', 'ngozi.umeh@harborgeneral.example', 'Unavailable'),
('David Okon', 'Emergency Medicine Specialist', 'Emergency Care', '08144555666', 'david.okon@harborgeneral.example', 'Available');

-- ---------------------------------------------------------
-- APPOINTMENTS
-- ---------------------------------------------------------
DROP TABLE IF EXISTS appointments;
CREATE TABLE appointments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id INT NOT NULL,
    doctor_id INT NOT NULL,
    appointment_date DATE NOT NULL,
    appointment_time TIME NOT NULL,
    reason VARCHAR(255) NOT NULL,
    status ENUM('Scheduled','Pending','Completed','Cancelled') NOT NULL DEFAULT 'Scheduled',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_appointments_patient FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE CASCADE,
    CONSTRAINT fk_appointments_doctor FOREIGN KEY (doctor_id) REFERENCES doctors(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

INSERT INTO appointments (patient_id, doctor_id, appointment_date, appointment_time, reason, status) VALUES
(1, 1, CURDATE(), '09:30:00', 'Routine check-up', 'Scheduled'),
(2, 2, CURDATE(), '11:00:00', 'Chest pain follow-up', 'Pending'),
(3, 4, CURDATE(), '14:15:00', 'Minor injury assessment', 'Scheduled'),
(1, 2, DATE_SUB(CURDATE(), INTERVAL 10 DAY), '10:00:00', 'General consultation', 'Completed'),
(2, 1, DATE_SUB(CURDATE(), INTERVAL 5 DAY), '13:00:00', 'Blood pressure review', 'Completed'),
(3, 3, DATE_SUB(CURDATE(), INTERVAL 2 DAY), '15:30:00', 'Child wellness visit', 'Cancelled');

-- ---------------------------------------------------------
-- CONTACT MESSAGES (from public contact form)
-- ---------------------------------------------------------
DROP TABLE IF EXISTS contact_messages;
CREATE TABLE contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    message TEXT NOT NULL,
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
