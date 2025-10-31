-- Database Schema for MMO Project
-- Created by Senior Developer (20 years experience)
-- Fixed version with correct table order

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

-- Dots (Domain Extensions) table - MUST be created before website_orders
CREATE TABLE IF NOT EXISTS `dots` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `extension` varchar(10) NOT NULL,
  `name` varchar(50) NOT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `renewal_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `description` text DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `extension` (`extension`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Website Templates table - MUST be created before website_orders
CREATE TABLE IF NOT EXISTS `website_templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `demo_url` varchar(255) DEFAULT NULL,
  `thumbnail` varchar(255) DEFAULT NULL,
  `features` text DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `category` (`category`),
  KEY `status` (`status`)
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

-- Website Orders table - NOW can reference dots and website_templates
CREATE TABLE IF NOT EXISTS `website_orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `template_id` int(11) NOT NULL,
  `domain` varchar(255) DEFAULT NULL,
  `dot_id` int(11) DEFAULT NULL,
  `package_type` enum('monthly','quarterly','yearly') DEFAULT 'monthly',
  `price` decimal(10,2) NOT NULL,
  `status` enum('pending','active','expired','cancelled') DEFAULT 'pending',
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `template_id` (`template_id`),
  KEY `dot_id` (`dot_id`),
  KEY `status` (`status`),
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`template_id`) REFERENCES `website_templates` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`dot_id`) REFERENCES `dots` (`id`) ON DELETE SET NULL
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

-- Insert sample domain extensions
INSERT INTO `dots` (`extension`, `name`, `price`, `renewal_price`, `description`) VALUES
('.com', 'Commercial Domain', 250000.00, 280000.00, 'Phổ biến nhất, phù hợp cho mọi loại hình kinh doanh'),
('.net', 'Network Domain', 220000.00, 250000.00, 'Phù hợp cho công ty công nghệ, mạng'),
('.org', 'Organization Domain', 200000.00, 230000.00, 'Phù hợp cho tổ chức phi lợi nhuận'),
('.vn', 'Vietnam Domain', 650000.00, 680000.00, 'Tên miền quốc gia Việt Nam, tăng uy tín tại Việt Nam'),
('.com.vn', 'Vietnam Commercial', 450000.00, 480000.00, 'Tên miền thương mại Việt Nam'),
('.info', 'Information Domain', 180000.00, 210000.00, 'Phù hợp cho trang thông tin, tin tức'),
('.biz', 'Business Domain', 190000.00, 220000.00, 'Phù hợp cho doanh nghiệp nhỏ và vừa'),
('.co', 'Company Domain', 350000.00, 380000.00, 'Ngắn gọn, hiện đại, phù hợp cho startup');

-- Insert sample website templates
INSERT INTO `website_templates` (`name`, `slug`, `description`, `category`, `price`, `demo_url`, `thumbnail`, `features`) VALUES
('E-commerce Pro', 'ecommerce-pro', 'Giao diện thương mại điện tử chuyên nghiệp với đầy đủ tính năng', 'ecommerce', 299000.00, 'https://demo.example.com/ecommerce', 'images/templates/ecommerce-pro.jpg', '["Cart", "Payment", "Admin Panel", "Multi-language", "SEO Optimized"]'),
('Business Corporate', 'business-corporate', 'Giao diện công ty doanh nghiệp sang trọng và chuyên nghiệp', 'business', 199000.00, 'https://demo.example.com/business', 'images/templates/business-corporate.jpg', '["About Us", "Services", "Portfolio", "Contact", "Blog"]'),
('News Portal', 'news-portal', 'Giao diện trang tin tức, báo điện tử hiện đại', 'news', 249000.00, 'https://demo.example.com/news', 'images/templates/news-portal.jpg', '["Article Management", "Categories", "Comments", "Search", "RSS Feed"]'),
('Restaurant & Food', 'restaurant-food', 'Giao diện nhà hàng, quán ăn, delivery food', 'food', 179000.00, 'https://demo.example.com/restaurant', 'images/templates/restaurant-food.jpg', '["Menu", "Online Ordering", "Reservation", "Gallery", "Reviews"]'),
('Education Online', 'education-online', 'Giao diện trang giáo dục, học trực tuyến', 'education', 329000.00, 'https://demo.example.com/education', 'images/templates/education-online.jpg', '["Courses", "Lessons", "Quizzes", "Certificates", "Student Dashboard"]'),
('Portfolio Creative', 'portfolio-creative', 'Giao diện portfolio cá nhân, agency sáng tạo', 'portfolio', 149000.00, 'https://demo.example.com/portfolio', 'images/templates/portfolio-creative.jpg', '["Projects", "Gallery", "Testimonials", "Contact Form", "Blog"]');