-- Module: approval
-- Purpose: Tung buoc trong workflow — luu approver/role + SLA + ket qua.
-- Related: approval_workflows.id, users.id (approver)
-- Legacy origin: n/a

CREATE TABLE `approval_steps` (
  `id`              BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `workflow_id`     BIGINT UNSIGNED NOT NULL,
  `step_number`     INT UNSIGNED    NOT NULL,
  `approver_id`     BIGINT UNSIGNED NULL DEFAULT NULL,
  `approver_role`   VARCHAR(100)    NULL DEFAULT NULL,
  `status`          ENUM('pending','approved','rejected','skipped','delegated') NOT NULL DEFAULT 'pending',
  `decision_at`     TIMESTAMP       NULL DEFAULT NULL,
  `notes`           TEXT            DEFAULT NULL,
  `delegated_to_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `sla_hours`       INT UNSIGNED    NOT NULL DEFAULT 24,
  `sla_deadline_at` TIMESTAMP       NULL DEFAULT NULL,
  `created_at`      TIMESTAMP       NULL DEFAULT NULL,
  `updated_at`      TIMESTAMP       NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_workflow_step` (`workflow_id`, `step_number`),
  KEY `idx_step_approver` (`approver_id`),
  KEY `idx_step_status`   (`status`),
  CONSTRAINT `fk_step_workflow` FOREIGN KEY (`workflow_id`) REFERENCES `approval_workflows` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
