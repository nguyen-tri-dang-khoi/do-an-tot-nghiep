-- user table
CREATE TABLE `user` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `type` tinyint(4) DEFAULT '0',
 `full_name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT ' ',
 `email` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
 `email_verify_at` datetime DEFAULT '2021-01-22 00:00:00',
 `phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT ' ',
 `phone_verify_at` datetime DEFAULT '2021-01-22 00:00:00',
 `cmnd` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT ' ',
 `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT ' ',
 `birthday` datetime DEFAULT '2021-01-22 00:00:00',
 `img_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT ' ',
 `img_cmnd` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
 `username` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT ' ',
 `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
 `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
 `is_lock` int(11) NOT NULL DEFAULT '0',
 `is_delete` int(11) NOT NULL DEFAULT '0',
 PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=85 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
-- shipper table
CREATE TABLE `shipper` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `full_name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
 `email` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
 `email_verify_at` datetime DEFAULT NULL,
 `phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
 `phone_verify_at` datetime DEFAULT NULL,
 `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
 `cmnd` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
 `birthday` datetime DEFAULT NULL,
 `img_name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
 `username` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
 `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
 `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
 `is_lock` int(11) NOT NULL DEFAULT '0',
 `is_delete` int(11) NOT NULL DEFAULT '0',
 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
-- customer table
CREATE TABLE `customer` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `oauth_provider` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
 `oauth_customer_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
 `full_name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'a',
 `email` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
 `email_token` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
 `email_verify_at` datetime DEFAULT NULL,
 `phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT ' ',
 `phone_token` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
 `phone_verify_at` datetime DEFAULT NULL,
 `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT ' ',
 `birthday` datetime DEFAULT NULL,
 `img_name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'image.jpg',
 `username` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT ' ',
 `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
 `is_lock` int(11) NOT NULL DEFAULT '0',
 `is_delete` int(11) NOT NULL DEFAULT '0',
 `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=110 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
-- policy table
CREATE TABLE `policy` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `title` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
 `content` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
 `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
-- product type table
CREATE TABLE `product_type` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `parent_id` int(11) DEFAULT NULL,
 `name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
 `img_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
 `is_delete` int(11) NOT NULL DEFAULT '0',
 `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
 PRIMARY KEY (`id`),
 KEY `parent_id` (`parent_id`),
 CONSTRAINT `product_type_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `product_type` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=128 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
-- product info table
CREATE TABLE `product_info` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `product_type_id` int(11) NOT NULL,
 `user_id` int(11) NOT NULL,
 `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
 `img_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
 `description` mediumtext CHARACTER SET utf8 COLLATE utf8_unicode_ci,
 `count` int(11) NOT NULL,
 `price` int(11) NOT NULL,
 `is_publish` int(11) NOT NULL,
 `is_delete` int(11) NOT NULL,
 `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
 PRIMARY KEY (`id`),
 KEY `user_id` (`user_id`),
 KEY `product_type_id` (`product_type_id`),
 CONSTRAINT `product_info_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
 CONSTRAINT `product_info_ibfk_2` FOREIGN KEY (`product_type_id`) REFERENCES `product_type` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
