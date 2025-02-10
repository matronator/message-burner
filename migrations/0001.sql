CREATE TABLE `rooms` (
  `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `hash` varchar(128) NOT NULL,
  `password` varchar(128) NULL,
  `secret_key` text NULL,
  `created_at` datetime NULL DEFAULT NOW(),
  `burn_on_read` tinyint NOT NULL DEFAULT '0'
) COLLATE 'utf8mb4_unicode_520_ci';

ALTER TABLE `rooms`
ADD `created_by` varchar(90) COLLATE 'utf8mb4_unicode_520_ci' NULL AFTER `hash`;
