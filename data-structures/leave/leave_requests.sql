-- Module: leave
-- Purpose: Don xin nghi phep — gan vao approval_workflows qua workflow_id.
-- Related: employees.id, leave_types.id, approval_workflows.id
-- Legacy origin: n/a

CREATE TABLE `leave_requests` (
  `id`            BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `ulid`          CHAR(26)        NOT NULL,
  `employee_id`   BIGINT UNSIGNED NOT NULL,
  `leave_type_id` BIGINT UNSIGNED NOT NULL,
  `workflow_id`   BIGINT UNSIGNED NULL DEFAULT NULL,
  `start_date`    DATE            NOT NULL,
  `end_date`      DATE            NOT NULL,
  `days_count`    DECIMAL(5,2)    NOT NULL,
  `reason`        TEXT            DEFAULT NULL,
  `status`        ENUM('draft','pending','approved','rejected','cancelled') NOT NULL DEFAULT 'draft',
  `created_at`    TIMESTAMP       NULL DEFAULT NULL,
  `updated_at`    TIMESTAMP       NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_leave_request_ulid` (`ulid`),
  KEY `idx_leave_request_employee` (`employee_id`),
  KEY `idx_leave_request_status`   (`status`),
  KEY `idx_leave_request_dates`    (`start_date`, `end_date`),
  CONSTRAINT `fk_leave_request_employee`   FOREIGN KEY (`employee_id`)   REFERENCES `employees` (`id`),
  CONSTRAINT `fk_leave_request_leave_type` FOREIGN KEY (`leave_type_id`) REFERENCES `leave_types` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
