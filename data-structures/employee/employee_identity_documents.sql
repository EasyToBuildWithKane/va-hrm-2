-- Module: employee
-- Purpose: Giay to tuy than + ma so chinh phu (CCCD, thue, BHXH, BHYT, BHTN).
-- Related: employees.id
-- Legacy origin: _legacy/user_info.sql (identity, identity_date, identity_place, tax_code,
--                                       social_insurance_number, health_insurance_code,
--                                       unemployment_insurance_number)

CREATE TABLE `employee_identity_documents` (
  `id`                            BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `employee_id`                   BIGINT UNSIGNED NOT NULL,
  `identity_number`               VARCHAR(255)    DEFAULT NULL COMMENT 'CCCD / CMND',
  `identity_issue_date`           TIMESTAMP       NULL DEFAULT NULL,
  `identity_issue_place`          VARCHAR(255)    DEFAULT NULL,
  `tax_code`                      VARCHAR(255)    DEFAULT NULL,
  `social_insurance_number`       VARCHAR(50)     DEFAULT NULL COMMENT 'BHXH',
  `health_insurance_code`         VARCHAR(50)     DEFAULT NULL COMMENT 'BHYT',
  `unemployment_insurance_number` VARCHAR(50)     DEFAULT NULL COMMENT 'BHTN',
  `created_at`                    TIMESTAMP       NULL DEFAULT NULL,
  `updated_at`                    TIMESTAMP       NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_identity_employee` (`employee_id`),
  KEY `idx_identity_number` (`identity_number`),
  KEY `idx_identity_tax`    (`tax_code`),
  CONSTRAINT `fk_identity_employee` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
