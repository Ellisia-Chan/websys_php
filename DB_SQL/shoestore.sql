-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 31, 2025 at 02:55 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shoestore`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT 1,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `quantity` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `payment_method` enum('COD','GCash','Credit Card','Shop Voucher') NOT NULL,
  `shipping_address` text NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `product_id`, `full_name`, `email`, `phone`, `quantity`, `total_price`, `payment_method`, `shipping_address`, `order_date`, `status`) VALUES
(23, 16, 40, 'ely', 'ely@ely.com', '1231231313', 1, 104.99, 'GCash', 'san', '2025-03-31 12:26:55', 'Pending'),
(24, 16, 38, 'ely', 'ely@ely.com', '12345678901', 1, 166.99, 'COD', 'asdfasfasf', '2025-03-31 12:36:54', 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `image`, `created_at`) VALUES
(28, 'JA 2 Heart Eyes EP', 'Ja puts his heart into the game he cherishes most. And what he gives, he gets back. This special JA 2 gives you all the feels, taking its cues from Ja\'s favourite Valentine\'s Day sweets that have a hold on the star\'s ticker. Underfoot, Air Zoom cushioning and tractor tyre-inspired traction are combined to help him continue his labour of love. With its extra-durable rubber outsole, this version gives you traction for outdoor courts.\r\n\r\n\r\nColour Shown: Magic Ember/Pink Foam/Deep Night/Iron Grey\r\nStyle: IB5841-800\r\nCountry/Region of Origin: China', 130.00, '1743337990_JA 2 Heart Eyes EP.png', '2025-03-30 12:33:10'),
(29, 'Zoom Vomero 5', 'The Vomero 5 takes early-2000s running to modern heights. A combination of breathable and durable materials stands ready for the rigours of your day, while Zoom Air cushioning delivers a smooth ride.\r\n\r\n\r\nColour Shown: Vast Grey/Black/Sail/Vast Grey\r\nStyle: BV1358-001\r\nCountry/Region of Origin: China, Indonesia', 155.00, '1743338037_Nike Zoom Vomero 5.png', '2025-03-30 12:33:57'),
(30, 'V2K Run', 'Fast-forward. Rewind. Doesn\'t matter—this shoe takes retro into the future. The V2K remasters everything you love about the Vomero in a look pulled straight from an early \'00s running catalogue. Layer up in a mixture of flashy metallics, referential plastic details and a midsole with a perfectly vintage aesthetic. And the chunky heel makes sure wherever you go, it\'s in comfort.\r\n\r\n\r\nColour Shown: Summit White/Pure Platinum/Light Iron Ore/Metallic Silver\r\nStyle: HJ4497-100\r\nCountry/Region of Origin: Vietnam', 120.00, '1743338079_Nike V2K Run.png', '2025-03-30 12:34:39'),
(31, 'Nike Calm', 'Enjoy a calm, comfortable experience—wherever your day off takes you. Made from soft yet supportive foam, the minimal design makes these slides easy to style with or without socks. And they\'ve got a textured footbed to help keep your feet in place.\r\n\r\n\r\nColour Shown: Black/Black\r\nStyle: FD4116-001\r\nCountry/Region of Origin: Indonesia', 45.00, '1743338127_Nike Calm.png', '2025-03-30 12:35:27'),
(32, 'Own the Game 3 Shoes', 'Dominate on the court. Stand out on the street. These adidas basketball shoes are built to go with you everywhere. Mesh and webbing come together for a breathable upper that can handle the demands of the hardwood. Feel energy return with every cut and crossover, thanks to adidas LIGHTMOTION cushioning that\'s responsive, super-light and designed to support your dynamic movements.', 50.00, '1743338221_Own the Game 3 Shoes.png', '2025-03-30 12:37:01'),
(33, 'Basketball Legends Low Shoes', 'When you\'re looking for on-court comfort in a streamlined package, these adidas basketball shoes have got you covered. An innovative Dreamstrike+ midsole provides supportive cushioning without adding weight. While the textile and synthetic upper stands up to the regular wear. A rubber outsole elevates traction on the hardwood to keep you cutting and diving with total confidence.', 67.99, '1743338269_Basketball Legends Low Shoes.png', '2025-03-30 12:37:49'),
(34, 'Adizero SL2 Running Shoes', 'Experience the thrill of breaking personal records in a shoe that\'s as fast as you are. These adidas Adizero running shoes are equipped with responsive Lightstrike Pro, super-light cushioning that delivers energy return for next-level speed. The engineered mesh upper provides breathability and targeted support where you need it most to keep you flying to the finish.\r\n\r\nThis product features at least 20% recycled materials. By reusing materials that have already been created, we help to reduce waste and our reliance on finite resources and reduce the footprint of the products we make.', 113.99, '1743338486_Adizero SL2 Running Shoes.png', '2025-03-30 12:41:26'),
(35, 'Duramo Speed 2 Running Shoes', 'Whether you have a 10k on your calendar or a personal running goal in sight, these adidas running shoes help you train with purpose. They\'re light and fast with responsive Lightstrike cushioning and a TPU outsole that offers traction on the road or track. The engineered mesh upper is breathable and supportive, so you can keep pushing your pace.\r\n\r\nThis product features at least 20% recycled materials. By reusing materials that have already been created, we help to reduce waste and our reliance on finite resources and reduce the footprint of the products we make.', 79.99, '1743338753_Duramo Speed 2 Running Shoes.png', '2025-03-30 12:45:53'),
(36, '9060', 'The 9060 is a new expression of the refined style and innovation-led design of the classic 99X series. The 9060 reinterprets familiar 99X elements with a warped sensibility inspired by the proudly futuristic, visible tech aesthetic of the Y2K era. Sway bars, taken from the 990, are expanded and utilized throughout the entire upper for a sense of visible motion, while wavy lines and scaled up proportions on a sculpted pod midsole place an exaggerated emphasis on the familiar cushioning platforms of ABZORB and SBS.', 149.99, '1743338812_9060.png', '2025-03-30 12:46:52'),
(37, 'Fresh Foam X 880v15', 'This everyday cushioned road runner has a breathable and structured engineered mesh upper for ultimate comfort and all-day wearability.', 139.99, '1743338852_Fresh Foam X 880v14 GORE-TEX.png', '2025-03-30 12:47:32'),
(38, 'Fresh Foam X 1080v14', 'If we only made one running shoe, it would be the Fresh Foam X 1080. The unique combination of reliable comfort and high performance offers versatility that spans from every day to race day. The Fresh Foam X midsole cushioning is built for smooth transitions from landing to push-off, while a soft, premium upper provides support and breathability.\r\n\r\nUpdates to the v14 include a new, triple jacquard mesh upper with increased breathability in key areas, an updated outsole for a propulsive feeling, and additional rubber in high wear areas for added durability.', 166.99, '1743338903_Fresh Foam X 1080v14.png', '2025-03-30 12:48:23'),
(39, 'Prospect Training Shoes', 'The Prospect, a new trainer with attitude. The shoe features full-length PROFOAM for a smooth ride and energy transfer, and the outsole is full-rubber with special zoned areas...\r\n Style: 379476_16\r\n Colour: Desert Dust-Glowing Red-PUMA Black', 74.99, '1743338952_Prospect Training Shoes.png', '2025-03-30 12:49:12'),
(40, 'Electrify NITRO™ 4', 'Electrify NITRO™ 4 seamlessly blends NITROFOAM™ and PROFOAMLITE for a responsive ride with cushioning. With a breathable mesh upper and PUMAGRIP rubber for unbeatable traction...\r\n Style: 310789_01\r\n Colour: PUMA Black-PUMA White', 104.99, '1743338993_Electrify NITRO 4.png', '2025-03-30 12:49:53');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `account_type` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `account_type`) VALUES
(10, 'admin', '123456', 'admin@admin.com', 1),
(16, 'elysia', '123456', 'kamiyon1234@kamiyon.com', 2),
(18, 'Ren', '1234567890', 'ren@ren.com', 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
