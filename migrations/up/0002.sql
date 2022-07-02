DROP TABLE IF EXISTS `note`;

CREATE TABLE `note` (
    `id` int NOT NULL AUTO_INCREMENT,
    `entry` text COLLATE utf8mb4_general_ci,
    `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `auth_id` int NOT NULL,
    `public` tinyint(1) DEFAULT '0',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci