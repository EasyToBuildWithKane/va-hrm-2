-- Module: organization
-- Purpose: Edges giua cac node trong org graph (REPORT_TO, MANAGE, BELONG_TO, APPROVE_FOR, …).
-- Related: organization_nodes.id (from/to)
-- Legacy origin: n/a

CREATE TABLE `organization_relationships` (
  `id`                BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `from_node_id`      BIGINT UNSIGNED NOT NULL,
  `to_node_id`        BIGINT UNSIGNED NOT NULL,
  `relationship_type` ENUM('REPORT_TO','MANAGE','BELONG_TO','APPROVE_FOR','WORK_WITH','MEMBER_OF') NOT NULL,
  `weight`            DECIMAL(5,2)    NOT NULL DEFAULT 1.00,
  `is_active`         TINYINT(1)      NOT NULL DEFAULT 1,
  `valid_from`        DATE            NULL DEFAULT NULL,
  `valid_until`       DATE            NULL DEFAULT NULL,
  `created_at`        TIMESTAMP       NULL DEFAULT NULL,
  `updated_at`        TIMESTAMP       NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_org_rel` (`from_node_id`, `to_node_id`, `relationship_type`),
  KEY `idx_org_rel_from` (`from_node_id`),
  KEY `idx_org_rel_to`   (`to_node_id`),
  KEY `idx_org_rel_type` (`relationship_type`),
  CONSTRAINT `fk_org_rel_from` FOREIGN KEY (`from_node_id`) REFERENCES `organization_nodes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_org_rel_to`   FOREIGN KEY (`to_node_id`)   REFERENCES `organization_nodes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
