-- Module: provisioning
-- Purpose: Specialization cho account_provisions.account_type='email' — luu MX/alias chi tiet.
-- Related: account_provisions.id, employees.id
-- Legacy origin: n/a

CREATE TABLE `email_provisions` (
  `id`                   BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `account_provision_id` BIGINT UNSIGNED NOT NULL,
  `email_address`        VARCHAR(255)    NOT NULL,
  `aliases`              JSON            DEFAULT NULL,
  `mailbox_quota_mb`     INT UNSIGNED    DEFAULT NULL,
  `forwarding_address`   VARCHAR(255)    DEFAULT NULL,
  `mx_provider`          VARCHAR(100)    DEFAULT NULL COMMENT 'google_workspace, microsoft365, …',
  `created_at`           TIMESTAMP       NULL DEFAULT NULL,
  `updated_at`           TIMESTAMP       NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_email_provision_address` (`email_address`),
  UNIQUE KEY `uq_email_provision_account` (`account_provision_id`),
  CONSTRAINT `fk_email_provision_account` FOREIGN KEY (`account_provision_id`) REFERENCES `account_provisions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
