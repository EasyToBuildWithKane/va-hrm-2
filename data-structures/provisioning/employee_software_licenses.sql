-- Module: provisioning
-- Purpose: Pivot — gan license cho nhan vien (seat assignment).
-- Related: employees.id, software_licenses.id
-- Legacy origin: n/a

CREATE TABLE `employee_software_licenses` (
  `id`                  BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `employee_id`         BIGINT UNSIGNED NOT NULL,
  `software_license_id` BIGINT UNSIGNED NOT NULL,
  `assigned_at`         TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `revoked_at`          TIMESTAMP       NULL DEFAULT NULL,
  `assigned_by`         BIGINT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_emp_license` (`employee_id`, `software_license_id`),
  KEY `idx_emp_license_license`  (`software_license_id`),
  CONSTRAINT `fk_emp_license_employee` FOREIGN KEY (`employee_id`)         REFERENCES `employees` (`id`)         ON DELETE CASCADE,
  CONSTRAINT `fk_emp_license_license`  FOREIGN KEY (`software_license_id`) REFERENCES `software_licenses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
