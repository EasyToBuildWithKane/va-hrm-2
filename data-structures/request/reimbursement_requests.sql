-- Module: request
-- Purpose: Yeu cau hoan tien (cong tac phi, mua sam ho cong ty, …).
-- Related: workflow_requests (polymorphic)
-- Legacy origin: n/a

CREATE TABLE `reimbursement_requests` (
  `id`           BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `category`     VARCHAR(100)    NOT NULL COMMENT 'travel, meal, supply, training, …',
  `amount`       DECIMAL(15,2)   NOT NULL,
  `currency`     CHAR(3)         NOT NULL DEFAULT 'VND',
  `expense_date` DATE            NOT NULL,
  `receipt_url`  VARCHAR(500)    DEFAULT NULL,
  `merchant`     VARCHAR(255)    DEFAULT NULL,
  `notes`        TEXT            DEFAULT NULL,
  `created_at`   TIMESTAMP       NULL DEFAULT NULL,
  `updated_at`   TIMESTAMP       NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_reimbursement_date` (`expense_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