-- product rate table
CREATE TABLE `product_rate` (
 `product_info_id` int(11) NOT NULL,
 `customer_id` int(11) NOT NULL,
 `rate` int(10) NOT NULL,
 `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
 PRIMARY KEY (`product_info_id`,`customer_id`),
 KEY `customer_id` (`customer_id`),
 CONSTRAINT `product_rate_ibfk_1` FOREIGN KEY (`product_info_id`) REFERENCES `product_info` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
 CONSTRAINT `product_rate_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
-- product image table
CREATE TABLE `product_image` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `product_info_id` int(11) DEFAULT NULL,
 `img_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
 PRIMARY KEY (`id`),
 KEY `product_info_id` (`product_info_id`),
 CONSTRAINT `product_image_ibfk_1` FOREIGN KEY (`product_info_id`) REFERENCES `product_info` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=77 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
-- product comment table
CREATE TABLE `product_comment` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `customer_id` int(11) NOT NULL,
 `product_info_id` int(11) NOT NULL,
 `comment` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
 `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
 PRIMARY KEY (`id`),
 KEY `product_info_id` (`product_info_id`),
 KEY `customer_id` (`customer_id`),
 CONSTRAINT `product_comment_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
 CONSTRAINT `product_comment_ibfk_2` FOREIGN KEY (`product_info_id`) REFERENCES `product_info` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
-- user comment table
CREATE TABLE `user_comment` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `user_id` int(11) NOT NULL,
 `comment` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
 `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
 PRIMARY KEY (`id`),
 KEY `user_id` (`user_id`),
 CONSTRAINT `user_comment_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
-- customer comment table
CREATE TABLE `customer_comment` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `customer_id` int(11) NOT NULL,
 `comment` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
 `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
 PRIMARY KEY (`id`),
 KEY `customer_id` (`customer_id`),
 CONSTRAINT `customer_comment_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
-- chat table
CREATE TABLE `chat` (
 `customer_comment_id` int(11) NOT NULL AUTO_INCREMENT,
 `user_comment_id` int(11) NOT NULL,
 PRIMARY KEY (`customer_comment_id`,`user_comment_id`),
 KEY `user_comment_id` (`user_comment_id`),
 CONSTRAINT `chat_ibfk_1` FOREIGN KEY (`customer_comment_id`) REFERENCES `customer_comment` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
 CONSTRAINT `chat_ibfk_2` FOREIGN KEY (`user_comment_id`) REFERENCES `user_comment` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
-- notification table
CREATE TABLE `notification` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
 `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
 `img_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
 `link` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
 `is_send` tinyint(4) NOT NULL,
 `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
-- notification customer table
CREATE TABLE `notification_customer` (
 `notify_id` int(11) NOT NULL,
 `customer_id` int(11) NOT NULL,
 PRIMARY KEY (`notify_id`,`customer_id`),
 KEY `customer_id` (`customer_id`),
 CONSTRAINT `notification_customer_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
 CONSTRAINT `notification_customer_ibfk_2` FOREIGN KEY (`notify_id`) REFERENCES `notification` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
-- payment method table
CREATE TABLE `payment_method` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `payment_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
-- delivery status table
CREATE TABLE `delivery_status` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `delivery_status_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
-- orders table
CREATE TABLE `orders` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `customer_id` int(11) DEFAULT NULL,
 `shipper_id` int(11) DEFAULT NULL,
 `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
 `distance` int(11) DEFAULT NULL,
 `total` int(11) DEFAULT NULL,
 `delivery_status_id` int(11) DEFAULT NULL,
 `payment_status` int(11) DEFAULT '0',
 `payment_method_id` int(11) DEFAULT NULL,
 `is_cancel_by_admin` bit(4) NOT NULL DEFAULT b'0',
 `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
 PRIMARY KEY (`id`),
 KEY `payment_method_id` (`payment_method_id`),
 KEY `customer_id` (`customer_id`),
 KEY `shipper_id` (`shipper_id`),
 KEY `delivery_status_id` (`delivery_status_id`),
 CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`payment_method_id`) REFERENCES `payment_method` (`id`),
 CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`),
 CONSTRAINT `orders_ibfk_3` FOREIGN KEY (`shipper_id`) REFERENCES `shipper` (`id`),
 CONSTRAINT `orders_ibfk_4` FOREIGN KEY (`delivery_status_id`) REFERENCES `delivery_status` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
-- order detail table
CREATE TABLE `order_detail` (
 `order_id` int(11) NOT NULL,
 `product_info_id` int(11) NOT NULL,
 `count` int(11) NOT NULL,
 `price` int(11) NOT NULL,
 PRIMARY KEY (`order_id`,`product_info_id`),
 KEY `product_info_id` (`product_info_id`),
 CONSTRAINT `order_detail_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
 CONSTRAINT `order_detail_ibfk_2` FOREIGN KEY (`product_info_id`) REFERENCES `product_info` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
