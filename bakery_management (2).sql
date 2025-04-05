-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Апр 03 2025 г., 21:01
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
(1, 5, 'Д-001', '2023-01-15', '2023-01-20', '2024-01-19', 'Доставка ежедневно до 10:00', 'Оплата по факту поставки', 1),
(2, 6, 'Д-002', '2023-02-01', '2023-02-05', '2024-02-04', 'Доставка 3 раза в неделю', 'Оплата по безналу с отсрочкой 7 дней', 1),
(3, 7, 'Д-003', '2023-03-10', '2023-03-15', '2024-03-14', 'Самовывоз', 'Предоплата 100%', 1);

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
(1, 1, 5, 1),
(2, 1, 6, 2),
(3, 2, 7, 1),
(4, 3, 6, 1),
(5, 3, 5, 2);

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
(1, 'А123БВ777', 'ГАЗель NEXT', '1500.00', 1),
(2, 'В456ТК777', 'ГАЗель NEXT', '1500.00', 1),
(3, 'С789УК777', 'Ford Transit', '2000.00', 1),
(4, 'Е012ХС777', 'Ford Transit', '2000.00', 1);

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
  `received_by` int NOT NULL,
  `notes` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
(1, 'Мука пшеничная', 'кг', '500.000', '100.000', '45.00', 1),
(2, 'Мука ржаная', 'кг', '300.000', '50.000', '55.00', 1),
(3, 'Дрожжи', 'кг', '20.000', '5.000', '320.00', 1),
(4, 'Соль', 'кг', '50.000', '10.000', '15.00', 1),
(5, 'Сахар', 'кг', '200.000', '50.000', '65.00', 1),
(6, 'Масло растительное', 'л', '100.000', '20.000', '120.00', 1),
(7, 'Яйца', 'шт', '1000.000', '200.000', '8.00', 1),
(8, 'Капуста', 'кг', '50.000', '10.000', '35.00', 1),
(9, 'Шоколад', 'кг', '30.000', '5.000', '450.00', 1);

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

-- --------------------------------------------------------

--
-- Структура таблицы `Orders`
--

