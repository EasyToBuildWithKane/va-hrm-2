-- Module: approval
-- Purpose: Audit-friendly log moi decision (1 step co the co nhieu decision neu reassign / re-review).
-- Related: approval_steps.id, users.id (decided_by)
-- Legacy origin: n/a

CREATE TABLE `approval_decisions` (
  `id`               BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `step_id`          BIGINT UNSIGNED NOT NULL,
  `decided_by`       BIGINT UNSIGNED NOT NULL,
  `decision`         ENUM('approve','reject','request_changes','delegate','skip') NOT NULL,
  `comment`          TEXT            DEFAULT NULL,
  `decided_at`       TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `metadata`         JSON            DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_decision_step`     (`step_id`),
  KEY `idx_decision_decider`  (`decided_by`),
  CONSTRAINT `fk_decision_step` FOREIGN KEY (`step_id`) REFERENCES `approval_steps` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
