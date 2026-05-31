-- Module: audit
-- Purpose: Immutable audit log — polymorphic theo auditable. KHONG co updated_at / deleted_at.
-- Related: bat ki bang nao qua (auditable_type, auditable_id); users.id (performed_by)
-- Legacy origin: n/a

CREATE TABLE `audit_logs` (
  `id`             BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `ulid`           CHAR(26)        NOT NULL,
  `auditable_type` VARCHAR(100)    NOT NULL,
  `auditable_id`   BIGINT UNSIGNED NOT NULL,
  `event`          ENUM('created','updated','deleted','restored','approved',
                        'rejected','assigned','revoked','activated','deactivated') NOT NULL,
  `old_values`     JSON            DEFAULT NULL,
  `new_values`     JSON            DEFAULT NULL,
  `changed_fields` JSON            DEFAULT NULL COMMENT "['field1','field2']",
  `performed_by`   BIGINT UNSIGNED NOT NULL,
  `ip_address`     VARCHAR(45)     DEFAULT NULL,
  `user_agent`     VARCHAR(500)    DEFAULT NULL,
  `context`        JSON            DEFAULT NULL,
  `created_at`     TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_audit_logs_ulid` (`ulid`),
  KEY `idx_audit_auditable`   (`auditable_type`, `auditable_id`),
  KEY `idx_audit_performed_by` (`performed_by`),
  KEY `idx_audit_event`        (`event`),
  KEY `idx_audit_created_at`   (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