CREATE TABLE `Orders` (
  `order_id` int NOT NULL,
  `client_id` int NOT NULL,
  `contract_id` int NOT NULL,
  `order_date` date NOT NULL,
  `status` enum('new','in_progress','completed','canceled') DEFAULT 'new',
  `notes` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `ProductCategories`
--

CREATE TABLE `ProductCategories` (
  `category_id` int NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `ProductCategories`
--

INSERT INTO `ProductCategories` (`category_id`, `name`, `description`) VALUES
(1, 'Хлебобулочные изделия', 'Различные виды хлеба и булочек'),
(2, 'Кондитерские изделия', 'Торты, пирожные, печенье'),
(3, 'Изделия из слоёного теста', 'Слойки, круассаны'),
(4, 'Пирожки', 'Пирожки с различными начинками'),
(5, 'Булочки', 'Различные виды булочек');

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
(1, 1, 'Хлеб горчичный', 'шт', '0.500', '45.00', 1),
(2, 1, 'Хлеб ржаной', 'шт', '0.700', '55.00', 1),
(3, 2, 'Халва', 'кг', '1.000', '320.00', 1),
(4, 2, 'Печенье овсяное', 'кг', '1.000', '280.00', 1),
(5, 3, 'Круассан с шоколадом', 'шт', '0.200', '75.00', 1),
(6, 4, 'Пирожок с капустой', 'шт', '0.150', '35.00', 1),
(7, 5, 'Булочка сдобная', 'шт', '0.100', '25.00', 1);

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
(2, 1, 3, '0.010'),
(3, 1, 4, '0.005'),
(4, 1, 5, '0.010'),
(5, 2, 2, '0.400'),
(6, 2, 3, '0.015'),
(7, 2, 4, '0.005'),
(8, 3, 5, '0.500'),
(9, 3, 9, '0.200'),
(10, 4, 1, '0.400'),
(11, 4, 5, '0.200'),
(12, 4, 7, '2.000'),
(13, 5, 1, '0.150'),
(14, 5, 6, '0.050'),
(15, 5, 9, '0.030'),
(16, 6, 1, '0.100'),
(17, 6, 8, '0.050'),
(18, 7, 1, '0.080'),
(19, 7, 5, '0.030'),
(20, 7, 7, '0.500');

-- --------------------------------------------------------

--
-- Структура таблицы `Users`
--

CREATE TABLE `Users` (
  `user_id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `role` enum('technologist','storekeeper','expeditor','director','client') NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `inn` varchar(20) DEFAULT NULL,
  `address` text,
  `contact_person` varchar(100) DEFAULT NULL,
  `is_client` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `Users`
--

INSERT INTO `Users` (`user_id`, `username`, `password`, `full_name`, `role`, `phone`, `email`, `is_active`, `inn`, `address`, `contact_person`, `is_client`) VALUES
(1, 'technologist', 'technologist', 'Смирнова Анна', 'technologist', '+79104561234', 'tech@bakery.ru', 1, NULL, NULL, NULL, 0),
(2, 'storekeeper', 'storekeeper', 'Кузнецов Петр', 'storekeeper', '+79105671234', 'store@bakery.ru', 1, NULL, NULL, NULL, 0),
(3, 'expeditor', 'expeditor', 'Васильев Иван', 'expeditor', '+79106781234', 'exp1@bakery.ru', 1, NULL, NULL, NULL, 0),
(4, 'owner', 'owner', 'Николаев Дмитрий', 'director', '+79107891234', 'dir@bakery.ru', 1, NULL, NULL, NULL, 0),
(5, 'client_vesna', 'clientpass1', 'Магазин \"Весна\"', 'client', '+79101234567', 'vesna@example.com', 1, '1234567890', 'ул. Ленина, 10', 'Иванова Мария', 1),
(6, 'client_okean', 'clientpass2', 'Супермаркет \"Океан\"', 'client', '+79112345678', 'okean@example.com', 1, '2345678901', 'пр. Мира, 25', 'Петров Алексей', 1),
(7, 'client_solnce', 'clientpass3', 'Торговый дом \"Солнце\"', 'client', '+79123456789', 'solnce@example.com', 1, '3456789012', 'ул. Гагарина, 7', 'Сидорова Ольга', 1);

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
-- Индексы сохранённых таблиц
--

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
  ADD KEY `idx_orders_client_date` (`client_id`,`order_date`);

--
-- Индексы таблицы `ProductCategories`
--
ALTER TABLE `ProductCategories`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `name` (`name`);

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
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `inn` (`inn`);

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
-- AUTO_INCREMENT для таблицы `Contracts`
--
ALTER TABLE `Contracts`
  MODIFY `contract_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `DeliveryRoutes`
--
ALTER TABLE `DeliveryRoutes`
  MODIFY `route_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `DeliveryVehicles`
--
ALTER TABLE `DeliveryVehicles`
  MODIFY `vehicle_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
  MODIFY `receipt_item_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `IngredientReceipts`
--
ALTER TABLE `IngredientReceipts`
  MODIFY `receipt_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `Ingredients`
--
ALTER TABLE `Ingredients`
  MODIFY `ingredient_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT для таблицы `IngredientStockLog`
--
ALTER TABLE `IngredientStockLog`
  MODIFY `log_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `OrderItems`
--
ALTER TABLE `OrderItems`
  MODIFY `order_item_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `Orders`
--
ALTER TABLE `Orders`
  MODIFY `order_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `ProductCategories`
--
ALTER TABLE `ProductCategories`
  MODIFY `category_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `ProductionTaskItems`
--
ALTER TABLE `ProductionTaskItems`
  MODIFY `task_item_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `ProductionTasks`
--
ALTER TABLE `ProductionTasks`
  MODIFY `task_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `Products`
--
ALTER TABLE `Products`
  MODIFY `product_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT для таблицы `Recipes`
--
ALTER TABLE `Recipes`
  MODIFY `recipe_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT для таблицы `Users`
--
ALTER TABLE `Users`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT для таблицы `WaybillItems`
--
ALTER TABLE `WaybillItems`
  MODIFY `waybill_item_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `Waybills`
--
ALTER TABLE `Waybills`
  MODIFY `waybill_id` int NOT NULL AUTO_INCREMENT;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

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
  ADD CONSTRAINT `deliveryroutes_ibfk_2` FOREIGN KEY (`client_id`) REFERENCES `Users` (`user_id`);

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
