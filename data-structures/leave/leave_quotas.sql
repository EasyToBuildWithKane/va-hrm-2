-- Module: leave
-- Purpose: Quota phep / nhan vien / loai / nam.
-- Related: employees.id, leave_types.id
-- Legacy origin: n/a

CREATE TABLE `leave_quotas` (
  `id`            BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `employee_id`   BIGINT UNSIGNED NOT NULL,
  `leave_type_id` BIGINT UNSIGNED NOT NULL,
  `year`          YEAR            NOT NULL,
  `entitled_days` DECIMAL(5,2)    NOT NULL,
  `used_days`     DECIMAL(5,2)    NOT NULL DEFAULT 0,
  `carried_days`  DECIMAL(5,2)    NOT NULL DEFAULT 0,
  `created_at`    TIMESTAMP       NULL DEFAULT NULL,
  `updated_at`    TIMESTAMP       NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_quota_emp_type_year` (`employee_id`, `leave_type_id`, `year`),
  CONSTRAINT `fk_quota_employee`   FOREIGN KEY (`employee_id`)   REFERENCES `employees` (`id`)   ON DELETE CASCADE,
  CONSTRAINT `fk_quota_leave_type` FOREIGN KEY (`leave_type_id`) REFERENCES `leave_types` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
