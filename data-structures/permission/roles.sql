-- Module: permission
-- Purpose: Vai trò (Spatie-compatible). Bao gồm cấp bậc theo 10_RBAC_PERMISSIONS.md.
-- Related: model_has_roles.role_id, role_has_permissions.role_id
-- Legacy origin: n/a

CREATE TABLE `roles` (
  `id`            BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name`          VARCHAR(125)    NOT NULL COMMENT 'super_admin, hr_director, hr_staff, dept_manager, …',
  `display_name`  VARCHAR(255)    NOT NULL,
  `guard_name`    VARCHAR(125)    NOT NULL DEFAULT 'web',
  `level`         INT UNSIGNED    NOT NULL DEFAULT 100 COMMENT 'Lower = higher in hierarchy (Super Admin=0)',
  `parent_id`     BIGINT UNSIGNED NULL DEFAULT NULL COMMENT 'Self-ref hierarchy',
  `is_system`     TINYINT(1)      NOT NULL DEFAULT 0 COMMENT 'System role không cho xóa',
  `created_at`    TIMESTAMP       NULL DEFAULT NULL,
  `updated_at`    TIMESTAMP       NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_roles_name_guard` (`name`, `guard_name`),
  KEY `idx_roles_parent` (`parent_id`),
  CONSTRAINT `fk_roles_parent` FOREIGN KEY (`parent_id`) REFERENCES `roles` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
