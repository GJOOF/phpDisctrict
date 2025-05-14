-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Май 13 2025 г., 21:16
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
-- Структура таблицы `recruit`
--

CREATE TABLE IF NOT EXISTS `recruit` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `age` datetime NOT NULL,
  `sex` tinyint(1) NOT NULL,
  `rank_id` int NOT NULL,
  `occupation_id` int DEFAULT NULL,
  `formation_id` int DEFAULT NULL,
  `service_len` timestamp NULL DEFAULT NULL,
  `IsSergeant` tinyint(1) DEFAULT NULL,
  `IsOfficer` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `recruit`
--

--
-- Дамп данных таблицы `recruit`
--

-- Сначала сбрасываем AUTO_INCREMENT
-- Затем вставляем данные без указания id
-- --------------------------------------------------------

--
-- Структура таблицы `mil_formation`
--

CREATE TABLE IF NOT EXISTS `mil_formation` (
  `id` int NOT NULL AUTO_INCREMENT,
  `type_id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `commanding_officer` int NOT NULL,
  `headquarter` int DEFAULT NULL,
  `weapon_id` int NOT NULL,
  `vehicle_id` int DEFAULT NULL,
  `units` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `mil_formation`
--
-- --------------------------------------------------------

--
-- Структура таблицы `mil_unit`
--

CREATE TABLE IF NOT EXISTS `mil_unit` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `formation_id` int DEFAULT NULL,
  `weapon_id` int DEFAULT NULL,
  `vehicle_id` int DEFAULT NULL,
  `buildings_id` int DEFAULT NULL,
  `settlement` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `mil_unit`
--
-- --------------------------------------------------------

--
-- Структура таблицы `recruit_formation`
--

CREATE TABLE IF NOT EXISTS `recruit_formation` (
  `recruit_id` int NOT NULL,
  `formation_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `recruit_formation`
--

-- --------------------------------------------------------

--
-- Структура таблицы `formation_unit`
--

CREATE TABLE IF NOT EXISTS `formation_unit` (
  `formation_id` int NOT NULL,
  `unit_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `formation_unit`
--

-- --------------------------------------------------------

--
-- Структура таблицы `formation_hierarchy`
--

CREATE TABLE IF NOT EXISTS `formation_hierarchy` (
  `object_id` int NOT NULL,
  `subject_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `formation_hierarchy`
--

-- --------------------------------------------------------

--
-- Структура таблицы `buildings`
--

CREATE TABLE IF NOT EXISTS `buildings` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `buildings`
--
-- --------------------------------------------------------

--
-- Структура таблицы `formation_type`
--

CREATE TABLE IF NOT EXISTS `formation_type` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `formation_type`
--
-- --------------------------------------------------------

--
-- Структура таблицы `mil_occupation`
--

CREATE TABLE IF NOT EXISTS `mil_occupation` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `mil_occupation`
--
-- --------------------------------------------------------

--
-- Структура таблицы `mil_rank`
--

CREATE TABLE IF NOT EXISTS `mil_rank` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `mil_rank`
--
-- --------------------------------------------------------

--
-- Структура таблицы `recruit_occupation`
--

CREATE TABLE IF NOT EXISTS `recruit_occupation` (
  `recruit_id` int NOT NULL,
  `occupation_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `recruit_occupation`
--

-- --------------------------------------------------------

--
-- Структура таблицы `settlement`
--


CREATE TABLE IF NOT EXISTS `settlement` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `settlement`
--

-- --------------------------------------------------------

--
-- Структура таблицы `unit_buildings`
--

CREATE TABLE IF NOT EXISTS `unit_buildings` (
  `unit_id` int NOT NULL,
  `building_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `unit_buildings`
--

-- --------------------------------------------------------

--
-- Структура таблицы `unit_settlement`
--

CREATE TABLE IF NOT EXISTS `unit_settlement` (
  `unit_id` int NOT NULL,
  `settlement_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `unit_settlement`
--

-- --------------------------------------------------------

--
-- Структура таблицы `vehicle`
--

CREATE TABLE IF NOT EXISTS `vehicle` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `amount` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `vehicle`

-- --------------------------------------------------------

--
-- Структура таблицы `weapon`
--

CREATE TABLE IF NOT EXISTS `weapon` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `amount` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `weapon`
--
--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `formation_hierarchy`
--
--
--
--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `buildings`
--
-- Ограничения внешнего ключа сохранённых таблиц
--

--
-- Ограничения внешнего ключа таблицы `formation_hierarchy`
--
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;