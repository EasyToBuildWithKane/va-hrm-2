-- Module: employee
-- Purpose: Thông tin nhân thân / PII (1-1 với employees). Tách riêng để cô lập field nhạy cảm.
-- Related: employees.id
-- Legacy origin: _legacy/user_info.sql (gender, birthdate, birth_place, national, religion, hometown)

CREATE TABLE `employee_personal_info` (
  `id`          BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `employee_id` BIGINT UNSIGNED NOT NULL,
  `gender`      TINYINT UNSIGNED DEFAULT 1 COMMENT '1: Nam, 0: Nu',
  `birthdate`   DATETIME        DEFAULT NULL,
  `birth_place` VARCHAR(255)    DEFAULT NULL,
  `national`    VARCHAR(255)    DEFAULT NULL,
  `religion`    VARCHAR(255)    DEFAULT NULL,
  `hometown`    VARCHAR(255)    DEFAULT NULL,
  `created_at`  TIMESTAMP       NULL DEFAULT NULL,
  `updated_at`  TIMESTAMP       NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_personal_employee` (`employee_id`),
  CONSTRAINT `fk_personal_employee` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
