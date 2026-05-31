-- Module: provisioning
-- Purpose: License phan mem dang sở hữu cua cong ty (pool of seats).
-- Related: employee_software_licenses.software_license_id
-- Legacy origin: n/a

CREATE TABLE `software_licenses` (
  `id`           BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name`         VARCHAR(255)    NOT NULL,
  `vendor`       VARCHAR(255)    DEFAULT NULL,
  `license_key`  VARCHAR(500)    DEFAULT NULL,
  `total_seats`  INT UNSIGNED    NOT NULL,
  `used_seats`   INT UNSIGNED    NOT NULL DEFAULT 0,
  `expires_at`   DATE            NULL DEFAULT NULL,
  `metadata`     JSON            DEFAULT NULL,
  `created_at`   TIMESTAMP       NULL DEFAULT NULL,
  `updated_at`   TIMESTAMP       NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_software_license_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
