-- Module: provisioning
-- Purpose: Log step-by-step cua provisioning engine (gọi API, retry, success/failure).
-- Related: provisioning_requests.id, account_provisions.id
-- Legacy origin: n/a

CREATE TABLE `provisioning_logs` (
  `id`                      BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `provisioning_request_id` BIGINT UNSIGNED NOT NULL,
  `account_provision_id`    BIGINT UNSIGNED NULL DEFAULT NULL,
  `action`                  VARCHAR(100)    NOT NULL COMMENT 'create_email, assign_license, revoke, …',
  `provider`                VARCHAR(50)     DEFAULT NULL COMMENT 'google, microsoft, internal, …',
  `status`                  ENUM('success','failure','retry','skipped') NOT NULL,
  `request_payload`         JSON            DEFAULT NULL,
  `response_payload`        JSON            DEFAULT NULL,
  `error_message`           TEXT            DEFAULT NULL,
  `duration_ms`             INT UNSIGNED    DEFAULT NULL,
  `created_at`              TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_prov_log_request` (`provisioning_request_id`),
  KEY `idx_prov_log_action`  (`action`),
  KEY `idx_prov_log_status`  (`status`),
  CONSTRAINT `fk_prov_log_request` FOREIGN KEY (`provisioning_request_id`) REFERENCES `provisioning_requests` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
