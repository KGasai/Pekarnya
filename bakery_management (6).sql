-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Апр 13 2025 г., 20:01
-- Версия сервера: 8.0.30
-- Версия PHP: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `bakery_management`
--

-- --------------------------------------------------------

--
-- Структура таблицы `Clients`
--

CREATE TABLE `Clients` (
  `client_id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `inn` varchar(20) NOT NULL,
  `address` text NOT NULL,
  `phone` varchar(20) NOT NULL,
  `contact_person` varchar(100) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `user_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `Clients`
--

INSERT INTO `Clients` (`client_id`, `name`, `inn`, `address`, `phone`, `contact_person`, `is_active`, `user_id`) VALUES
(2, 'Магазин \'test\'', '12314', 'Новочеркасск,', '89964388701', 'Савелий Бульдаг', 1, 1),
(3, 'Bakery Shop 1', '1234567890', '123 Main St, City', '+79151234567', 'John Doe', 1, 4),
(4, 'Supermarket Chain', '2345678901', '456 Market Ave, Town', '+79161234567', 'Jane Smith', 1, 4),
(5, 'test1', '13214', 'РОСТОВСКАЯ ОБЛ, Г НОВОЧЕРКАССК, П. ПЛАТВОСКИЙ, Д. 116', '+79964388701', 'Савелий Буль', 1, 8);

-- --------------------------------------------------------

--
-- Структура таблицы `Contracts`
--

CREATE TABLE `Contracts` (
  `contract_id` int NOT NULL,
  `client_id` int NOT NULL,
  `contract_number` varchar(50) NOT NULL,
  `contract_date` date NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `delivery_terms` text,
  `payment_terms` text,
  `is_active` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `Contracts`
--

INSERT INTO `Contracts` (`contract_id`, `client_id`, `contract_number`, `contract_date`, `start_date`, `end_date`, `delivery_terms`, `payment_terms`, `is_active`) VALUES
(1, 1, 'Б-234', '2025-04-20', '2025-04-20', '2025-05-13', 'Каждое утро', 'Безналичка', 1),
(2, 1, 'CON-001', '2025-01-01', '2025-01-01', '2025-12-31', 'Daily delivery before 9 AM', 'Безналичка', 1),
(3, 2, 'CON-002', '2025-02-01', '2025-02-01', '2025-12-31', '3 times per week', 'Безналичка', 1),
(4, 8, 'В-333', '2025-04-20', '2025-04-20', '2025-05-15', 'Курьер', 'Наличный', 1),
(5, 8, 'Ш-1234', '2025-04-20', '2025-04-20', '2025-04-20', 'Самовывоз', 'Карта', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `DeliveryRoutes`
--

CREATE TABLE `DeliveryRoutes` (
  `route_id` int NOT NULL,
  `vehicle_id` int NOT NULL,
  `client_id` int NOT NULL,
  `delivery_order` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `DeliveryRoutes`
--

INSERT INTO `DeliveryRoutes` (`route_id`, `vehicle_id`, `client_id`, `delivery_order`) VALUES
(9, 1, 3, 1),
(10, 1, 4, 2),
(11, 2, 4, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `DeliveryVehicles`
--

CREATE TABLE `DeliveryVehicles` (
  `vehicle_id` int NOT NULL,
  `license_plate` varchar(20) NOT NULL,
  `model` varchar(50) NOT NULL,
  `capacity` decimal(10,2) NOT NULL,
  `is_active` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `DeliveryVehicles`
--

INSERT INTO `DeliveryVehicles` (`vehicle_id`, `license_plate`, `model`, `capacity`, `is_active`) VALUES
(1, 'A123BC77', 'Ford Transit', '2000.00', 1),
(2, 'B456DE77', 'Mercedes Sprinter', '2500.00', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `IngredientConsumptionItems`
--

CREATE TABLE `IngredientConsumptionItems` (
  `consumption_item_id` int NOT NULL,
  `consumption_id` int NOT NULL,
  `ingredient_id` int NOT NULL,
  `quantity` decimal(10,3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Триггеры `IngredientConsumptionItems`
--
DELIMITER $$
CREATE TRIGGER `after_ingredient_consumption_insert` AFTER INSERT ON `IngredientConsumptionItems` FOR EACH ROW BEGIN
    UPDATE Ingredients 
    SET current_stock = current_stock - NEW.quantity
    WHERE ingredient_id = NEW.ingredient_id;
    
    INSERT INTO IngredientStockLog (date, ingredient_id, outgoing, balance)
    VALUES (CURDATE(), NEW.ingredient_id, NEW.quantity, 
           (SELECT current_stock FROM Ingredients WHERE ingredient_id = NEW.ingredient_id))
    ON DUPLICATE KEY UPDATE 
        outgoing = outgoing + NEW.quantity,
        balance = balance - NEW.quantity;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Структура таблицы `IngredientConsumptions`
--

CREATE TABLE `IngredientConsumptions` (
  `consumption_id` int NOT NULL,
  `document_number` varchar(50) NOT NULL,
  `date` date NOT NULL,
  `task_id` int DEFAULT NULL,
  `issued_by` int NOT NULL,
  `issued_to` varchar(100) NOT NULL,
  `notes` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `IngredientPurchaseRequests`
--

CREATE TABLE `IngredientPurchaseRequests` (
  `request_id` int NOT NULL,
  `date` date NOT NULL,
  `ingredient_id` int NOT NULL,
  `required_quantity` decimal(10,3) NOT NULL,
  `requested_by` int NOT NULL,
  `status` enum('pending','approved','rejected','completed') DEFAULT 'pending',
  `approved_by` int DEFAULT NULL,
  `approved_at` datetime DEFAULT NULL,
  `completed_date` date DEFAULT NULL,
  `notes` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `IngredientReceiptItems`
--

CREATE TABLE `IngredientReceiptItems` (
  `receipt_item_id` int NOT NULL,
  `receipt_id` int NOT NULL,
  `ingredient_id` int NOT NULL,
  `quantity` decimal(10,3) NOT NULL,
  `cost_per_unit` decimal(10,2) NOT NULL,
  `total_cost` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `IngredientReceiptItems`
--

INSERT INTO `IngredientReceiptItems` (`receipt_item_id`, `receipt_id`, `ingredient_id`, `quantity`, `cost_per_unit`, `total_cost`) VALUES
(1, 1, 1, '100.000', '50.00', '5000.00'),
(2, 2, 2, '30.000', '60.00', '1800.00'),
(3, 2, 3, '4.000', '300.00', '1200.00');

--
-- Триггеры `IngredientReceiptItems`
--
DELIMITER $$
CREATE TRIGGER `after_ingredient_receipt_insert` AFTER INSERT ON `IngredientReceiptItems` FOR EACH ROW BEGIN
    UPDATE Ingredients 
    SET current_stock = current_stock + NEW.quantity
    WHERE ingredient_id = NEW.ingredient_id;
    
    INSERT INTO IngredientStockLog (date, ingredient_id, incoming, balance)
    VALUES (CURDATE(), NEW.ingredient_id, NEW.quantity, 
           (SELECT current_stock FROM Ingredients WHERE ingredient_id = NEW.ingredient_id))
    ON DUPLICATE KEY UPDATE 
        incoming = incoming + NEW.quantity,
        balance = balance + NEW.quantity;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Структура таблицы `IngredientReceipts`
--

CREATE TABLE `IngredientReceipts` (
  `receipt_id` int NOT NULL,
  `document_number` varchar(50) NOT NULL,
  `date` date NOT NULL,
  `supplier_id` int DEFAULT NULL,
  `total_cost` decimal(12,2) NOT NULL,
  `received_by` int DEFAULT NULL,
  `notes` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `IngredientReceipts`
--

INSERT INTO `IngredientReceipts` (`receipt_id`, `document_number`, `date`, `supplier_id`, `total_cost`, `received_by`, `notes`) VALUES
(1, 'REC-001', '2025-04-10', NULL, '5000.00', 3, 'Monthly flour delivery'),
(2, 'REC-002', '2025-04-11', NULL, '3000.00', 3, 'Sugar and butter delivery');

-- --------------------------------------------------------

--
-- Структура таблицы `Ingredients`
--

CREATE TABLE `Ingredients` (
  `ingredient_id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `unit_of_measure` varchar(20) NOT NULL,
  `current_stock` decimal(10,3) NOT NULL DEFAULT '0.000',
  `min_stock` decimal(10,3) NOT NULL DEFAULT '0.000',
  `cost_per_unit` decimal(10,2) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `Ingredients`
--

INSERT INTO `Ingredients` (`ingredient_id`, `name`, `unit_of_measure`, `current_stock`, `min_stock`, `cost_per_unit`, `is_active`) VALUES
(1, 'Wheat Flour', 'kg', '600.000', '100.000', '50.00', 1),
(2, 'Sugar', 'kg', '230.000', '50.000', '60.00', 1),
(3, 'Butter', 'kg', '104.000', '20.000', '300.00', 1);

--
-- Триггеры `Ingredients`
--
DELIMITER $$
CREATE TRIGGER `after_ingredient_stock_update` AFTER UPDATE ON `Ingredients` FOR EACH ROW BEGIN
    IF NEW.current_stock < NEW.min_stock AND OLD.current_stock >= OLD.min_stock THEN
        INSERT INTO IngredientPurchaseRequests (date, ingredient_id, required_quantity, requested_by, status)
        VALUES (CURDATE(), NEW.ingredient_id, NEW.min_stock * 2 - NEW.current_stock, 1, 'pending');
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Структура таблицы `IngredientStockLog`
--

CREATE TABLE `IngredientStockLog` (
  `log_id` int NOT NULL,
  `date` date NOT NULL,
  `ingredient_id` int NOT NULL,
  `incoming` decimal(10,3) DEFAULT '0.000',
  `outgoing` decimal(10,3) DEFAULT '0.000',
  `balance` decimal(10,3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `IngredientStockLog`
--

INSERT INTO `IngredientStockLog` (`log_id`, `date`, `ingredient_id`, `incoming`, `outgoing`, `balance`) VALUES
(1, '2025-04-13', 1, '100.000', '0.000', '600.000'),
(2, '2025-04-13', 2, '30.000', '0.000', '230.000'),
(3, '2025-04-13', 3, '4.000', '0.000', '104.000');

-- --------------------------------------------------------

--
-- Структура таблицы `OrderItems`
--

CREATE TABLE `OrderItems` (
  `order_item_id` int NOT NULL,
  `order_id` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` decimal(10,3) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `OrderItems`
--

INSERT INTO `OrderItems` (`order_item_id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(1, 1, 1, '20.000', '45.00'),
(2, 1, 2, '15.000', '55.00'),
(3, 2, 3, '30.000', '75.00'),
(4, 2, 4, '5.000', '320.00'),
(5, 3, 2, '10.000', '550.00'),
(6, 4, 3, '12.000', '900.00');

-- --------------------------------------------------------

--
-- Структура таблицы `Orders`
--

CREATE TABLE `Orders` (
  `order_id` int NOT NULL,
  `client_id` int NOT NULL,
  `contract_id` int NOT NULL,
  `order_date` varchar(200) NOT NULL,
  `status` enum('new','in_progress','completed','canceled') DEFAULT 'new',
  `notes` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `Orders`
--

INSERT INTO `Orders` (`order_id`, `client_id`, `contract_id`, `order_date`, `status`, `notes`) VALUES
(1, 1, 1, '2025-04-15', 'new', 'Regular weekly order'),
(2, 2, 2, '2025-04-16', 'in_progress', 'Special event order'),
(3, 8, 4, '', 'new', ''),
(4, 8, 5, '2025-04-20', 'completed', '');

-- --------------------------------------------------------

--
-- Структура таблицы `ProductCategories`
--

CREATE TABLE `ProductCategories` (
  `category_id` int NOT NULL,
  `name_cat` varchar(50) NOT NULL,
  `description` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `ProductCategories`
--

INSERT INTO `ProductCategories` (`category_id`, `name_cat`, `description`) VALUES
(1, 'Bread', 'Various types of bread'),
(2, 'Pastries', 'Sweet baked goods'),
(3, 'Pies', 'Various pies with fillings');

-- --------------------------------------------------------

--
-- Структура таблицы `ProductionTaskItems`
--

CREATE TABLE `ProductionTaskItems` (
  `task_item_id` int NOT NULL,
  `task_id` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` decimal(10,3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `ProductionTaskItems`
--

INSERT INTO `ProductionTaskItems` (`task_item_id`, `task_id`, `product_id`, `quantity`) VALUES
(1, 1, 1, '20.000'),
(2, 1, 2, '15.000'),
(3, 2, 3, '30.000'),
(4, 2, 4, '5.000');

-- --------------------------------------------------------

--
-- Структура таблицы `ProductionTasks`
--

CREATE TABLE `ProductionTasks` (
  `task_id` int NOT NULL,
  `task_date` date NOT NULL,
  `shift` enum('morning','evening','night') NOT NULL,
  `status` enum('planned','in_progress','completed') DEFAULT 'planned',
  `created_by` int NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `ProductionTasks`
--

INSERT INTO `ProductionTasks` (`task_id`, `task_date`, `shift`, `status`, `created_by`, `created_at`) VALUES
(1, '2025-04-15', 'morning', 'planned', 2, '2025-04-13 12:10:08'),
(2, '2025-04-15', 'evening', 'planned', 2, '2025-04-13 12:10:08');

-- --------------------------------------------------------

--
-- Структура таблицы `Products`
--

CREATE TABLE `Products` (
  `product_id` int NOT NULL,
  `category_id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `unit_of_measure` varchar(20) NOT NULL,
  `weight` decimal(10,3) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `is_active` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `Products`
--

INSERT INTO `Products` (`product_id`, `category_id`, `name`, `unit_of_measure`, `weight`, `price`, `is_active`) VALUES
(1, 1, 'Белый хлеб', 'кусок', '0.500', '45.00', 1),
(2, 1, 'Хлеб из цельнозерновой муки', 'кусок', '0.600', '55.00', 1),
(3, 2, 'Круассан', 'кусок', '0.150', '75.00', 1),
(4, 3, 'Яблочный пирог', 'кусок', '1.000', '320.00', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `Recipes`
--

CREATE TABLE `Recipes` (
  `recipe_id` int NOT NULL,
  `product_id` int NOT NULL,
  `ingredient_id` int NOT NULL,
  `quantity` decimal(10,3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `Recipes`
--

INSERT INTO `Recipes` (`recipe_id`, `product_id`, `ingredient_id`, `quantity`) VALUES
(1, 1, 1, '0.300'),
(2, 1, 2, '0.010'),
(3, 2, 1, '0.350'),
(4, 2, 2, '0.015'),
(5, 3, 1, '0.200'),
(6, 3, 3, '0.100');

-- --------------------------------------------------------

--
-- Структура таблицы `Users`
--

CREATE TABLE `Users` (
  `user_id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `role` enum('technologist','storekeeper','expeditor','director','client','driver') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'client',
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `Users`
--

INSERT INTO `Users` (`user_id`, `username`, `password`, `full_name`, `role`, `phone`, `email`, `is_active`) VALUES
(1, 'test', 'test', 'Магазин \"test\"', 'client', '+79964388701', 'test@gmail.com', 1),
(2, 'owner', 'owner', 'owner', 'director', '+79101234567', 'admin@bakery.com', 1),
(3, 'technologist', 'technologist', 'technologist', 'technologist', '+79111234567', 'techno@bakery.com', 1),
(4, 'storekeeper', 'storekeeper', 'storekeeper', 'storekeeper', '+79121234567', 'store@bakery.com', 1),
(5, 'expeditor', 'expeditor', 'expeditor', 'expeditor', '+79131234567', 'client1@shop.com', 1),
(6, 'Ivan', 'Ivan', 'Иван Иваныч', 'driver', '+79141234567', 'driver@bakery.com', 1),
(8, 'test1', 'test1', 'test', 'client', '+79964388701', 'test@yandex.ru', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `WaybillItems`
--

CREATE TABLE `WaybillItems` (
  `waybill_item_id` int NOT NULL,
  `waybill_id` int NOT NULL,
  `order_id` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` decimal(10,3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `WaybillItems`
--

INSERT INTO `WaybillItems` (`waybill_item_id`, `waybill_id`, `order_id`, `product_id`, `quantity`) VALUES
(1, 3, 4, 3, '12.000'),
(2, 4, 4, 3, '11.951'),
(3, 5, 4, 3, '11.958'),
(4, 6, 4, 3, '5.000');

-- --------------------------------------------------------

--
-- Структура таблицы `Waybills`
--

CREATE TABLE `Waybills` (
  `waybill_id` int NOT NULL,
  `waybill_number` varchar(50) NOT NULL,
  `date` date NOT NULL,
  `vehicle_id` int NOT NULL,
  `driver_id` int NOT NULL,
  `expeditor_id` int NOT NULL,
  `departure_time` datetime DEFAULT NULL,
  `return_time` datetime DEFAULT NULL,
  `status` enum('planned','in_progress','completed') DEFAULT 'planned'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `Waybills`
--

INSERT INTO `Waybills` (`waybill_id`, `waybill_number`, `date`, `vehicle_id`, `driver_id`, `expeditor_id`, `departure_time`, `return_time`, `status`) VALUES
(1, 'WB-001', '2025-04-15', 1, 5, 3, NULL, NULL, 'planned'),
(2, 'WB-002', '2025-04-16', 2, 5, 3, NULL, NULL, 'planned'),
(3, 'WB-20250413-0001', '2025-04-13', 1, 6, 5, NULL, NULL, 'planned'),
(4, 'WB-20250413-0002', '2025-04-13', 1, 6, 5, NULL, NULL, 'planned'),
(5, 'WB-20250413-0003', '2025-04-13', 1, 6, 5, NULL, NULL, 'planned'),
(6, 'WB-20250413-0004', '2025-04-13', 2, 6, 5, NULL, NULL, 'planned');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `Clients`
--
ALTER TABLE `Clients`
  ADD PRIMARY KEY (`client_id`),
  ADD UNIQUE KEY `inn` (`inn`),
  ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `Contracts`
--
ALTER TABLE `Contracts`
  ADD PRIMARY KEY (`contract_id`),
  ADD UNIQUE KEY `contract_number` (`contract_number`,`contract_date`),
  ADD KEY `client_id` (`client_id`);

--
-- Индексы таблицы `DeliveryRoutes`
--
ALTER TABLE `DeliveryRoutes`
  ADD PRIMARY KEY (`route_id`),
  ADD UNIQUE KEY `vehicle_id` (`vehicle_id`,`client_id`),
  ADD KEY `client_id` (`client_id`);

--
-- Индексы таблицы `DeliveryVehicles`
--
ALTER TABLE `DeliveryVehicles`
  ADD PRIMARY KEY (`vehicle_id`),
  ADD UNIQUE KEY `license_plate` (`license_plate`);

--
-- Индексы таблицы `IngredientConsumptionItems`
--
ALTER TABLE `IngredientConsumptionItems`
  ADD PRIMARY KEY (`consumption_item_id`),
  ADD KEY `consumption_id` (`consumption_id`),
  ADD KEY `ingredient_id` (`ingredient_id`);

--
-- Индексы таблицы `IngredientConsumptions`
--
ALTER TABLE `IngredientConsumptions`
  ADD PRIMARY KEY (`consumption_id`),
  ADD KEY `task_id` (`task_id`),
  ADD KEY `issued_by` (`issued_by`);

--
-- Индексы таблицы `IngredientPurchaseRequests`
--
ALTER TABLE `IngredientPurchaseRequests`
  ADD PRIMARY KEY (`request_id`),
  ADD KEY `ingredient_id` (`ingredient_id`),
  ADD KEY `requested_by` (`requested_by`),
  ADD KEY `approved_by` (`approved_by`);

--
-- Индексы таблицы `IngredientReceiptItems`
--
ALTER TABLE `IngredientReceiptItems`
  ADD PRIMARY KEY (`receipt_item_id`),
  ADD KEY `receipt_id` (`receipt_id`),
  ADD KEY `ingredient_id` (`ingredient_id`);

--
-- Индексы таблицы `IngredientReceipts`
--
ALTER TABLE `IngredientReceipts`
  ADD PRIMARY KEY (`receipt_id`),
  ADD KEY `received_by` (`received_by`);

--
-- Индексы таблицы `Ingredients`
--
ALTER TABLE `Ingredients`
  ADD PRIMARY KEY (`ingredient_id`);

--
-- Индексы таблицы `IngredientStockLog`
--
ALTER TABLE `IngredientStockLog`
  ADD PRIMARY KEY (`log_id`),
  ADD UNIQUE KEY `date` (`date`,`ingredient_id`),
  ADD KEY `ingredient_id` (`ingredient_id`),
  ADD KEY `idx_ingredient_stock_log_date_ingredient` (`date`,`ingredient_id`);

--
-- Индексы таблицы `OrderItems`
--
ALTER TABLE `OrderItems`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `idx_order_items_order_product` (`order_id`,`product_id`);

--
-- Индексы таблицы `Orders`
--
ALTER TABLE `Orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `contract_id` (`contract_id`),
  ADD KEY `idx_orders_client_date` (`client_id`,`order_date`(191));

--
-- Индексы таблицы `ProductCategories`
--
ALTER TABLE `ProductCategories`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `name` (`name_cat`);

--
-- Индексы таблицы `ProductionTaskItems`
--
ALTER TABLE `ProductionTaskItems`
  ADD PRIMARY KEY (`task_item_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `idx_production_task_items_task_product` (`task_id`,`product_id`);

--
-- Индексы таблицы `ProductionTasks`
--
ALTER TABLE `ProductionTasks`
  ADD PRIMARY KEY (`task_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Индексы таблицы `Products`
--
ALTER TABLE `Products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Индексы таблицы `Recipes`
--
ALTER TABLE `Recipes`
  ADD PRIMARY KEY (`recipe_id`),
  ADD UNIQUE KEY `product_id` (`product_id`,`ingredient_id`),
  ADD KEY `ingredient_id` (`ingredient_id`),
  ADD KEY `idx_recipes_product_ingredient` (`product_id`,`ingredient_id`);

--
-- Индексы таблицы `Users`
--
ALTER TABLE `Users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Индексы таблицы `WaybillItems`
--
ALTER TABLE `WaybillItems`
  ADD PRIMARY KEY (`waybill_item_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `idx_waybill_items_waybill_order` (`waybill_id`,`order_id`);

--
-- Индексы таблицы `Waybills`
--
ALTER TABLE `Waybills`
  ADD PRIMARY KEY (`waybill_id`),
  ADD KEY `vehicle_id` (`vehicle_id`),
  ADD KEY `driver_id` (`driver_id`),
  ADD KEY `expeditor_id` (`expeditor_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `Clients`
--
ALTER TABLE `Clients`
  MODIFY `client_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `Contracts`
--
ALTER TABLE `Contracts`
  MODIFY `contract_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `DeliveryRoutes`
--
ALTER TABLE `DeliveryRoutes`
  MODIFY `route_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT для таблицы `DeliveryVehicles`
--
ALTER TABLE `DeliveryVehicles`
  MODIFY `vehicle_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `IngredientConsumptionItems`
--
ALTER TABLE `IngredientConsumptionItems`
  MODIFY `consumption_item_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `IngredientConsumptions`
--
ALTER TABLE `IngredientConsumptions`
  MODIFY `consumption_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `IngredientPurchaseRequests`
--
ALTER TABLE `IngredientPurchaseRequests`
  MODIFY `request_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `IngredientReceiptItems`
--
ALTER TABLE `IngredientReceiptItems`
  MODIFY `receipt_item_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `IngredientReceipts`
--
ALTER TABLE `IngredientReceipts`
  MODIFY `receipt_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `Ingredients`
--
ALTER TABLE `Ingredients`
  MODIFY `ingredient_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `IngredientStockLog`
--
ALTER TABLE `IngredientStockLog`
  MODIFY `log_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `OrderItems`
--
ALTER TABLE `OrderItems`
  MODIFY `order_item_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `Orders`
--
ALTER TABLE `Orders`
  MODIFY `order_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `ProductCategories`
--
ALTER TABLE `ProductCategories`
  MODIFY `category_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `ProductionTaskItems`
--
ALTER TABLE `ProductionTaskItems`
  MODIFY `task_item_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `ProductionTasks`
--
ALTER TABLE `ProductionTasks`
  MODIFY `task_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `Products`
--
ALTER TABLE `Products`
  MODIFY `product_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `Recipes`
--
ALTER TABLE `Recipes`
  MODIFY `recipe_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `Users`
--
ALTER TABLE `Users`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT для таблицы `WaybillItems`
--
ALTER TABLE `WaybillItems`
  MODIFY `waybill_item_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `Waybills`
--
ALTER TABLE `Waybills`
  MODIFY `waybill_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `Clients`
--
ALTER TABLE `Clients`
  ADD CONSTRAINT `clients_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `Users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `Contracts`
--
ALTER TABLE `Contracts`
  ADD CONSTRAINT `contracts_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `Users` (`user_id`);

--
-- Ограничения внешнего ключа таблицы `DeliveryRoutes`
--
ALTER TABLE `DeliveryRoutes`
  ADD CONSTRAINT `deliveryroutes_ibfk_1` FOREIGN KEY (`vehicle_id`) REFERENCES `DeliveryVehicles` (`vehicle_id`),
  ADD CONSTRAINT `deliveryroutes_ibfk_2` FOREIGN KEY (`client_id`) REFERENCES `Clients` (`client_id`);

--
-- Ограничения внешнего ключа таблицы `IngredientConsumptionItems`
--
ALTER TABLE `IngredientConsumptionItems`
  ADD CONSTRAINT `ingredientconsumptionitems_ibfk_1` FOREIGN KEY (`consumption_id`) REFERENCES `IngredientConsumptions` (`consumption_id`),
  ADD CONSTRAINT `ingredientconsumptionitems_ibfk_2` FOREIGN KEY (`ingredient_id`) REFERENCES `Ingredients` (`ingredient_id`);

--
-- Ограничения внешнего ключа таблицы `IngredientConsumptions`
--
ALTER TABLE `IngredientConsumptions`
  ADD CONSTRAINT `ingredientconsumptions_ibfk_1` FOREIGN KEY (`task_id`) REFERENCES `ProductionTasks` (`task_id`),
  ADD CONSTRAINT `ingredientconsumptions_ibfk_2` FOREIGN KEY (`issued_by`) REFERENCES `Users` (`user_id`);

--
-- Ограничения внешнего ключа таблицы `IngredientPurchaseRequests`
--
ALTER TABLE `IngredientPurchaseRequests`
  ADD CONSTRAINT `ingredientpurchaserequests_ibfk_1` FOREIGN KEY (`ingredient_id`) REFERENCES `Ingredients` (`ingredient_id`),
  ADD CONSTRAINT `ingredientpurchaserequests_ibfk_2` FOREIGN KEY (`requested_by`) REFERENCES `Users` (`user_id`),
  ADD CONSTRAINT `ingredientpurchaserequests_ibfk_3` FOREIGN KEY (`approved_by`) REFERENCES `Users` (`user_id`);

--
-- Ограничения внешнего ключа таблицы `IngredientReceiptItems`
--
ALTER TABLE `IngredientReceiptItems`
  ADD CONSTRAINT `ingredientreceiptitems_ibfk_1` FOREIGN KEY (`receipt_id`) REFERENCES `IngredientReceipts` (`receipt_id`),
  ADD CONSTRAINT `ingredientreceiptitems_ibfk_2` FOREIGN KEY (`ingredient_id`) REFERENCES `Ingredients` (`ingredient_id`);

--
-- Ограничения внешнего ключа таблицы `IngredientReceipts`
--
ALTER TABLE `IngredientReceipts`
  ADD CONSTRAINT `ingredientreceipts_ibfk_1` FOREIGN KEY (`received_by`) REFERENCES `Users` (`user_id`);

--
-- Ограничения внешнего ключа таблицы `IngredientStockLog`
--
ALTER TABLE `IngredientStockLog`
  ADD CONSTRAINT `ingredientstocklog_ibfk_1` FOREIGN KEY (`ingredient_id`) REFERENCES `Ingredients` (`ingredient_id`);

--
-- Ограничения внешнего ключа таблицы `OrderItems`
--
ALTER TABLE `OrderItems`
  ADD CONSTRAINT `orderitems_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `Orders` (`order_id`),
  ADD CONSTRAINT `orderitems_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `Products` (`product_id`);

--
-- Ограничения внешнего ключа таблицы `Orders`
--
ALTER TABLE `Orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `Users` (`user_id`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`contract_id`) REFERENCES `Contracts` (`contract_id`);

--
-- Ограничения внешнего ключа таблицы `ProductionTaskItems`
--
ALTER TABLE `ProductionTaskItems`
  ADD CONSTRAINT `productiontaskitems_ibfk_1` FOREIGN KEY (`task_id`) REFERENCES `ProductionTasks` (`task_id`),
  ADD CONSTRAINT `productiontaskitems_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `Products` (`product_id`);

--
-- Ограничения внешнего ключа таблицы `ProductionTasks`
--
ALTER TABLE `ProductionTasks`
  ADD CONSTRAINT `productiontasks_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `Users` (`user_id`);

--
-- Ограничения внешнего ключа таблицы `Products`
--
ALTER TABLE `Products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `ProductCategories` (`category_id`);

--
-- Ограничения внешнего ключа таблицы `Recipes`
--
ALTER TABLE `Recipes`
  ADD CONSTRAINT `recipes_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `Products` (`product_id`),
  ADD CONSTRAINT `recipes_ibfk_2` FOREIGN KEY (`ingredient_id`) REFERENCES `Ingredients` (`ingredient_id`);

--
-- Ограничения внешнего ключа таблицы `WaybillItems`
--
ALTER TABLE `WaybillItems`
  ADD CONSTRAINT `waybillitems_ibfk_1` FOREIGN KEY (`waybill_id`) REFERENCES `Waybills` (`waybill_id`),
  ADD CONSTRAINT `waybillitems_ibfk_2` FOREIGN KEY (`order_id`) REFERENCES `Orders` (`order_id`),
  ADD CONSTRAINT `waybillitems_ibfk_3` FOREIGN KEY (`product_id`) REFERENCES `Products` (`product_id`);

--
-- Ограничения внешнего ключа таблицы `Waybills`
--
ALTER TABLE `Waybills`
  ADD CONSTRAINT `waybills_ibfk_1` FOREIGN KEY (`vehicle_id`) REFERENCES `DeliveryVehicles` (`vehicle_id`),
  ADD CONSTRAINT `waybills_ibfk_2` FOREIGN KEY (`driver_id`) REFERENCES `Users` (`user_id`),
  ADD CONSTRAINT `waybills_ibfk_3` FOREIGN KEY (`expeditor_id`) REFERENCES `Users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
