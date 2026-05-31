-- Module: employee
-- Purpose: Dong thoi gian su kien cua nhan vien (promotion, transfer, training, award, …).
-- Related: employees.id, departments.id, positions.id
-- Legacy origin: n/a

CREATE TABLE `employee_timeline` (
  `id`             BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `employee_id`    BIGINT UNSIGNED NOT NULL,
  `event_type`     VARCHAR(50)     NOT NULL COMMENT 'join, promotion, transfer, demotion, training, award, warning, terminate, …',
  `title`          VARCHAR(255)    NOT NULL,
  `description`    TEXT            DEFAULT NULL,
  `from_value`     JSON            DEFAULT NULL,
  `to_value`       JSON            DEFAULT NULL,
  `effective_date` DATE            NOT NULL,
  `recorded_by`    BIGINT UNSIGNED NULL DEFAULT NULL,
  `metadata`       JSON            DEFAULT NULL,
  `created_at`     TIMESTAMP       NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_timeline_employee_date` (`employee_id`, `effective_date`),
  KEY `idx_timeline_event_type`    (`event_type`),
  CONSTRAINT `fk_timeline_employee` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
