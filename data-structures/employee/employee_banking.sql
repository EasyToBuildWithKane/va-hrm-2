-- Module: employee
-- Purpose: Tai khoan ngan hang de chi luong. 1 employee co the co nhieu tk (is_primary).
-- Related: employees.id
-- Legacy origin: _legacy/user_info.sql (bank_account, bank)

CREATE TABLE `employee_banking` (
  `id`             BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `employee_id`    BIGINT UNSIGNED NOT NULL,
  `bank_name`      VARCHAR(255)    NOT NULL,
  `bank_branch`    VARCHAR(255)    DEFAULT NULL,
  `account_number` VARCHAR(255)    NOT NULL,
  `account_holder` VARCHAR(255)    DEFAULT NULL,
  `is_primary`     TINYINT(1)      NOT NULL DEFAULT 1,
  `created_at`     TIMESTAMP       NULL DEFAULT NULL,
  `updated_at`     TIMESTAMP       NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_banking_employee` (`employee_id`),
  CONSTRAINT `fk_banking_employee` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
