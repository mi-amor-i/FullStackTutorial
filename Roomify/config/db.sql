

USE room_booking_db;

CREATE TABLE IF NOT EXISTS users(
            user_id INT AUTO_INCREMENT PRIMARY KEY,
            user_name VARCHAR(100) NOT NULL,
            user_email VARCHAR(150) NOT NULL,
            user_role ENUM('user','admin','staff') DEFAULT 'user',
            user_password VARCHAR(100) NOT NULL,
            user_created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );

CREATE TABLE IF NOT EXISTS rooms(
            room_id INT AUTO_INCREMENT PRIMARY KEY,
            room_name VARCHAR(100) NOT NULL,
            room_type VARCHAR(100) NOT NULL,
            room_capacity INT NOT NULL,
            room_price DECIMAL(10,2)  NOT NULL,
            room_status ENUM('active','maintanence') DEFAULT 'active',
            room_description TEXT NOT NULL,
            room_image VARCHAR(250) NOT NULL
            );

CREATE TABLE IF NOT EXISTS booking_table(
            booking_id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT,
            room_id INT,
            customer_name VARCHAR(50) NOT NULL,
            customer_email VARCHAR(100) NOT NULL,
            start_time DATE,
            end_time DATE,
            booking_status ENUM('confirmed','canceled','completed') DEFAULT 'confirmed',
            FOREIGN KEY (user_id) REFERENCES users(user_id),
            FOREIGN KEY (room_id) REFERENCES rooms(room_id)
            );


