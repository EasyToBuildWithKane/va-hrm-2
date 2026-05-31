-- Module: core
-- Purpose: Polymorphic OAuth / 3rd-party integration credentials per user (Google, Strava, …).
-- Related: users.id
-- Legacy origin: _legacy/users.sql (google_id, google_access_token, google_refresh_token,
--                                   google_token_expires_at, google_scopes, google_event_id,
--                                   google_calendar_id, strava_reconnect_suggested)

CREATE TABLE `user_oauth_providers` (
  `id`                  BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id`             BIGINT UNSIGNED NOT NULL,
  `provider`            VARCHAR(50)     NOT NULL COMMENT 'google, strava, microsoft, …',
  `provider_user_id`    VARCHAR(255)    NOT NULL COMMENT 'Subject id từ provider',
  `access_token`        TEXT            DEFAULT NULL,
  `refresh_token`       TEXT            DEFAULT NULL,
  `token_expires_at`    TIMESTAMP       NULL DEFAULT NULL,
  `scopes`              LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL
                        CHECK (json_valid(`scopes`)),
  `metadata`            JSON            DEFAULT NULL
                        COMMENT 'Provider-specific extras: google_calendar_id, google_event_id, strava_reconnect_suggested, …',
  `reconnect_suggested` TINYINT(1)      NOT NULL DEFAULT 0,
  `connected_at`        TIMESTAMP       NULL DEFAULT NULL,
  `disconnected_at`     TIMESTAMP       NULL DEFAULT NULL,
  `created_at`          TIMESTAMP       NULL DEFAULT NULL,
  `updated_at`          TIMESTAMP       NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_oauth_provider_subject` (`provider`, `provider_user_id`),
  UNIQUE KEY `uq_oauth_user_provider`    (`user_id`, `provider`),
  KEY `idx_oauth_user`     (`user_id`),
  KEY `idx_oauth_provider` (`provider`),
  CONSTRAINT `fk_oauth_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
