-- Module: attendance
-- Purpose: Ca lam viec (shift) — gan voi nhan vien qua attendance_records.shift_id.
-- Related: attendance_records.shift_id
-- Legacy origin: n/a

CREATE TABLE `attendance_shifts` (
  `id`              BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name`            VARCHAR(100)    NOT NULL,
  `code`            VARCHAR(20)     NOT NULL,
  `start_time`      TIME            NOT NULL,
  `end_time`        TIME            NOT NULL,
  `break_minutes`   INT UNSIGNED    NOT NULL DEFAULT 0,
  `late_tolerance`  INT UNSIGNED    NOT NULL DEFAULT 0 COMMENT 'Minutes',
  `is_overnight`    TINYINT(1)      NOT NULL DEFAULT 0,
  `is_active`       TINYINT(1)      NOT NULL DEFAULT 1,
  `created_at`      TIMESTAMP       NULL DEFAULT NULL,
  `updated_at`      TIMESTAMP       NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_shifts_code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
