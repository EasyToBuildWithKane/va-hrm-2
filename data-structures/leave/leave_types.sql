-- Module: leave
-- Purpose: Loai phep (annual, sick, maternity, …) va policy mac dinh.
-- Related: leave_quotas.leave_type_id, leave_requests.leave_type_id, leave_policies.leave_type_id
-- Legacy origin: n/a

CREATE TABLE `leave_types` (
  `id`              BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name`            VARCHAR(100)    NOT NULL,
  `code`            VARCHAR(20)     NOT NULL,
  `days_per_year`   DECIMAL(5,2)    NOT NULL,
  `is_paid`         TINYINT(1)      NOT NULL DEFAULT 1,
  `carry_forward`   TINYINT(1)      NOT NULL DEFAULT 0,
  `max_carry_days`  DECIMAL(5,2)    NOT NULL DEFAULT 0,
  `requires_docs`   TINYINT(1)      NOT NULL DEFAULT 0,
  `min_notice_days` INT             NOT NULL DEFAULT 0,
  `created_at`      TIMESTAMP       NULL DEFAULT NULL,
  `updated_at`      TIMESTAMP       NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_leave_types_code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
