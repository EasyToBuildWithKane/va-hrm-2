-- Module: request
-- Purpose: Base polymorphic table cho moi request can workflow (xem 02_FOLDER_STRUCTURE.md modules/Request).
-- Related: employees.id (requester), approval_workflows.id
-- Legacy origin: n/a

CREATE TABLE `workflow_requests` (
  `id`                BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `ulid`              CHAR(26)        NOT NULL,
  `requestable_type`  VARCHAR(100)    NOT NULL COMMENT 'EquipmentRequest, AccountRequest, …',
  `requestable_id`    BIGINT UNSIGNED NOT NULL,
  `request_type`      VARCHAR(100)    NOT NULL,
  `requester_id`      BIGINT UNSIGNED NOT NULL,
  `workflow_id`       BIGINT UNSIGNED NULL DEFAULT NULL,
  `status`            ENUM('draft','submitted','pending','approved','rejected','cancelled','completed') NOT NULL DEFAULT 'draft',
  `priority`          ENUM('low','normal','high','urgent') NOT NULL DEFAULT 'normal',
  `subject`           VARCHAR(255)    NOT NULL,
  `description`       TEXT            DEFAULT NULL,
  `metadata`          JSON            DEFAULT NULL,
  `submitted_at`      TIMESTAMP       NULL DEFAULT NULL,
  `completed_at`      TIMESTAMP       NULL DEFAULT NULL,
  `created_at`        TIMESTAMP       NULL DEFAULT NULL,
  `updated_at`        TIMESTAMP       NULL DEFAULT NULL,
  `deleted_at`        TIMESTAMP       NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_workflow_request_ulid` (`ulid`),
  KEY `idx_workflow_request_polymorphic` (`requestable_type`, `requestable_id`),
  KEY `idx_workflow_request_requester`   (`requester_id`),
  KEY `idx_workflow_request_status`      (`status`),
  CONSTRAINT `fk_workflow_request_requester` FOREIGN KEY (`requester_id`) REFERENCES `employees` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
