-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Май 20 2025 г., 20:07
-- Версия сервера: 8.0.30
-- Версия PHP: 8.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `military_db`
--

-- --------------------------------------------------------

--
-- Структура таблицы `buildings`
--

CREATE TABLE `buildings` (
  `id` int NOT NULL,
  `name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `buildings`
--

INSERT INTO `buildings` (`id`, `name`) VALUES
(1, 'Штаб части'),
(2, 'Комендатура'),
(3, 'Караульное помещение'),
(4, 'Дежурная часть'),
(5, 'Архив'),
(6, 'Казарма №1'),
(7, 'Казарма №2'),
(8, 'Солдатское общежитие'),
(9, 'Офицерское общежитие'),
(10, 'Изолятор временного содержания'),
(11, 'Учебный корпус'),
(12, 'Спортивный комплекс'),
(13, 'Полоса препятствий'),
(14, 'Столовая №1'),
(15, 'Продовольственный склад'),
(16, 'Вещевой склад'),
(17, 'Банно-прачечный комбинат'),
(18, 'Котельная'),
(19, 'Автопарк'),
(20, 'Медицинский пункт'),
(21, 'КПП Главный'),
(22, 'КПП №2'),
(23, 'Парк боевых машин'),
(24, 'Ангар техники'),
(25, 'Ремонтная мастерская'),
(26, 'Хранилище оружия');

-- --------------------------------------------------------

--
-- Структура таблицы `formation_hierarchy`
--

CREATE TABLE `formation_hierarchy` (
  `object_id` int NOT NULL,
  `subject_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `formation_hierarchy`
--

INSERT INTO `formation_hierarchy` (`object_id`, `subject_id`) VALUES
(3, 1),
(3, 2);

-- --------------------------------------------------------

--
-- Структура таблицы `formation_type`
--

CREATE TABLE `formation_type` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `formation_type`
--

INSERT INTO `formation_type` (`id`, `name`) VALUES
(1, 'Отделение'),
(2, 'Взвод'),
(3, 'Рота'),
(4, 'Батальон'),
(5, 'Полк'),
(6, 'Бригада'),
(7, 'Дивизия'),
(8, 'Корпус'),
(9, 'Армия');

-- --------------------------------------------------------

--
-- Структура таблицы `formation_unit`
--

CREATE TABLE `formation_unit` (
  `formation_id` int NOT NULL,
  `unit_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `formation_unit`
--

INSERT INTO `formation_unit` (`formation_id`, `unit_id`) VALUES
(3, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `mil_formation`
--

CREATE TABLE `mil_formation` (
  `id` int NOT NULL,
  `type_id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `commanding_officer` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `mil_formation`
--

INSERT INTO `mil_formation` (`id`, `type_id`, `name`, `commanding_officer`) VALUES
(1, 1, '1/1 мср', 1),
(2, 1, '2/1 мср', 2),
(3, 2, '1 мср', 3);

-- --------------------------------------------------------

--
-- Структура таблицы `mil_occupation`
--

CREATE TABLE `mil_occupation` (
  `id` int NOT NULL,
  `name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `mil_occupation`
--

INSERT INTO `mil_occupation` (`id`, `name`) VALUES
(1, 'Командир мотострелкового отделения'),
(2, 'Механик-водитель БМП'),
(3, 'Наводчик-оператор БМП'),
(4, 'Командир танка'),
(5, 'Механик-водитель танка'),
(6, 'Наводчик-оператор танка'),
(7, 'Пулемётчик'),
(8, 'Стрелок-гранатомётчик'),
(9, 'Снайпер'),
(10, 'Старший стрелок'),
(11, 'Командир артиллерийского орудия'),
(12, 'Наводчик артиллерийского орудия'),
(13, 'Оператор ПТРК'),
(14, 'Командир миномётного расчёта'),
(15, 'Разведчик-артиллерист'),
(16, 'Сапёр'),
(17, 'Инженер-разведчик'),
(18, 'Механик по ремонту бронетехники'),
(19, 'Электромеханик'),
(20, 'Водитель военных грузовиков'),
(21, 'Разведчик-диверсант'),
(22, 'Радист-шифровальщик'),
(23, 'Военный топограф'),
(24, 'Химик-разведчик'),
(25, 'Специалист РХБЗ'),
(26, 'Старший радиотелефонист'),
(27, 'Оператор спутниковой связи'),
(28, 'Начальник радиостанции'),
(29, 'Техник средств связи'),
(30, 'Военный повар'),
(31, 'Вещевой сервис'),
(32, 'Горюче-смазочных материалов'),
(33, 'Командир взвода'),
(34, 'Командир роты'),
(35, 'Командир батальона'),
(36, 'Командир полка'),
(37, 'Командир бригады'),
(38, 'Командир дивизии'),
(39, 'Командующий');

-- --------------------------------------------------------

--
-- Структура таблицы `mil_rank`
--

CREATE TABLE `mil_rank` (
  `id` int NOT NULL,
  `name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `mil_rank`
--

INSERT INTO `mil_rank` (`id`, `name`) VALUES
(1, 'Рядовой'),
(2, 'Ефрейтор'),
(3, 'Младший сержант'),
(4, 'Сержант'),
(5, 'Старший сержант'),
(6, 'Старшина'),
(7, 'Прапорщик'),
(8, 'Старший прапорщик'),
(9, 'Младший лейтенант'),
(10, 'Лейтенант'),
(11, 'Старший лейтенант'),
(12, 'Капитан'),
(13, 'Майор'),
(14, 'Подполковник'),
(15, 'Полковник'),
(16, 'Генерал-майор'),
(17, 'Генерал-лейтенант'),
(18, 'Генерал-полковник'),
(19, 'Генерал армии'),
(20, 'Маршал Российской Федерации');

-- --------------------------------------------------------

--
-- Структура таблицы `mil_unit`
--

CREATE TABLE `mil_unit` (
  `id` int NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `formation_id` int DEFAULT NULL,
  `weapon_id` int DEFAULT NULL,
  `vehicle_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `mil_unit`
--

INSERT INTO `mil_unit` (`id`, `name`, `formation_id`, `weapon_id`, `vehicle_id`) VALUES
(1, '75041', 3, NULL, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `recruit`
--

CREATE TABLE `recruit` (
  `id` int NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `age` datetime NOT NULL,
  `sex` tinyint(1) NOT NULL,
  `rank_id` int NOT NULL,
  `service_len` timestamp NOT NULL,
  `IsSergeant` tinyint(1) DEFAULT NULL,
  `IsOfficer` tinyint(1) DEFAULT NULL,
  `education` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `recruit`
--

INSERT INTO `recruit` (`id`, `name`, `age`, `sex`, `rank_id`, `service_len`, `IsSergeant`, `IsOfficer`, `education`) VALUES
(1, 'Иванов Д.С.', '2001-11-09 00:00:00', 0, 10, '2021-09-05 21:00:00', 0, 1, NULL),
(2, 'Очеретный В. И.', '1999-08-09 00:00:00', 0, 11, '2020-08-07 21:00:00', 0, 1, NULL),
(3, 'Исаченко А. А.', '1998-08-09 00:00:00', 0, 12, '2020-08-07 21:00:00', 0, 1, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `recruit_formation`
--

CREATE TABLE `recruit_formation` (
  `recruit_id` int NOT NULL,
  `formation_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `recruit_formation`
--

INSERT INTO `recruit_formation` (`recruit_id`, `formation_id`) VALUES
(1, 1),
(2, 2),
(3, 3);

-- --------------------------------------------------------

--
-- Структура таблицы `recruit_occupation`
--

CREATE TABLE `recruit_occupation` (
  `recruit_id` int NOT NULL,
  `occupation_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `recruit_occupation`
--

INSERT INTO `recruit_occupation` (`recruit_id`, `occupation_id`) VALUES
(1, 33),
(2, 33),
(3, 34),
(1, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `settlement`
--

CREATE TABLE `settlement` (
  `id` int NOT NULL,
  `name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `settlement`
--

INSERT INTO `settlement` (`id`, `name`) VALUES
(1, 'Ростов-на-Дону'),
(2, 'Новочеркасск'),
(3, 'Таганрог'),
(4, 'Каменск-Шахтинский'),
(5, 'Краснодар'),
(6, 'Новороссийск'),
(7, 'Сочи'),
(8, 'Армавир'),
(9, 'Ставрополь'),
(10, 'Будённовск'),
(11, 'Михайловск'),
(12, 'Волгоград'),
(13, 'Камышин'),
(14, 'Волжский'),
(15, 'Севастополь'),
(16, 'Симферополь'),
(17, 'Керчь'),
(18, 'Евпатория'),
(19, 'Владикавказ'),
(20, 'Махачкала'),
(21, 'Грозный'),
(22, 'Астрахань'),
(23, 'Ахтубинск');

-- --------------------------------------------------------

--
-- Структура таблицы `unit_buildings`
--

CREATE TABLE `unit_buildings` (
  `unit_id` int NOT NULL,
  `building_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `unit_buildings`
--

INSERT INTO `unit_buildings` (`unit_id`, `building_id`) VALUES
(1, 1),
(1, 2),
(1, 3);

-- --------------------------------------------------------

--
-- Структура таблицы `unit_equipment`
--

CREATE TABLE `unit_equipment` (
  `unit_id` int NOT NULL,
  `weapon_id` int NOT NULL,
  `vehicle_id` int NOT NULL,
  `weapon_count` int NOT NULL,
  `vehicle_count` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `unit_equipment`
--

INSERT INTO `unit_equipment` (`unit_id`, `weapon_id`, `vehicle_id`, `weapon_count`, `vehicle_count`) VALUES
(1, 3, 1, 100, 100);

-- --------------------------------------------------------

--
-- Структура таблицы `unit_settlement`
--

CREATE TABLE `unit_settlement` (
  `unit_id` int NOT NULL,
  `settlement_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `unit_settlement`
--

INSERT INTO `unit_settlement` (`unit_id`, `settlement_id`) VALUES
(1, 10);

-- --------------------------------------------------------

--
-- Структура таблицы `vehicle`
--

CREATE TABLE `vehicle` (
  `id` int NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `type` int NOT NULL,
  `amount` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `vehicle`
--

INSERT INTO `vehicle` (`id`, `name`, `type`, `amount`) VALUES
(1, 'Т-90М', 1, 370),
(2, 'БТР-82', 2, 370),
(3, 'АМН-590951 ВПК-Урал\r\n', 4, 370),
(4, '2С19 Мста-С', 5, 370),
(5, 'ТОС-1А Солнцепёк', 6, 370),
(6, '2С6 Тунгуска-М', 7, 370),
(7, '9П157 Хризантема-С', 8, 370);

-- --------------------------------------------------------

--
-- Структура таблицы `vehicle_type`
--

CREATE TABLE `vehicle_type` (
  `id` int NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `vehicle_type`
--

INSERT INTO `vehicle_type` (`id`, `name`) VALUES
(1, 'Танк'),
(2, 'Боевая машина пехоты'),
(3, 'Бронетранспортёр'),
(4, 'Бронеавтомобиль'),
(5, 'Самоходная артиллерийская установка'),
(6, 'Реактивная система залпового огня'),
(7, 'Зенитный ракетный комплекс'),
(8, 'Противотанковый ракетный комплекс');

-- --------------------------------------------------------

--
-- Структура таблицы `weapon`
--

CREATE TABLE `weapon` (
  `id` int NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `type` int NOT NULL,
  `amount` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `weapon`
--

INSERT INTO `weapon` (`id`, `name`, `type`, `amount`) VALUES
(1, 'РШГ-2', 1, 500),
(2, 'АГС-30', 2, 500),
(3, 'ПКП Печенег', 3, 500),
(4, 'СВ-98', 4, 500),
(5, 'АК-74', 5, 500),
(6, 'MP-443 Грач', 6, 500),
(7, 'ПФМ-1 Лепесток', 7, 500),
(8, 'Ф-1', 8, 500);

-- --------------------------------------------------------

--
-- Структура таблицы `weapon_type`
--

CREATE TABLE `weapon_type` (
  `id` int NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `weapon_type`
--

INSERT INTO `weapon_type` (`id`, `name`) VALUES
(1, 'Реактивная штурмовая граната'),
(2, 'Противопехотный гранатомёт'),
(3, 'Пулемёт'),
(4, 'Снайперская винтовка'),
(5, 'Автомат'),
(6, 'Пистолет'),
(7, 'Противопехотная мина'),
(8, 'Ручная граната');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `buildings`
--
ALTER TABLE `buildings`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `formation_hierarchy`
--
ALTER TABLE `formation_hierarchy`
  ADD KEY `object` (`object_id`),
  ADD KEY `subject` (`subject_id`),
  ADD KEY `idx_object_id` (`object_id`),
  ADD KEY `idx_subject_id` (`subject_id`);

--
-- Индексы таблицы `formation_type`
--
ALTER TABLE `formation_type`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `formation_unit`
--
ALTER TABLE `formation_unit`
  ADD KEY `formation_id` (`formation_id`),
  ADD KEY `unit_id` (`unit_id`);

--
-- Индексы таблицы `mil_formation`
--
ALTER TABLE `mil_formation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_formation_type` (`type_id`),
  ADD KEY `mil_formation_ibfk_5` (`commanding_officer`);

--
-- Индексы таблицы `mil_occupation`
--
ALTER TABLE `mil_occupation`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `mil_rank`
--
ALTER TABLE `mil_rank`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `mil_unit`
--
ALTER TABLE `mil_unit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vehicle` (`vehicle_id`),
  ADD KEY `weapon` (`weapon_id`),
  ADD KEY `formation_id` (`formation_id`);

--
-- Индексы таблицы `recruit`
--
ALTER TABLE `recruit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rank_id` (`rank_id`);

--
-- Индексы таблицы `recruit_formation`
--
ALTER TABLE `recruit_formation`
  ADD KEY `recruit` (`recruit_id`),
  ADD KEY `formation` (`formation_id`);

--
-- Индексы таблицы `recruit_occupation`
--
ALTER TABLE `recruit_occupation`
  ADD KEY `occupation` (`occupation_id`),
  ADD KEY `recruit_id` (`recruit_id`);

--
-- Индексы таблицы `settlement`
--
ALTER TABLE `settlement`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `unit_buildings`
--
ALTER TABLE `unit_buildings`
  ADD KEY `unit_ibfk` (`unit_id`),
  ADD KEY `building_ibfk` (`building_id`);

--
-- Индексы таблицы `unit_equipment`
--
ALTER TABLE `unit_equipment`
  ADD KEY `weapon_fk` (`weapon_id`),
  ADD KEY `vehicle_fk` (`vehicle_id`),
  ADD KEY `unit_equip_fk` (`unit_id`);

--
-- Индексы таблицы `unit_settlement`
--
ALTER TABLE `unit_settlement`
  ADD KEY `unit_fk` (`unit_id`),
  ADD KEY `settlement_fk` (`settlement_id`);

--
-- Индексы таблицы `vehicle`
--
ALTER TABLE `vehicle`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vehicle_type_fk` (`type`);

--
-- Индексы таблицы `vehicle_type`
--
ALTER TABLE `vehicle_type`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `weapon`
--
ALTER TABLE `weapon`
  ADD PRIMARY KEY (`id`),
  ADD KEY `weapon_type_fk` (`type`);

--
-- Индексы таблицы `weapon_type`
--
ALTER TABLE `weapon_type`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `buildings`
--
ALTER TABLE `buildings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT для таблицы `formation_type`
--
ALTER TABLE `formation_type`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT для таблицы `mil_formation`
--
ALTER TABLE `mil_formation`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `mil_occupation`
--
ALTER TABLE `mil_occupation`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT для таблицы `mil_rank`
--
ALTER TABLE `mil_rank`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT для таблицы `mil_unit`
--
ALTER TABLE `mil_unit`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `recruit`
--
ALTER TABLE `recruit`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `settlement`
--
ALTER TABLE `settlement`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT для таблицы `vehicle`
--
ALTER TABLE `vehicle`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT для таблицы `weapon`
--
ALTER TABLE `weapon`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `formation_hierarchy`
--
ALTER TABLE `formation_hierarchy`
  ADD CONSTRAINT `fk_object_id` FOREIGN KEY (`object_id`) REFERENCES `mil_formation` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `fk_subject_id` FOREIGN KEY (`subject_id`) REFERENCES `mil_formation` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Ограничения внешнего ключа таблицы `formation_unit`
--
ALTER TABLE `formation_unit`
  ADD CONSTRAINT `formation_id` FOREIGN KEY (`formation_id`) REFERENCES `mil_formation` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `unit_id` FOREIGN KEY (`unit_id`) REFERENCES `mil_unit` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Ограничения внешнего ключа таблицы `mil_formation`
--
ALTER TABLE `mil_formation`
  ADD CONSTRAINT `mil_formation_ibfk_1` FOREIGN KEY (`type_id`) REFERENCES `formation_type` (`id`),
  ADD CONSTRAINT `mil_formation_ibfk_4` FOREIGN KEY (`commanding_officer`) REFERENCES `recruit` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Ограничения внешнего ключа таблицы `mil_unit`
--
ALTER TABLE `mil_unit`
  ADD CONSTRAINT `mil_unit_ibfk_1` FOREIGN KEY (`formation_id`) REFERENCES `mil_formation` (`id`);

--
-- Ограничения внешнего ключа таблицы `recruit`
--
ALTER TABLE `recruit`
  ADD CONSTRAINT `recruit_ibfk_1` FOREIGN KEY (`rank_id`) REFERENCES `mil_rank` (`id`);

--
-- Ограничения внешнего ключа таблицы `recruit_formation`
--
ALTER TABLE `recruit_formation`
  ADD CONSTRAINT `formation` FOREIGN KEY (`formation_id`) REFERENCES `mil_formation` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `recruit` FOREIGN KEY (`recruit_id`) REFERENCES `recruit` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Ограничения внешнего ключа таблицы `recruit_occupation`
--
ALTER TABLE `recruit_occupation`
  ADD CONSTRAINT `occupation` FOREIGN KEY (`occupation_id`) REFERENCES `mil_occupation` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `recruit_id` FOREIGN KEY (`recruit_id`) REFERENCES `recruit` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Ограничения внешнего ключа таблицы `unit_buildings`
--
ALTER TABLE `unit_buildings`
  ADD CONSTRAINT `building_ibfk` FOREIGN KEY (`building_id`) REFERENCES `buildings` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `unit_ibfk` FOREIGN KEY (`unit_id`) REFERENCES `mil_unit` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Ограничения внешнего ключа таблицы `unit_equipment`
--
ALTER TABLE `unit_equipment`
  ADD CONSTRAINT `unit_equip_fk` FOREIGN KEY (`unit_id`) REFERENCES `mil_unit` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `vehicle_fk` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicle` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `weapon_fk` FOREIGN KEY (`weapon_id`) REFERENCES `weapon` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Ограничения внешнего ключа таблицы `unit_settlement`
--
ALTER TABLE `unit_settlement`
  ADD CONSTRAINT `settlement_fk` FOREIGN KEY (`settlement_id`) REFERENCES `settlement` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `unit_fk` FOREIGN KEY (`unit_id`) REFERENCES `mil_unit` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Ограничения внешнего ключа таблицы `vehicle`
--
ALTER TABLE `vehicle`
  ADD CONSTRAINT `vehicle_type_fk` FOREIGN KEY (`type`) REFERENCES `vehicle_type` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Ограничения внешнего ключа таблицы `weapon`
--
ALTER TABLE `weapon`
  ADD CONSTRAINT `weapon_type_fk` FOREIGN KEY (`type`) REFERENCES `weapon_type` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
