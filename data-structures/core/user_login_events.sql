-- Module: core
-- Purpose: Login / logout telemetry (1 row / event). Phục vụ audit & analytics.
-- Related: users.id
-- Legacy origin: _legacy/users.sql (check_first_login, first_login_at, last_login_at, last_logout_at)

CREATE TABLE `user_login_events` (
  `id`         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id`    BIGINT UNSIGNED NOT NULL,
  `event`      ENUM('login','logout','first_login','failed_login') NOT NULL,
  `ip_address` VARCHAR(45)     DEFAULT NULL,
  `user_agent` VARCHAR(500)    DEFAULT NULL,
  `context`    JSON            DEFAULT NULL,
  `occurred_at` TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_login_user_time` (`user_id`, `occurred_at`),
  KEY `idx_login_event`     (`event`),
  CONSTRAINT `fk_login_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
