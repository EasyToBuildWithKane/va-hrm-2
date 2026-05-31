-- Module: attendance
-- Purpose: Ban ghi cham cong hang ngay (1 row / employee / date).
-- Related: employees.id, attendance_shifts.id
-- Legacy origin: n/a

CREATE TABLE `attendance_records` (
  `id`               BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `employee_id`      BIGINT UNSIGNED NOT NULL,
  `shift_id`         BIGINT UNSIGNED NULL DEFAULT NULL,
  `date`             DATE            NOT NULL,
  `check_in_at`      TIMESTAMP       NULL DEFAULT NULL,
  `check_out_at`     TIMESTAMP       NULL DEFAULT NULL,
  `check_in_ip`      VARCHAR(45)     DEFAULT NULL,
  `check_out_ip`     VARCHAR(45)     DEFAULT NULL,
  `status`           ENUM('present','absent','late','half_day','holiday','leave') NOT NULL,
  `late_minutes`     INT UNSIGNED    NOT NULL DEFAULT 0,
  `overtime_minutes` INT UNSIGNED    NOT NULL DEFAULT 0,
  `notes`            TEXT            DEFAULT NULL,
  `is_corrected`     TINYINT(1)      NOT NULL DEFAULT 0,
  `created_at`       TIMESTAMP       NULL DEFAULT NULL,
  `updated_at`       TIMESTAMP       NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_attendance_employee_date` (`employee_id`, `date`),
  KEY `idx_attendance_date`          (`date`),
  KEY `idx_attendance_employee_date` (`employee_id`, `date`),
  CONSTRAINT `fk_attendance_employee` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`),
  CONSTRAINT `fk_attendance_shift`    FOREIGN KEY (`shift_id`)    REFERENCES `attendance_shifts` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
