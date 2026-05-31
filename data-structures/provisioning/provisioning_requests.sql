-- Module: provisioning
-- Purpose: Cao nhat — yeu cau provision/deprovision toan bo gói tai khoan, license, …
-- Related: employees.id, approval_workflows.id
-- Legacy origin: n/a

CREATE TABLE `provisioning_requests` (
  `id`           BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `ulid`         CHAR(26)        NOT NULL,
  `employee_id`  BIGINT UNSIGNED NOT NULL,
  `workflow_id`  BIGINT UNSIGNED NULL DEFAULT NULL,
  `type`         ENUM('onboarding','offboarding','access_change','license_assign') NOT NULL,
  `status`       ENUM('pending','approved','active','suspended','disabled','revoked') NOT NULL DEFAULT 'pending',
  `requested_by` BIGINT UNSIGNED NOT NULL,
  `processed_by` BIGINT UNSIGNED NULL DEFAULT NULL,
  `processed_at` TIMESTAMP       NULL DEFAULT NULL,
  `metadata`     JSON            DEFAULT NULL,
  `created_at`   TIMESTAMP       NULL DEFAULT NULL,
  `updated_at`   TIMESTAMP       NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_prov_request_ulid` (`ulid`),
  KEY `idx_prov_request_employee` (`employee_id`),
  KEY `idx_prov_request_status`   (`status`),
  CONSTRAINT `fk_prov_request_employee` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
