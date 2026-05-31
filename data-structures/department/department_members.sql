-- Module: department
-- Purpose: Membership phu — cho phep nhan vien thuoc them phong ban khac (concurrent / project).
-- Related: departments.id, employees.id
-- Legacy origin: _legacy/user_info.sql (concurrent_position_name)

CREATE TABLE `department_members` (
  `id`             BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `department_id`  BIGINT UNSIGNED NOT NULL,
  `employee_id`    BIGINT UNSIGNED NOT NULL,
  `role`           VARCHAR(100)    DEFAULT NULL COMMENT 'member, lead, deputy, …',
  `is_primary`     TINYINT(1)      NOT NULL DEFAULT 0 COMMENT 'TRUE = primary dept (= employees.department_id)',
  `assigned_at`    TIMESTAMP       NULL DEFAULT NULL,
  `ended_at`       TIMESTAMP       NULL DEFAULT NULL,
  `created_at`     TIMESTAMP       NULL DEFAULT NULL,
  `updated_at`     TIMESTAMP       NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_dept_member_active` (`department_id`, `employee_id`, `ended_at`),
  KEY `idx_dept_member_employee` (`employee_id`),
  CONSTRAINT `fk_dept_member_department` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_dept_member_employee`   FOREIGN KEY (`employee_id`)   REFERENCES `employees` (`id`)   ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
