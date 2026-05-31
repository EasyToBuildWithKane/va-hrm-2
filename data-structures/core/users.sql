-- Module: core
-- Purpose: Authentication identity. Lean — chỉ chứa credential + trạng thái đăng nhập tối thiểu.
-- Related: employees.user_id, user_oauth_providers.user_id, user_login_events.user_id, contribution_scores.user_id
-- Legacy origin: _legacy/users.sql (auth fields)

CREATE TABLE `users` (
  `id`                BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `ulid`              CHAR(26)        NOT NULL,
  `name`              VARCHAR(255)    NOT NULL,
  `email`             VARCHAR(255)    NOT NULL,
  `email_verified_at` TIMESTAMP       NULL DEFAULT NULL,
  `avatar`            TEXT            DEFAULT NULL,
  `password`          VARCHAR(255)    NOT NULL,
  `remember_token`    VARCHAR(100)    DEFAULT NULL,
  `status`            ENUM('active','inactive','suspended') NOT NULL DEFAULT 'active',
  `last_login_at`     TIMESTAMP       NULL DEFAULT NULL,
  `created_at`        TIMESTAMP       NULL DEFAULT NULL,
  `updated_at`        TIMESTAMP       NULL DEFAULT NULL,
  `deleted_at`        TIMESTAMP       NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_users_ulid`  (`ulid`),
  UNIQUE KEY `uq_users_email` (`email`),
  KEY `idx_users_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
