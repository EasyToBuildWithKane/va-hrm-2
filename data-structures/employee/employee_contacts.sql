-- Module: employee
-- Purpose: Lien lac va dia chi cua nhan vien (1-1 voi employees).
-- Related: employees.id
-- Legacy origin: _legacy/user_info.sql (phone, address, household, working_place)

CREATE TABLE `employee_contacts` (
  `id`             BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `employee_id`    BIGINT UNSIGNED NOT NULL,
  `phone`          VARCHAR(255)    DEFAULT NULL,
  `address`        VARCHAR(255)    DEFAULT NULL,
  `household`      VARCHAR(255)    DEFAULT NULL COMMENT 'Dia chi ho khau',
  `working_place`  VARCHAR(255)    DEFAULT NULL COMMENT 'Noi lam viec thuc te',
  `created_at`     TIMESTAMP       NULL DEFAULT NULL,
  `updated_at`     TIMESTAMP       NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_contacts_employee` (`employee_id`),
  CONSTRAINT `fk_contacts_employee` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
