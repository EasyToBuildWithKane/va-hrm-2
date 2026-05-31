-- Module: request
-- Purpose: Yeu cau cap quyen su dung phan mem co license.
-- Related: workflow_requests (polymorphic), software_licenses.id
-- Legacy origin: n/a

CREATE TABLE `software_access_requests` (
  `id`                  BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `software_license_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `software_name`       VARCHAR(255)    NOT NULL,
  `access_level`        VARCHAR(50)     DEFAULT NULL COMMENT 'viewer, editor, admin',
  `business_reason`     TEXT            DEFAULT NULL,
  `duration_days`       INT UNSIGNED    NULL DEFAULT NULL,
  `created_at`          TIMESTAMP       NULL DEFAULT NULL,
  `updated_at`          TIMESTAMP       NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_software_access_license` (`software_license_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
