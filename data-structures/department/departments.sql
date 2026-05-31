-- Module: department
-- Purpose: Phong ban / don vi to chuc, ho tro hierarchy (parent_id self-ref).
-- Related: departments.parent_id, employees.id (manager_id)
-- Legacy origin: _legacy/user_info.sql (department_id, department_name, unit_name, headquarter_name)

CREATE TABLE `departments` (
  `id`              BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `ulid`            CHAR(26)        NOT NULL,
  `name`            VARCHAR(255)    NOT NULL,
  `code`            VARCHAR(20)     NOT NULL,
  `parent_id`       BIGINT UNSIGNED NULL DEFAULT NULL,
  `manager_id`      BIGINT UNSIGNED NULL DEFAULT NULL,
  `headcount_limit` INT UNSIGNED    NULL DEFAULT NULL,
  `is_active`       TINYINT(1)      NOT NULL DEFAULT 1,
  `metadata`        JSON            DEFAULT NULL,
  `created_at`     TIMESTAMP       NULL DEFAULT NULL,
  `updated_at`     TIMESTAMP       NULL DEFAULT NULL,
  `deleted_at`     TIMESTAMP       NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_departments_ulid` (`ulid`),
  UNIQUE KEY `uq_departments_code` (`code`),
  KEY `idx_departments_parent` (`parent_id`),
  CONSTRAINT `fk_departments_parent`  FOREIGN KEY (`parent_id`)  REFERENCES `departments` (`id`),
  CONSTRAINT `fk_departments_manager` FOREIGN KEY (`manager_id`) REFERENCES `employees` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
