-- Module: request
-- Purpose: Yeu cau cap thiet bi (laptop, man hinh, …).
-- Related: workflow_requests (qua requestable_type='EquipmentRequest', requestable_id)
-- Legacy origin: n/a

CREATE TABLE `equipment_requests` (
  `id`                BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `equipment_type`    VARCHAR(100)    NOT NULL,
  `equipment_specs`   JSON            DEFAULT NULL,
  `quantity`          INT UNSIGNED    NOT NULL DEFAULT 1,
  `estimated_cost`    DECIMAL(15,2)   DEFAULT NULL,
  `currency`          CHAR(3)         NOT NULL DEFAULT 'VND',
  `delivery_location` VARCHAR(255)    DEFAULT NULL,
  `needed_by`         DATE            NULL DEFAULT NULL,
  `created_at`        TIMESTAMP       NULL DEFAULT NULL,
  `updated_at`        TIMESTAMP       NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
