-- Module: employee
-- Purpose: Tai lieu dinh kem ho so nhan vien (CV, bang cap, chung chi, …).
-- Related: employees.id
-- Legacy origin: n/a

CREATE TABLE `employee_documents` (
  `id`            BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `employee_id`   BIGINT UNSIGNED NOT NULL,
  `document_type` VARCHAR(100)    NOT NULL COMMENT 'cv, degree, certificate, id_card, …',
  `title`         VARCHAR(255)    NOT NULL,
  `file_url`      VARCHAR(500)    NOT NULL,
  `file_size`     BIGINT UNSIGNED DEFAULT NULL,
  `mime_type`     VARCHAR(100)    DEFAULT NULL,
  `issued_at`     DATE            NULL DEFAULT NULL,
  `expires_at`    DATE            NULL DEFAULT NULL,
  `metadata`      JSON            DEFAULT NULL,
  `uploaded_by`   BIGINT UNSIGNED NULL DEFAULT NULL,
  `created_at`    TIMESTAMP       NULL DEFAULT NULL,
  `updated_at`    TIMESTAMP       NULL DEFAULT NULL,
  `deleted_at`    TIMESTAMP       NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_documents_employee` (`employee_id`),
  KEY `idx_documents_type`     (`document_type`),
  CONSTRAINT `fk_documents_employee` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
