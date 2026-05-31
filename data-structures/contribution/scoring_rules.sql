-- Module: contribution
-- Purpose: Cau hinh quy tac tinh diem (event_type -> base_points * multiplier).
-- Related: contribution_events.rule_id
-- Legacy origin: n/a

CREATE TABLE `scoring_rules` (
  `id`          BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name`        VARCHAR(255)    NOT NULL,
  `event_type`  VARCHAR(100)    NOT NULL COMMENT 'task_completed, overtime_contribution, peer_recognition, …',
  `base_points` DECIMAL(10,2)   NOT NULL,
  `multiplier`  DECIMAL(5,2)    NOT NULL DEFAULT 1.00,
  `conditions`  JSON            DEFAULT NULL,
  `is_active`   TINYINT(1)      NOT NULL DEFAULT 1,
  `created_at`  TIMESTAMP       NULL DEFAULT NULL,
  `updated_at`  TIMESTAMP       NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_scoring_rules_event` (`event_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
