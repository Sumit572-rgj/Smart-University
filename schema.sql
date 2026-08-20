CREATE DATABASE IF NOT EXISTS cit_ums;
USE cit_ums;

-- Users Table (Handles login for all roles)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('admin', 'faculty', 'student', 'parent', 'warden', 'security') NOT NULL,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Students Profile
CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    enrollment_no VARCHAR(20) NOT NULL UNIQUE,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    department VARCHAR(50),
    batch VARCHAR(10),
    phone VARCHAR(15),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Faculty Profile
CREATE TABLE IF NOT EXISTS faculty (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    employee_id VARCHAR(20) NOT NULL UNIQUE,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    department VARCHAR(50),
    designation VARCHAR(50),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Outpass Requests
CREATE TABLE IF NOT EXISTS outpass (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    leave_date DATETIME NOT NULL,
    return_date DATETIME NOT NULL,
    reason TEXT NOT NULL,
    destination VARCHAR(255) NOT NULL,
    status ENUM('pending_faculty', 'pending_admin', 'pending_warden', 'approved', 'rejected', 'checked_out', 'checked_in') DEFAULT 'pending_faculty',
    qr_code VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
);

-- Password Resets
CREATE TABLE IF NOT EXISTS password_resets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) NOT NULL,
    token VARCHAR(255) NOT NULL,
    expires_at DATETIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (email) REFERENCES users(email) ON DELETE CASCADE
);

-- Finance & Fees
CREATE TABLE IF NOT EXISTS fees (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    invoice_no VARCHAR(50) NOT NULL UNIQUE,
    fee_type ENUM('tuition', 'hostel', 'library', 'exam', 'other') NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    due_date DATE NOT NULL,
    reference_no VARCHAR(100),
    status ENUM('pending', 'paid', 'verified', 'overdue') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
);

-- Online Exams
CREATE TABLE IF NOT EXISTS exams (
    id INT AUTO_INCREMENT PRIMARY KEY,
    faculty_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    course_code VARCHAR(50) NOT NULL,
    start_time DATETIME NOT NULL,
    duration_minutes INT NOT NULL,
    status ENUM('upcoming', 'active', 'completed') DEFAULT 'upcoming',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (faculty_id) REFERENCES faculty(id) ON DELETE CASCADE
);

-- Exam Results
CREATE TABLE IF NOT EXISTS exam_results (
    id INT AUTO_INCREMENT PRIMARY KEY,
    exam_id INT NOT NULL,
    student_id INT NOT NULL,
    score DECIMAL(5, 2) NOT NULL,
    total_marks DECIMAL(5, 2) NOT NULL,
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (exam_id) REFERENCES exams(id) ON DELETE CASCADE,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
);

-- Hostel Management
CREATE TABLE IF NOT EXISTS hostel_rooms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    room_number VARCHAR(20) NOT NULL UNIQUE,
    block_name VARCHAR(50) NOT NULL,
    capacity INT NOT NULL,
    occupancy INT DEFAULT 0
);

CREATE TABLE IF NOT EXISTS hostel_allocations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    room_id INT NOT NULL,
    allocated_date DATE NOT NULL,
    status ENUM('active', 'vacated') DEFAULT 'active',
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (room_id) REFERENCES hostel_rooms(id) ON DELETE CASCADE
);

-- Library System
CREATE TABLE IF NOT EXISTS library_books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(255) NOT NULL,
    isbn VARCHAR(50) UNIQUE,
    available_copies INT NOT NULL,
    total_copies INT NOT NULL
);

CREATE TABLE IF NOT EXISTS library_issues (
    id INT AUTO_INCREMENT PRIMARY KEY,
    book_id INT NOT NULL,
    student_id INT NOT NULL,
    issue_date DATE NOT NULL,
    due_date DATE NOT NULL,
    return_date DATE NULL,
    status ENUM('issued', 'returned', 'overdue') DEFAULT 'issued',
    FOREIGN KEY (book_id) REFERENCES library_books(id) ON DELETE CASCADE,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
);

