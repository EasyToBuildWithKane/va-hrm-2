-- Module: attendance
-- Purpose: Yeu cau chinh sua attendance_records — co the gan vao workflow approval.
-- Related: attendance_records.id, employees.id, approval_workflows.id
-- Legacy origin: n/a

CREATE TABLE `attendance_corrections` (
  `id`                    BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `ulid`                  CHAR(26)        NOT NULL,
  `attendance_record_id`  BIGINT UNSIGNED NOT NULL,
  `requested_by`          BIGINT UNSIGNED NOT NULL,
  `workflow_id`           BIGINT UNSIGNED NULL DEFAULT NULL,
  `field`                 VARCHAR(50)     NOT NULL COMMENT 'check_in_at, check_out_at, status, …',
  `old_value`             VARCHAR(255)    DEFAULT NULL,
  `new_value`             VARCHAR(255)    DEFAULT NULL,
  `reason`                TEXT            NOT NULL,
  `status`                ENUM('pending','approved','rejected','cancelled') NOT NULL DEFAULT 'pending',
  `decided_by`            BIGINT UNSIGNED NULL DEFAULT NULL,
  `decided_at`            TIMESTAMP       NULL DEFAULT NULL,
  `created_at`            TIMESTAMP       NULL DEFAULT NULL,
  `updated_at`            TIMESTAMP       NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_correction_ulid` (`ulid`),
  KEY `idx_correction_record` (`attendance_record_id`),
  KEY `idx_correction_status` (`status`),
  CONSTRAINT `fk_correction_record` FOREIGN KEY (`attendance_record_id`) REFERENCES `attendance_records` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
