-- Module: leave
-- Purpose: Quy che phep theo dieu kien (vi du: > 3 nam tham nien thi +2 ngay annual).
-- Related: leave_types.id
-- Legacy origin: n/a

CREATE TABLE `leave_policies` (
  `id`             BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name`           VARCHAR(255)    NOT NULL,
  `leave_type_id`  BIGINT UNSIGNED NOT NULL,
  `conditions`     JSON            NOT NULL COMMENT 'tenure_years, employment_type, dept, …',
  `bonus_days`     DECIMAL(5,2)    NOT NULL DEFAULT 0,
  `effective_from` DATE            NULL DEFAULT NULL,
  `effective_to`   DATE            NULL DEFAULT NULL,
  `is_active`      TINYINT(1)      NOT NULL DEFAULT 1,
  `created_at`     TIMESTAMP       NULL DEFAULT NULL,
  `updated_at`     TIMESTAMP       NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_leave_policy_type` (`leave_type_id`),
  CONSTRAINT `fk_leave_policy_type` FOREIGN KEY (`leave_type_id`) REFERENCES `leave_types` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
