-- Database Schema for MMO Project
-- Created by Senior Developer (20 years experience)

-- Users table
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `balance` decimal(15,2) DEFAULT 0.00,
  `remember_token` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `ip` varchar(45) DEFAULT NULL,
  `status` enum('active','banned','inactive') DEFAULT 'active',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `remember_token` (`remember_token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Banks table
CREATE TABLE IF NOT EXISTS `banks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bank_name` varchar(100) NOT NULL,
  `bank_code` varchar(20) NOT NULL,
  `account_name` varchar(100) NOT NULL,
  `account_number` varchar(50) NOT NULL,
  `branch` varchar(200) DEFAULT NULL,
  `min_amount` decimal(15,2) DEFAULT 10000.00,
  `max_amount` decimal(15,2) DEFAULT 50000000.00,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `bank_code` (`bank_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Deposits table
CREATE TABLE IF NOT EXISTS `deposits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `type` enum('card','bank') NOT NULL,
  `status` enum('pending','success','failed','cancelled') DEFAULT 'pending',
  `card_type` varchar(20) DEFAULT NULL,
  `card_code` varchar(50) DEFAULT NULL,
  `card_serial` varchar(50) DEFAULT NULL,
  `bank_id` int(11) DEFAULT NULL,
  `transaction_id` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `status` (`status`),
  KEY `type` (`type`),
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`bank_id`) REFERENCES `banks` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Settings table
CREATE TABLE IF NOT EXISTS `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `value` text DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Password reset tokens table
CREATE TABLE IF NOT EXISTS `password_resets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(100) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `expires_at` timestamp NOT NULL,
  PRIMARY KEY (`id`),
  KEY `email` (`email`),
  KEY `token` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default banks
INSERT INTO `banks` (`bank_name`, `bank_code`, `account_name`, `account_number`, `branch`, `min_amount`, `max_amount`) VALUES
('Vietcombank', 'VCB', 'NGUYEN VAN A', '0011001234567', 'Chi nhánh Hà Nội', 10000.00, 50000000.00),
('Techcombank', 'TCB', 'NGUYEN VAN A', '1903123456789', 'Chi nhánh TP.HCM', 10000.00, 50000000.00),
('Vietinbank', 'CTG', 'NGUYEN VAN A', '71123456789', 'Chi nhánh Đà Nẵng', 10000.00, 50000000.00),
('DongA Bank', 'DAB', 'NGUYEN VAN A', '0123456789', 'Chi nhánh Quận 1', 10000.00, 50000000.00),
('ACB Bank', 'ACB', 'NGUYEN VAN A', '23456789', 'Chi nhánh Bình Thạnh', 10000.00, 50000000.00),
('MB Bank', 'MB', 'NGUYEN VAN A', '880123456789', 'Chi nhánh Cầu Giấy', 10000.00, 50000000.00);

-- Insert default settings
INSERT INTO `settings` (`name`, `value`, `description`) VALUES
('site_name', 'MMO Platform', 'Tên website'),
('site_description', 'Nền tảng MMO uy tín hàng đầu', 'Mô tả website'),
('site_keywords', 'mmo, kiếm tiền online, tài chính', 'Từ khóa SEO'),
('card_discount', '20', 'Chiết khấu thẻ cào (%)'),
('min_card_amount', '10000', 'Mệnh giá thẻ cào tối thiểu'),
('max_card_amount', '500000', 'Mệnh giá thẻ cào tối đa'),
('auto_approve_card', '0', 'Tự động duyệt thẻ cào (0/1)'),
('maintenance', '0', 'Chế độ bảo trì (0/1)');