-- Transport Management
CREATE TABLE IF NOT EXISTS transport_routes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    route_name VARCHAR(100) NOT NULL,
    bus_number VARCHAR(20) NOT NULL,
    driver_name VARCHAR(50) NOT NULL,
    driver_phone VARCHAR(15) NOT NULL,
    capacity INT NOT NULL,
    occupancy INT DEFAULT 0
);

CREATE TABLE IF NOT EXISTS transport_allocations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    route_id INT NOT NULL,
    boarding_point VARCHAR(100) NOT NULL,
    allocated_date DATE NOT NULL,
    status ENUM('active', 'cancelled') DEFAULT 'active',
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (route_id) REFERENCES transport_routes(id) ON DELETE CASCADE
);

-- Health & Wellness
CREATE TABLE IF NOT EXISTS health_records (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    blood_group VARCHAR(5) NOT NULL,
    medical_conditions TEXT,
    allergies TEXT,
    emergency_contact VARCHAR(50) NOT NULL,
    emergency_phone VARCHAR(15) NOT NULL,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
);

-- Placement & Career
CREATE TABLE IF NOT EXISTS placement_jobs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    company_name VARCHAR(100) NOT NULL,
    job_title VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    salary_package VARCHAR(50),
    deadline DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS placement_applications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    job_id INT NOT NULL,
    student_id INT NOT NULL,
    resume_link VARCHAR(255) NOT NULL,
    status ENUM('applied', 'shortlisted', 'selected', 'rejected') DEFAULT 'applied',
    applied_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (job_id) REFERENCES placement_jobs(id) ON DELETE CASCADE,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
);

-- Grievance & Feedback
CREATE TABLE IF NOT EXISTS grievances (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT,
    category ENUM('academic', 'hostel', 'facilities', 'other') NOT NULL,
    subject VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    status ENUM('open', 'in_progress', 'resolved') DEFAULT 'open',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE SET NULL
);

-- Events & Notice Management
CREATE TABLE IF NOT EXISTS events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    event_date DATETIME NOT NULL,
    venue VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Alumni Network
CREATE TABLE IF NOT EXISTS alumni (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    graduation_year INT NOT NULL,
    company VARCHAR(100),
    designation VARCHAR(100),
    linkedin_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Timetable Generator
CREATE TABLE IF NOT EXISTS timetable (
    id INT AUTO_INCREMENT PRIMARY KEY,
    department VARCHAR(50) NOT NULL,
    semester INT NOT NULL,
    day_of_week ENUM('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday') NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    course_code VARCHAR(50) NOT NULL,
    faculty_id INT,
    room VARCHAR(50) NOT NULL,
    FOREIGN KEY (faculty_id) REFERENCES faculty(id) ON DELETE SET NULL
);

-- Attendance System
CREATE TABLE IF NOT EXISTS attendance (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    course_code VARCHAR(50) NOT NULL,
    date DATE NOT NULL,
    status ENUM('present', 'absent', 'late') NOT NULL,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
);

-- Security & Activity Logs
CREATE TABLE IF NOT EXISTS activity_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    action VARCHAR(255) NOT NULL,
    ip_address VARCHAR(45) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Phase 11: Research & Publications
CREATE TABLE IF NOT EXISTS research_papers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    faculty_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    journal_name VARCHAR(255) NOT NULL,
    publication_date DATE NOT NULL,
    doi_link VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (faculty_id) REFERENCES faculty(id) ON DELETE CASCADE
);

