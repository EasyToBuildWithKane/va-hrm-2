-- Module: approval
-- Purpose: Approver A uy quyen approve cho B trong khoang thoi gian (vd nghi phep).
--          Khac voi permission_delegations o cho: chi cho approval workflow, co the scope theo workflow_type.
-- Related: users.id (delegator/delegatee)
-- Legacy origin: n/a

CREATE TABLE `approval_delegations` (
  `id`            BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `delegator_id`  BIGINT UNSIGNED NOT NULL,
  `delegatee_id`  BIGINT UNSIGNED NOT NULL,
  `workflow_type` VARCHAR(100)    NULL DEFAULT NULL COMMENT 'NULL = all workflow types',
  `valid_from`    TIMESTAMP       NOT NULL,
  `valid_until`   TIMESTAMP       NOT NULL,
  `reason`        TEXT            DEFAULT NULL,
  `is_active`     TINYINT(1)      NOT NULL DEFAULT 1,
  `created_at`    TIMESTAMP       NULL DEFAULT NULL,
  `updated_at`    TIMESTAMP       NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_app_deleg_delegator` (`delegator_id`),
  KEY `idx_app_deleg_delegatee` (`delegatee_id`),
  KEY `idx_app_deleg_validity`  (`valid_from`, `valid_until`),
  CONSTRAINT `fk_app_deleg_delegator` FOREIGN KEY (`delegator_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_app_deleg_delegatee` FOREIGN KEY (`delegatee_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
