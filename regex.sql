 CREATE TABLE `regex` (
  `tipo` varchar(50) DEFAULT NULL,
  `identificador` varchar(20) DEFAULT NULL,
  `definicion` text DEFAULT NULL,
  `color` varchar(7) DEFAULT NULL,
  `equivalente` varchar(10) DEFAULT NULL,
  `token` varchar(100) DEFAULT NULL,
  `patron` varchar(3000) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
