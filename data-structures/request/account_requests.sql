-- Module: request
-- Purpose: Yeu cau cap tai khoan he thong / email noi bo.
-- Related: workflow_requests (polymorphic)
-- Legacy origin: n/a

CREATE TABLE `account_requests` (
  `id`              BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `account_type`    ENUM('email','system','vpn','other') NOT NULL,
  `requested_email` VARCHAR(255)    DEFAULT NULL,
  `requested_alias` VARCHAR(255)    DEFAULT NULL,
  `mailbox_quota`   VARCHAR(20)     DEFAULT NULL,
  `aliases`         JSON            DEFAULT NULL,
  `notes`           TEXT            DEFAULT NULL,
  `created_at`      TIMESTAMP       NULL DEFAULT NULL,
  `updated_at`      TIMESTAMP       NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
