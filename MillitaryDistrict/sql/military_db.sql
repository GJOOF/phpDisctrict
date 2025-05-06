CREATE TABLE IF NOT EXISTS  `mil_unit` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(100),
  `formation_id` int,
  `weapon` int,
  `vehicle` int,
  `aircraft` int,
  `buildings` int,
  `settlement` int
);

CREATE TABLE IF NOT EXISTS  `mil_formation` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `type` VARCHAR(50),
  `name` VARCHAR(100),
  `manpower` INT,
  `commanding_officer` VARCHAR(100),
  `headquarter` VARCHAR(100),
  `weapon` INT,
  `vehicle` INT,
  `aircraft` INT,
  `units` int
);

CREATE TABLE IF NOT EXISTS  `mil_rank` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `name` VARCHAR(100)
);

CREATE TABLE IF NOT EXISTS  `mil_occupation` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `name` VARCHAR(100)
);

CREATE TABLE IF NOT EXISTS  `recruit` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `name` VARCHAR(100),
  `age` DATETIME,
  `sex` INT,
  `rank_id` INT,
  `occupation_id` INT,
  `formation_id` INT,
  `service_len` TIMESTAMP,
  `IsSergeant` BOOLEAN,
  `IsOfficer` BOOLEAN
);

CREATE TABLE IF NOT EXISTS  `vehicle` (
  `id` INT PRIMARY KEY AUTO_INCREMENT, 
  `name` varchar(255),
  `amount` INT
);

CREATE TABLE IF NOT EXISTS  `weapon` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(255),
  `amount` INT
);

CREATE TABLE IF NOT EXISTS  `aircraft` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(255),
  `amount` int
);

CREATE TABLE IF NOT EXISTS  `buildings` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(255),
  `deployed_units` int
);

CREATE TABLE IF NOT EXISTS  `settlement` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(255)
);

ALTER TABLE `recruit` ADD FOREIGN KEY (`rank_id`) REFERENCES `mil_rank` (`id`);

ALTER TABLE `recruit` ADD FOREIGN KEY (`occupation_id`) REFERENCES `mil_occupation` (`id`);

ALTER TABLE `recruit` ADD FOREIGN KEY (`formation_id`) REFERENCES `mil_formation` (`id`);

ALTER TABLE `mil_unit` ADD FOREIGN KEY (`formation_id`) REFERENCES `mil_formation` (`id`);

ALTER TABLE `mil_formation` ADD FOREIGN KEY (`vehicle`) REFERENCES `vehicle` (`id`);

ALTER TABLE `mil_formation` ADD FOREIGN KEY (`weapon`) REFERENCES `weapon` (`id`);

ALTER TABLE `mil_formation` ADD FOREIGN KEY (`aircraft`) REFERENCES `aircraft` (`id`);

ALTER TABLE `mil_unit` ADD FOREIGN KEY (`vehicle`) REFERENCES `vehicle` (`id`);

ALTER TABLE `mil_unit` ADD FOREIGN KEY (`weapon`) REFERENCES `weapon` (`id`);

ALTER TABLE `mil_unit` ADD FOREIGN KEY (`aircraft`) REFERENCES `aircraft` (`id`);

ALTER TABLE `mil_unit` ADD FOREIGN KEY (`buildings`) REFERENCES `buildings` (`id`);

ALTER TABLE `buildings` ADD FOREIGN KEY (`deployed_units`) REFERENCES `mil_formation` (`id`);

ALTER TABLE `mil_unit` ADD FOREIGN KEY (`settlement`) REFERENCES `settlement` (`id`);

ALTER TABLE `mil_formation` ADD FOREIGN KEY (`units`) REFERENCES `mil_unit` (`id`);
