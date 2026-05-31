-- Module: notification
-- Purpose: In-app notification cho user (Laravel default notifications-compatible).
-- Related: users.id (qua notifiable polymorphic)
-- Legacy origin: n/a

CREATE TABLE `user_notifications` (
  `id`              CHAR(36)        NOT NULL COMMENT 'UUID — Laravel notifications default',
  `type`            VARCHAR(255)    NOT NULL COMMENT 'FQCN cua notification class',
  `notifiable_type` VARCHAR(255)    NOT NULL,
  `notifiable_id`   BIGINT UNSIGNED NOT NULL,
  `channel`         VARCHAR(50)     NOT NULL DEFAULT 'database' COMMENT 'database, mail, slack, …',
  `data`            JSON            NOT NULL,
  `read_at`         TIMESTAMP       NULL DEFAULT NULL,
  `created_at`      TIMESTAMP       NULL DEFAULT NULL,
  `updated_at`      TIMESTAMP       NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_notif_notifiable` (`notifiable_type`, `notifiable_id`),
  KEY `idx_notif_unread`     (`notifiable_type`, `notifiable_id`, `read_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
