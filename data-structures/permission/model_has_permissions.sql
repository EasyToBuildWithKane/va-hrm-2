-- Module: permission
-- Purpose: Pivot — model nhận permission trực tiếp (override role).
-- Related: permissions.id, users.id
-- Legacy origin: n/a

CREATE TABLE `model_has_permissions` (
  `permission_id` BIGINT UNSIGNED NOT NULL,
  `model_type`    VARCHAR(255)    NOT NULL,
  `model_id`      BIGINT UNSIGNED NOT NULL,
  `team_id`       BIGINT UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`permission_id`, `model_type`, `model_id`),
  KEY `idx_mhp_model` (`model_type`, `model_id`),
  CONSTRAINT `fk_mhp_permission` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
