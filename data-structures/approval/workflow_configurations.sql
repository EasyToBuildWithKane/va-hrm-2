-- Module: approval
-- Purpose: Dinh nghia workflow theo workflow_type (steps, role, SLA, escalation, …) duoi dang JSON.
-- Related: approval_workflows.workflow_type
-- Legacy origin: n/a

CREATE TABLE `workflow_configurations` (
  `id`            BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `workflow_type` VARCHAR(100)    NOT NULL,
  `config`        JSON            NOT NULL COMMENT 'steps[], escalation_after_hours, escalate_to_role, conditions',
  `version`       INT UNSIGNED    NOT NULL DEFAULT 1,
  `is_active`     TINYINT(1)      NOT NULL DEFAULT 1,
  `created_by`    BIGINT UNSIGNED NULL DEFAULT NULL,
  `created_at`    TIMESTAMP       NULL DEFAULT NULL,
  `updated_at`    TIMESTAMP       NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_workflow_config_type_version` (`workflow_type`, `version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
