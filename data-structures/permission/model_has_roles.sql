-- Module: permission
-- Purpose: Pivot — model (thường là User) gắn role. Hỗ trợ team_id để scope theo phòng ban.
-- Related: roles.id, users.id (qua model_type = 'App\\Models\\User')
-- Legacy origin: n/a

CREATE TABLE `model_has_roles` (
  `role_id`    BIGINT UNSIGNED NOT NULL,
  `model_type` VARCHAR(255)    NOT NULL,
  `model_id`   BIGINT UNSIGNED NOT NULL,
  `team_id`    BIGINT UNSIGNED NULL DEFAULT NULL COMMENT 'Optional department/team scope',
  PRIMARY KEY (`role_id`, `model_type`, `model_id`),
  KEY `idx_mhr_model` (`model_type`, `model_id`),
  KEY `idx_mhr_team`  (`team_id`),
  CONSTRAINT `fk_mhr_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
