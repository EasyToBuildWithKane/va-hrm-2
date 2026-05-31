-- Module: permission
-- Purpose: Ủy quyền permission có thời hạn (vd manager nghỉ phép, delegate cho deputy).
-- Related: users.id (delegator/delegatee), permissions.id, roles.id
-- Legacy origin: n/a

CREATE TABLE `permission_delegations` (
  `id`            BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `delegator_id`  BIGINT UNSIGNED NOT NULL COMMENT 'User ủy quyền',
  `delegatee_id`  BIGINT UNSIGNED NOT NULL COMMENT 'User nhận ủy quyền',
  `role_id`       BIGINT UNSIGNED NULL DEFAULT NULL,
  `permission_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `scope`         JSON            DEFAULT NULL COMMENT 'Module/department scope filter',
  `reason`        TEXT            DEFAULT NULL,
  `valid_from`    TIMESTAMP       NOT NULL,
  `valid_until`   TIMESTAMP       NOT NULL,
  `is_active`     TINYINT(1)      NOT NULL DEFAULT 1,
  `created_at`    TIMESTAMP       NULL DEFAULT NULL,
  `updated_at`    TIMESTAMP       NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_deleg_delegator` (`delegator_id`),
  KEY `idx_deleg_delegatee` (`delegatee_id`),
  KEY `idx_deleg_validity`  (`valid_from`, `valid_until`),
  CONSTRAINT `fk_deleg_delegator`  FOREIGN KEY (`delegator_id`)  REFERENCES `users` (`id`)       ON DELETE CASCADE,
  CONSTRAINT `fk_deleg_delegatee`  FOREIGN KEY (`delegatee_id`)  REFERENCES `users` (`id`)       ON DELETE CASCADE,
  CONSTRAINT `fk_deleg_role`       FOREIGN KEY (`role_id`)       REFERENCES `roles` (`id`)       ON DELETE CASCADE,
  CONSTRAINT `fk_deleg_permission` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
