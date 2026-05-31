-- Module: permission
-- Purpose: Permission atom. Naming: {module}.{action}[.{scope}] (xem 10_RBAC_PERMISSIONS.md §2).
-- Related: role_has_permissions.permission_id, model_has_permissions.permission_id
-- Legacy origin: n/a

CREATE TABLE `permissions` (
  `id`           BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name`         VARCHAR(125)    NOT NULL COMMENT 'e.g. employee.view, employee.view.own',
  `display_name` VARCHAR(255)    NOT NULL,
  `module`       VARCHAR(50)     NOT NULL COMMENT 'employee, leave, approval, …',
  `action`       VARCHAR(50)     NOT NULL COMMENT 'view, create, update, delete, approve, …',
  `scope`        VARCHAR(50)     DEFAULT NULL COMMENT 'own, department, all',
  `guard_name`   VARCHAR(125)    NOT NULL DEFAULT 'web',
  `created_at`   TIMESTAMP       NULL DEFAULT NULL,
  `updated_at`   TIMESTAMP       NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_permissions_name_guard` (`name`, `guard_name`),
  KEY `idx_permissions_module` (`module`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