-- Phase 12: Internal Messaging
CREATE TABLE IF NOT EXISTS messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sender_id INT NOT NULL,
    receiver_id INT NOT NULL,
    subject VARCHAR(255) NOT NULL,
    body TEXT NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (receiver_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Phase 16: Inventory & Asset Management
CREATE TABLE IF NOT EXISTS inventory_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    item_name VARCHAR(100) NOT NULL,
    category VARCHAR(50) NOT NULL,
    quantity INT NOT NULL,
    location VARCHAR(100) NOT NULL,
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Phase 17: Visitor Management
CREATE TABLE IF NOT EXISTS visitors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    visitor_name VARCHAR(100) NOT NULL,
    phone VARCHAR(15) NOT NULL,
    purpose VARCHAR(255) NOT NULL,
    host_name VARCHAR(100) NOT NULL,
    check_in DATETIME NOT NULL,
    check_out DATETIME NULL,
    status ENUM('checked_in', 'checked_out') DEFAULT 'checked_in'
);

-- Phase 18: Task Manager
CREATE TABLE IF NOT EXISTS tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    status ENUM('pending', 'completed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Phase 19: Disciplinary Records
CREATE TABLE IF NOT EXISTS disciplinary_records (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    incident_date DATE NOT NULL,
    description TEXT NOT NULL,
    action_taken VARCHAR(255) NOT NULL,
    status ENUM('active', 'resolved') DEFAULT 'active',
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
);

-- Initial Admin Data (Password is 'admin123')
INSERT INTO users (username, email, password_hash, role) 
VALUES ('admin', 'admin@cit.edu.in', '$2y$10$yRey7S8KXBY4gky/wHhswebyDOGLMbkcL2lFEVcWfjMsJUlfcCF9G', 'admin')
ON DUPLICATE KEY UPDATE id=id;

-- Seed Data
INSERT IGNORE INTO hostel_rooms (room_number, block_name, capacity) VALUES ('A-101', 'Block A', 2), ('A-102', 'Block A', 2), ('B-201', 'Block B', 3);
INSERT IGNORE INTO library_books (title, author, isbn, available_copies, total_copies) VALUES ('Introduction to Algorithms', 'Thomas H. Cormen', '9780262033848', 5, 5), ('Clean Code', 'Robert C. Martin', '9780132350884', 3, 3);
INSERT IGNORE INTO transport_routes (route_name, bus_number, driver_name, driver_phone, capacity) VALUES ('Tambaram Route', 'TN 01 AB 1234', 'Ramesh', '9876543210', 40), ('Adyar Route', 'TN 02 XY 5678', 'Suresh', '9876543211', 40);
INSERT IGNORE INTO placement_jobs (company_name, job_title, description, salary_package, deadline) VALUES ('Google', 'Software Engineer', 'Full stack development role', '35 LPA', '2026-09-30'), ('TCS', 'System Engineer', 'IT services', '7 LPA', '2026-10-15');
INSERT IGNORE INTO events (title, description, event_date, venue) VALUES ('Tech Symposium 2026', 'Annual tech fest featuring AI workshops.', '2026-11-20 09:00:00', 'Main Auditorium');
INSERT IGNORE INTO alumni (name, graduation_year, company, designation, linkedin_url) VALUES ('Arun Kumar', 2020, 'Amazon', 'SDE II', 'linkedin.com/in/arun');
INSERT IGNORE INTO timetable (department, semester, day_of_week, start_time, end_time, course_code, room) VALUES ('CSE', 5, 'Monday', '09:00:00', '10:00:00', 'CS301', 'Lab 1'), ('CSE', 5, 'Monday', '10:00:00', '11:00:00', 'CS302', 'Room 105');
INSERT IGNORE INTO inventory_items (item_name, category, quantity, location) VALUES ('Dell Optiplex', 'Electronics', 50, 'Computer Lab 1'), ('Beakers 500ml', 'Lab Equipment', 100, 'Chemistry Lab');
INSERT IGNORE INTO visitors (visitor_name, phone, purpose, host_name, check_in) VALUES ('Mr. Sharma', '9876543210', 'Parent Meeting', 'Prof. Kumar', NOW());
INSERT IGNORE INTO tasks (user_id, title) VALUES (1, 'Review mid-term results'), (1, 'Update system configurations');
