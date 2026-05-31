-- Module: organization
-- Purpose: Node trong organization graph (polymorphic — employee, department, role, project, …).
-- Related: bat ki bang nao qua (reference_type, reference_id)
-- Legacy origin: n/a (thay the cho cac string snapshot trong _legacy/user_info.sql)

CREATE TABLE `organization_nodes` (
  `id`             BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `node_type`      ENUM('employee','department','role','project','approval_authority') NOT NULL,
  `reference_type` VARCHAR(100)    NOT NULL,
  `reference_id`   BIGINT UNSIGNED NOT NULL,
  `label`          VARCHAR(255)    NOT NULL,
  `metadata`       JSON            DEFAULT NULL,
  `is_active`      TINYINT(1)      NOT NULL DEFAULT 1,
  `created_at`     TIMESTAMP       NULL DEFAULT NULL,
  `updated_at`     TIMESTAMP       NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_org_node_reference` (`reference_type`, `reference_id`),
  KEY `idx_org_node_type` (`node_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
