-- Module: request
-- Purpose: Yeu cau dieu chinh luong (tang luong, thay doi phu cap).
-- Related: workflow_requests (polymorphic), employees.id (target)
-- Legacy origin: n/a

CREATE TABLE `salary_adjustment_requests` (
  `id`                  BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `target_employee_id`  BIGINT UNSIGNED NOT NULL,
  `current_salary`      DECIMAL(15,2)   NOT NULL,
  `proposed_salary`     DECIMAL(15,2)   NOT NULL,
  `currency`            CHAR(3)         NOT NULL DEFAULT 'VND',
  `adjustment_reason`   VARCHAR(100)    NOT NULL COMMENT 'promotion, market_alignment, annual_review, …',
  `justification`       TEXT            DEFAULT NULL,
  `effective_from`      DATE            NOT NULL,
  `created_at`          TIMESTAMP       NULL DEFAULT NULL,
  `updated_at`          TIMESTAMP       NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_salary_adj_target` (`target_employee_id`),
  CONSTRAINT `fk_salary_adj_target` FOREIGN KEY (`target_employee_id`) REFERENCES `employees` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
