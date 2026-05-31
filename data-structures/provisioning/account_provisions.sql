-- Module: provisioning
-- Purpose: Tai khoan da duoc cap (email, system, software, device) cho nhan vien.
-- Related: employees.id, provisioning_requests.id
-- Legacy origin: n/a

CREATE TABLE `account_provisions` (
  `id`                      BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `employee_id`             BIGINT UNSIGNED NOT NULL,
  `provisioning_request_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `account_type`            ENUM('email','system','software','device') NOT NULL,
  `account_identifier`      VARCHAR(255)    NOT NULL COMMENT 'email address hoac system username',
  `status`                  ENUM('pending','active','suspended','disabled','revoked') NOT NULL DEFAULT 'pending',
  `activated_at`            TIMESTAMP       NULL DEFAULT NULL,
  `suspended_at`            TIMESTAMP       NULL DEFAULT NULL,
  `revoked_at`              TIMESTAMP       NULL DEFAULT NULL,
  `metadata`                JSON            DEFAULT NULL,
  `created_at`              TIMESTAMP       NULL DEFAULT NULL,
  `updated_at`              TIMESTAMP       NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_account_provision_employee` (`employee_id`),
  KEY `idx_account_provision_status`   (`status`),
  CONSTRAINT `fk_account_provision_employee` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`),
  CONSTRAINT `fk_account_provision_request`  FOREIGN KEY (`provisioning_request_id`) REFERENCES `provisioning_requests` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
