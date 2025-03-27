-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th1 12, 2025 lúc 06:13 PM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `fptshop`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cart`
--

CREATE TABLE `cart` (
  `cart_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `parent_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `categories`
--

INSERT INTO `categories` (`id`, `name`, `parent_id`) VALUES
(1, 'iPhone', NULL),
(2, 'Redmi', NULL),
(3, 'Samsung', NULL),
(4, 'Tivi', NULL),
(5, 'Laptop', NULL),
(6, 'Điện thoại', NULL),
(9, 'Apple', 6),
(10, 'Samsung', 6),
(11, 'Xiaomi', 6),
(12, 'OPPO', 6);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `status` enum('pending','completed','cancelled') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `category_id` int(11) NOT NULL,
  `price_vnd` decimal(10,2) DEFAULT NULL,
  `brand` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `image`, `category_id`, `price_vnd`, `brand`) VALUES
(36, 'Laptop Acer Predator Helios Neo ', 'Acer Predator Helios Neo 16 PHN16 72 950P là một lựa chọn tuyệt vời cho những game thủ và người sáng tạo nội dung chuyên nghiệp. Với hiệu năng mạnh mẽ, màn hình sắc nét, thiết kế ấn tượng và kết nối đa dạng, chiếc laptop này sẽ đáp ứng mọi nhu cầu người dùng một cách hoàn hảo. Cùng Thế Giới Máy TÍnh đánh giá chi tiết Acer Predator Helios Neo PHN16 trong phần dưới đây!', 'laptop1.jpg', 5, 10000000.00, ''),
(37, 'Smart Tivi Samsung Neo QLED 8K 85 Inch ', 'Công nghệ nâng cấp hình ảnh 8K AI Upscaling Pro hỗ trợ bởi AI với 512 mạng mô phỏng thần kinh giúp bạn thưởng thức tất cả nội dung 8K sống động. Cùng bộ xử lý AI NQ8 thế hệ 3 mang lại khung hình sắc nét và mượt mà nhất từng có trên TV Samsung.\r\n', 'tivi1.jpg', 1, 12000000.00, ''),
(38, 'Điện thoại Samsung Galaxy A06 6GB/128GB', 'MÀN HÌNH: PLS LCD 6.7\" HD+ CAM SAU: Chính 50 MP & Phụ 2 MP CAM TRƯỚC: 8 MP CHIP: MediaTek Helio G85 RAM - BỘ NHỚ: 4G - 128Gb PIN: 5000mAh 25w MÀU: Bạc Đen Xanh LOẠI HÀNG: New\r\n', 'dienthoai2.jpg', 3, 6000000.00, ''),
(39, 'Samsung Galaxy M55 5G 256GB', 'Không chỉ sở hữu kiểu dáng thanh lịch với ngôn ngữ thiết kế trẻ trung, Samsung Galaxy M55 còn ghi điểm nhờ chip xử lý lõi tám mạnh mẽ, camera 50MP chống rung quang học OIS và hỗ trợ sạc siêu nhanh 45W tốc độ cao. Đây là lựa chọn lý tưởng cho những ai kiếm tìm trải nghiệm cao cấp trên một thiết bị có giá bán không quá cao.', 'Samsung Galaxy M55 5G 256GB.jpg', 3, 8900000.00, '');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `product_images`
--

CREATE TABLE `product_images` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `image_path` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','customer') DEFAULT 'customer',
  `is_admin` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `is_admin`) VALUES
(1, 'anh', 'phanlebakhang73@gmail.com', '$2y$10$WUlNgEPrnuG/GJUoFKoaTeZJ4ZbwtA2u4/eTySuQ7/4jiiXbZed4i', 'admin', 1),
(2, 'khang1', 'phanlebakhang71@gmail.com', '$2y$10$ZQgSTAM2dZfYdLFz7X8x0elSdFdiyLDFauo57M/64Pup5FKikqwzi', 'admin', 1),
(3, '123', 'phanlebakhang78@gmail.com', '$2y$10$28us8UlHTicnsr.0LlHfvO9kOHG9T1amQ6sGFyBts6hafA6mAiHui', 'admin', 1),
(4, '343', 'khang@123', '$2y$10$dTRgc5DwuDmfObMvJq0FqeboOGinT9lJkhcsnb6KSCJc/uN8WXVSa', 'admin', 1),
(5, 'admin', 'admin@123', '$2y$10$WIJrkzTx4RGydBbjXM/3AudBOEe9B9Zc2Aw1fgFKUYUkXfxS93c9G', 'customer', 0),
(6, 'khang', 'phanlebakhang70@gmail.com', '$2y$10$msnIEpipaxNOnmGFBqACw.bqzQwagtImywft96Wabp76hGtd9jiYa', 'admin', 1),
(7, '123', '123@gmail.com', '$2y$10$kgY5QZip2Jc1NOPolFBhNutGAuVfeZc7VeLgC5RzpSkaYvvAcbmSO', 'admin', 1),
(8, 'phanbao16104@gmail.com', 'phanbao16104@gmail.com', '$2y$10$38TpDDRvqxxWq2.58S6es.dgB15VKuHnMQgL00I8BkES18QcWNhZ2', 'admin', 1);

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Chỉ mục cho bảng `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Chỉ mục cho bảng `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Chỉ mục cho bảng `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT cho bảng `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT cho bảng `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=137;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `product_images_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
