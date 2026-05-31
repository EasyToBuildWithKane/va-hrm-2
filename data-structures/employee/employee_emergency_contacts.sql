-- Module: employee
-- Purpose: Lien lac khan cap (vo/chong, bo me, …). 1 employee co nhieu contact.
-- Related: employees.id
-- Legacy origin: n/a

CREATE TABLE `employee_emergency_contacts` (
  `id`           BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `employee_id`  BIGINT UNSIGNED NOT NULL,
  `full_name`    VARCHAR(255)    NOT NULL,
  `relationship` VARCHAR(100)    NOT NULL COMMENT 'spouse, parent, sibling, child, friend, …',
  `phone`        VARCHAR(50)     NOT NULL,
  `email`        VARCHAR(255)    DEFAULT NULL,
  `address`      VARCHAR(255)    DEFAULT NULL,
  `is_primary`   TINYINT(1)      NOT NULL DEFAULT 0,
  `created_at`   TIMESTAMP       NULL DEFAULT NULL,
  `updated_at`   TIMESTAMP       NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_emergency_employee` (`employee_id`),
  CONSTRAINT `fk_emergency_employee` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
