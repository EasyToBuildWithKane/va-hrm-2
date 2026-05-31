-- Module: employee
-- Purpose: Work identity của nhân sự. 1 user có thể có 0..1 employee.
-- Related: users.id, departments.id, positions.id (manager_id self-ref)
-- Legacy origin: _legacy/user_info.sql (code, department_id, company_id, start_working_date, note)
--                _legacy/users.sql (name → first_name/last_name, email)

CREATE TABLE `employees` (
  `id`                  BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `ulid`                CHAR(26)        NOT NULL,
  `user_id`             BIGINT UNSIGNED NOT NULL,
  `employee_number`     VARCHAR(20)     NOT NULL COMMENT 'Was user_info.code',
  `department_id`       BIGINT UNSIGNED NULL DEFAULT NULL COMMENT 'Legacy user_info.department_id nullable',
  `company_id`          BIGINT UNSIGNED NULL DEFAULT NULL COMMENT 'Was user_info.company_id; FK companies (deferred)',
  `position_id`         BIGINT UNSIGNED NULL DEFAULT NULL,
  `manager_id`          BIGINT UNSIGNED NULL DEFAULT NULL COMMENT 'Self-ref reporting line',
  `first_name`          VARCHAR(100)    NULL DEFAULT NULL COMMENT 'Split tu users.name khi migrate',
  `last_name`           VARCHAR(100)    NULL DEFAULT NULL COMMENT 'Split tu users.name khi migrate',
  `email`               VARCHAR(255)    NOT NULL COMMENT 'Work email; mac dinh = users.email (co the khac)',
  `phone`               VARCHAR(20)     DEFAULT NULL,
  `employment_type`     ENUM('full_time','part_time','contract','intern') NOT NULL DEFAULT 'full_time',
  `employment_status`   ENUM('active','inactive','on_leave','terminated','resigned') NOT NULL DEFAULT 'active',
  `join_date`           DATE            NOT NULL COMMENT 'Was user_info.start_working_date',
  `probation_end_date`  DATE            NULL DEFAULT NULL,
  `termination_date`    DATE            NULL DEFAULT NULL,
  `onboarding_status`   ENUM('pending','in_progress','completed') NOT NULL DEFAULT 'pending',
  `offboarding_status`  ENUM('none','in_progress','completed')    NOT NULL DEFAULT 'none',
  `metadata`            JSON            DEFAULT NULL COMMENT 'Was user_info.note + free-form extras',
  `created_by`          BIGINT UNSIGNED NULL DEFAULT NULL,
  `updated_by`          BIGINT UNSIGNED NULL DEFAULT NULL,
  `deleted_by`          BIGINT UNSIGNED NULL DEFAULT NULL,
  `created_at`          TIMESTAMP       NULL DEFAULT NULL,
  `updated_at`          TIMESTAMP       NULL DEFAULT NULL,
  `deleted_at`          TIMESTAMP       NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_employees_ulid`            (`ulid`),
  UNIQUE KEY `uq_employees_employee_number` (`employee_number`),
  UNIQUE KEY `uq_employees_user`            (`user_id`),
  KEY `idx_employees_department` (`department_id`),
  KEY `idx_employees_manager`    (`manager_id`),
  KEY `idx_employees_status`     (`employment_status`),
  CONSTRAINT `fk_employees_user`       FOREIGN KEY (`user_id`)       REFERENCES `users` (`id`),
  CONSTRAINT `fk_employees_department` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`),
  CONSTRAINT `fk_employees_manager`    FOREIGN KEY (`manager_id`)    REFERENCES `employees` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
