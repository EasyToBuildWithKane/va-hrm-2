-- Module: contribution
-- Purpose: Yeu cau chinh sua diem (manual adjustment) — co the gan vao approval_workflows.
-- Related: employees.id, users.id (requested_by), approval_workflows.id
-- Legacy origin: n/a

CREATE TABLE `score_adjustment_requests` (
  `id`             BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `ulid`           CHAR(26)        NOT NULL,
  `employee_id`    BIGINT UNSIGNED NOT NULL,
  `requested_by`   BIGINT UNSIGNED NOT NULL,
  `workflow_id`    BIGINT UNSIGNED NULL DEFAULT NULL,
  `delta_points`   DECIMAL(10,2)   NOT NULL COMMENT 'Co the am de tru',
  `reason`         TEXT            NOT NULL,
  `evidence_url`   VARCHAR(500)    DEFAULT NULL,
  `status`         ENUM('pending','approved','rejected','applied','cancelled') NOT NULL DEFAULT 'pending',
  `applied_at`     TIMESTAMP       NULL DEFAULT NULL,
  `created_at`     TIMESTAMP       NULL DEFAULT NULL,
  `updated_at`     TIMESTAMP       NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_score_adj_ulid` (`ulid`),
  KEY `idx_score_adj_employee` (`employee_id`),
  KEY `idx_score_adj_status`   (`status`),
  CONSTRAINT `fk_score_adj_employee` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
