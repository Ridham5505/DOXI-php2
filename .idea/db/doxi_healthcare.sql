-- DOXI Healthcare SQL Export
-- Import this file in phpMyAdmin to create the database and tables

-- Create database
CREATE DATABASE IF NOT EXISTS `doxi` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `doxi`;

-- Users table (unified for admin/doctor/patient)
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `role` ENUM('admin','doctor','patient') NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `first_name` VARCHAR(100) NOT NULL,
  `last_name` VARCHAR(100) NOT NULL,
  `phone` VARCHAR(20) NULL,
  `date_of_birth` DATE NULL,
  `gender` ENUM('Female','Male','Other','Prefer not to say') NULL,
  `address` TEXT NULL,
  `specialty` VARCHAR(100) NULL,
  `license_number` VARCHAR(100) NULL,
  `years_experience` INT NULL,
  `practice_address` TEXT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_users_email` (`email`),
  UNIQUE KEY `uniq_users_license` (`license_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Appointments table (FKs to users)
CREATE TABLE IF NOT EXISTS `appointments` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `patient_id` INT NOT NULL,
  `doctor_id` INT NOT NULL,
  `appointment_date` DATE NOT NULL,
  `appointment_time` TIME NOT NULL,
  `status` ENUM('scheduled','completed','cancelled','rescheduled') NOT NULL DEFAULT 'scheduled',
  `notes` TEXT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_appt_patient` (`patient_id`),
  KEY `idx_appt_doctor` (`doctor_id`),
  CONSTRAINT `fk_appt_patient` FOREIGN KEY (`patient_id`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_appt_doctor` FOREIGN KEY (`doctor_id`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Medical records table (FKs to users)
CREATE TABLE IF NOT EXISTS `medical_records` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `patient_id` INT NOT NULL,
  `doctor_id` INT NOT NULL,
  `record_type` ENUM('consultation','test_result','prescription','diagnosis') NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `description` TEXT NULL,
  `file_path` VARCHAR(500) NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_rec_patient` (`patient_id`),
  KEY `idx_rec_doctor` (`doctor_id`),
  CONSTRAINT `fk_rec_patient` FOREIGN KEY (`patient_id`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_rec_doctor` FOREIGN KEY (`doctor_id`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- User sessions table (optional; used by login API)
CREATE TABLE IF NOT EXISTS `user_sessions` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `role` ENUM('patient','doctor','admin') NOT NULL,
  `token` VARCHAR(64) NOT NULL,
  `expires_at` TIMESTAMP NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_session_token` (`token`),
  KEY `idx_session_user` (`user_id`),
  CONSTRAINT `fk_session_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Seed default admin (email: admin@doxi.com / password: admin123)
INSERT INTO `users` (`role`,`email`,`password`,`first_name`,`last_name`)
SELECT 'admin','admin@doxi.com',
       '$2y$10$WgYlZ6p0fFq0Ew3mTz2wUuQ5s4yV3Yk2yVQ0q3b3oH2VQdVw2Y0hW',
       'System','Administrator'
WHERE NOT EXISTS (SELECT 1 FROM `users` WHERE `email`='admin@doxi.com');

-- Note: The password hash above corresponds to 'admin123' using PASSWORD_DEFAULT at time of creation.


