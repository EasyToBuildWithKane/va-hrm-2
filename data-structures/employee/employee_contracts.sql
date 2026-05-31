-- Module: employee
-- Purpose: Hop dong lao dong cua nhan vien (1 employee co nhieu contract qua thoi gian).
-- Related: employees.id
-- Legacy origin: n/a

CREATE TABLE `employee_contracts` (
  `id`               BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `ulid`             CHAR(26)        NOT NULL,
  `employee_id`      BIGINT UNSIGNED NOT NULL,
  `contract_number`  VARCHAR(50)     NOT NULL,
  `contract_type`    ENUM('probation','definite','indefinite','seasonal','intern') NOT NULL,
  `start_date`       DATE            NOT NULL,
  `end_date`         DATE            NULL DEFAULT NULL,
  `base_salary`      DECIMAL(15,2)   DEFAULT NULL,
  `currency`         CHAR(3)         NOT NULL DEFAULT 'VND',
  `status`           ENUM('draft','active','expired','terminated') NOT NULL DEFAULT 'draft',
  `document_url`     VARCHAR(500)    DEFAULT NULL,
  `signed_at`        TIMESTAMP       NULL DEFAULT NULL,
  `metadata`         JSON            DEFAULT NULL,
  `created_by`       BIGINT UNSIGNED NULL DEFAULT NULL,
  `created_at`       TIMESTAMP       NULL DEFAULT NULL,
  `updated_at`       TIMESTAMP       NULL DEFAULT NULL,
  `deleted_at`       TIMESTAMP       NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_contracts_ulid`   (`ulid`),
  UNIQUE KEY `uq_contracts_number` (`contract_number`),
  KEY `idx_contracts_employee` (`employee_id`),
  KEY `idx_contracts_status`   (`status`),
  KEY `idx_contracts_dates`    (`start_date`, `end_date`),
  CONSTRAINT `fk_contracts_employee` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
