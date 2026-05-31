-- Module: contribution
-- Purpose: Tong diem hien tai / rank (denormalized). Hấp thụ point/contribution_point/level tu legacy users.
-- Related: employees.id
-- Legacy origin: _legacy/users.sql (point, contribution_point, level)

CREATE TABLE `contribution_scores` (
  `id`                 BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `employee_id`        BIGINT UNSIGNED NOT NULL,
  `total_points`       DECIMAL(12,2)   NOT NULL DEFAULT 0,
  `monthly_points`     DECIMAL(10,2)   NOT NULL DEFAULT 0,
  `quarterly_points`   DECIMAL(10,2)   NOT NULL DEFAULT 0,
  `yearly_points`      DECIMAL(10,2)   NOT NULL DEFAULT 0,
  `level`              VARCHAR(50)     DEFAULT NULL COMMENT 'Tu legacy users.level, derived tu total_points',
  `rank_overall`       INT UNSIGNED    NULL DEFAULT NULL,
  `rank_department`    INT UNSIGNED    NULL DEFAULT NULL,
  `last_calculated_at` TIMESTAMP       NULL DEFAULT NULL,
  `updated_at`         TIMESTAMP       NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_contrib_score_employee` (`employee_id`),
  KEY `idx_contrib_score_total` (`total_points`),
  KEY `idx_contrib_score_rank`  (`rank_overall`),
  CONSTRAINT `fk_contrib_score_employee` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
