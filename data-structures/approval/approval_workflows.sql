-- Module: approval
-- Purpose: Instance workflow phe duyet — gan polymorphic vao bat ki requestable nao.
-- Related: approval_steps.workflow_id, workflow_configurations.workflow_type
-- Legacy origin: n/a

CREATE TABLE `approval_workflows` (
  `id`               BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `ulid`             CHAR(26)        NOT NULL,
  `requestable_type` VARCHAR(100)    NOT NULL,
  `requestable_id`   BIGINT UNSIGNED NOT NULL,
  `workflow_type`    VARCHAR(100)    NOT NULL COMMENT 'leave_request, equipment_request, …',
  `current_step`     INT UNSIGNED    NOT NULL DEFAULT 1,
  `total_steps`      INT UNSIGNED    NOT NULL,
  `status`           ENUM('pending','in_progress','approved','rejected','cancelled','escalated') NOT NULL DEFAULT 'pending',
  `sla_deadline_at`  TIMESTAMP       NULL DEFAULT NULL,
  `completed_at`     TIMESTAMP       NULL DEFAULT NULL,
  `created_by`       BIGINT UNSIGNED NOT NULL,
  `created_at`       TIMESTAMP       NULL DEFAULT NULL,
  `updated_at`       TIMESTAMP       NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_workflow_ulid` (`ulid`),
  KEY `idx_workflow_requestable` (`requestable_type`, `requestable_id`),
  KEY `idx_workflow_status`      (`status`),
  KEY `idx_workflow_created_by`  (`created_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
