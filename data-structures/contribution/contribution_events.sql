-- Module: contribution
-- Purpose: Moi event cong diem (immutable). Tham chieu polymorphic den source (task, leave, …).
-- Related: employees.id, scoring_rules.id
-- Legacy origin: n/a

CREATE TABLE `contribution_events` (
  `id`              BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `employee_id`     BIGINT UNSIGNED NOT NULL,
  `rule_id`         BIGINT UNSIGNED NOT NULL,
  `event_type`      VARCHAR(100)    NOT NULL,
  `points_earned`   DECIMAL(10,2)   NOT NULL,
  `reference_type`  VARCHAR(100)    NULL DEFAULT NULL,
  `reference_id`    BIGINT UNSIGNED NULL DEFAULT NULL,
  `description`     TEXT            DEFAULT NULL,
  `occurred_at`     TIMESTAMP       NOT NULL,
  `created_at`      TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_contrib_event_employee` (`employee_id`),
  KEY `idx_contrib_event_occurred` (`occurred_at`),
  KEY `idx_contrib_event_reference` (`reference_type`, `reference_id`),
  CONSTRAINT `fk_contrib_event_employee` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_contrib_event_rule`     FOREIGN KEY (`rule_id`)     REFERENCES `scoring_rules` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
