# Database — ERD (Entity Relationship Diagram)

> Nguồn: [database/migrations/](../../database/migrations/) (schema chính thức) đối chiếu
> [data-structures/](../../data-structures/) (DDL thiết kế). Chi tiết từng cột:
> [table-dictionary.md](table-dictionary.md).

Database: **MySQL 8**. Quy ước chung:
- Tên bảng vật lý: tiền tố `va_hrm_` (Laravel connection prefix — [config/database.php](../../config/database.php)); migration khai báo tên logic.
- `id` BIGINT auto-increment là khoá chính nội bộ.
- `ulid` CHAR(26) UNIQUE cho entity public-facing (route key là `ulid` qua trait
  [HasUlid](../../app/Concerns/HasUlid.php)).
- `created_at/updated_at`; `deleted_at` (soft delete) cho entity chính.
- `created_by/updated_by` cho entity cần truy vết nguồn.
- JSON column cho metadata linh hoạt.

---

## 1. Tổng quan miền dữ liệu

```mermaid
flowchart TB
    subgraph Identity & RBAC
      va_hrm_users
      va_hrm_roles
      va_hrm_permissions
      va_hrm_permission_delegations
    end
    subgraph HR Core
      va_hrm_departments
      va_hrm_positions
      va_hrm_employees
      va_hrm_employee_contracts
    end
    subgraph Organization
      va_hrm_organization_nodes
      va_hrm_organization_relationships
    end
    subgraph Workflow
      va_hrm_workflow_configurations
      va_hrm_approval_workflows
      va_hrm_approval_steps
      va_hrm_workflow_requests
    end
    subgraph Self-service
      va_hrm_leave_requests
      va_hrm_attendance_records
    end
    subgraph Ops
      va_hrm_provisioning_requests
      va_hrm_contribution_scores
      va_hrm_audit_logs
      va_hrm_user_notifications
    end

    va_hrm_users --> va_hrm_employees
    va_hrm_departments --> va_hrm_employees
    va_hrm_positions --> va_hrm_employees
    va_hrm_employees --> va_hrm_employees
    va_hrm_employees --> va_hrm_leave_requests
    va_hrm_employees --> va_hrm_attendance_records
    va_hrm_employees --> va_hrm_workflow_requests
    va_hrm_employees --> va_hrm_provisioning_requests
    va_hrm_employees --> va_hrm_contribution_scores
    va_hrm_workflow_configurations --> va_hrm_approval_workflows
    va_hrm_approval_workflows --> va_hrm_approval_steps
    va_hrm_leave_requests -.morph.-> va_hrm_approval_workflows
    va_hrm_workflow_requests -.morph.-> va_hrm_approval_workflows
    va_hrm_employees --> va_hrm_organization_nodes
    va_hrm_departments --> va_hrm_organization_nodes
    va_hrm_organization_nodes --> va_hrm_organization_relationships
```

---

## 2. ERD chi tiết — HR Core + Identity

```mermaid
erDiagram
    va_hrm_users ||--o| va_hrm_employees : "has profile"
    va_hrm_users ||--o{ va_hrm_permission_delegations : "delegates"
    va_hrm_departments ||--o{ va_hrm_employees : "employs"
    va_hrm_departments ||--o{ va_hrm_departments : "parent_of"
    va_hrm_departments ||--o{ va_hrm_positions : "has"
    va_hrm_positions ||--o{ va_hrm_employees : "holds"
    va_hrm_employees ||--o{ va_hrm_employees : "manages"
    va_hrm_employees ||--o{ va_hrm_employee_contracts : "has"
    va_hrm_employees ||--o{ va_hrm_employee_documents : "has"
    va_hrm_employees ||--o{ va_hrm_employee_timeline : "logs"
    va_hrm_employees ||--o{ va_hrm_employee_emergency_contacts : "has"

    va_hrm_users {
        bigint id PK
        char ulid UK
        string email UK
        string status
        timestamp last_login_at
    }
    va_hrm_employees {
        bigint id PK
        char ulid UK
        bigint user_id FK
        string employee_number UK
        bigint department_id FK
        bigint position_id FK
        bigint manager_id FK
        string employment_status
        decimal salary "sensitive"
    }
    va_hrm_departments {
        bigint id PK
        string code UK
        bigint parent_id FK
        bigint manager_id FK
    }
    va_hrm_positions {
        bigint id PK
        string code UK
        bigint department_id FK
    }
```

---

## 3. ERD chi tiết — Workflow & Requests

`va_hrm_approval_workflows` gắn polymorphic vào bất kỳ "requestable" nào (va_hrm_leave_requests,
va_hrm_workflow_requests, va_hrm_attendance_corrections, va_hrm_score_adjustment_requests…) qua
`requestable_type` + `requestable_id`.

```mermaid
erDiagram
    va_hrm_workflow_configurations ||--o{ va_hrm_approval_workflows : "drives"
    va_hrm_approval_workflows ||--o{ va_hrm_approval_steps : "has"
    va_hrm_approval_steps ||--o{ va_hrm_approval_decisions : "records"
    va_hrm_approval_steps ||--o{ va_hrm_approval_delegations : "delegated_via"
    va_hrm_workflow_requests ||--o{ va_hrm_equipment_requests : "specializes"
    va_hrm_workflow_requests ||--o{ va_hrm_reimbursement_requests : "specializes"
    va_hrm_workflow_requests ||--o{ va_hrm_software_access_requests : "specializes"
    va_hrm_workflow_requests ||--o{ va_hrm_account_requests : "specializes"
    va_hrm_workflow_requests ||--o{ va_hrm_salary_adjustment_requests : "specializes"
    va_hrm_leave_requests }o--o| va_hrm_approval_workflows : "morph requestable"
    va_hrm_workflow_requests }o--o| va_hrm_approval_workflows : "morph requestable"

    va_hrm_approval_workflows {
        bigint id PK
        char ulid UK
        string requestable_type
        bigint requestable_id
        string workflow_type
        int current_step
        int total_steps
        string status
        timestamp sla_deadline_at
    }
    va_hrm_approval_steps {
        bigint id PK
        bigint workflow_id FK
        int step_number
        bigint approver_id FK
        string status
        timestamp sla_deadline_at
    }
    va_hrm_workflow_requests {
        bigint id PK
        char ulid UK
        string request_type
        bigint employee_id FK
        bigint workflow_id
        string status
        json payload
    }
```

---

## 4. ERD chi tiết — Self-service, Provisioning, Contribution, Audit

```mermaid
erDiagram
    va_hrm_leave_types ||--o{ va_hrm_leave_quotas : "quota"
    va_hrm_leave_types ||--o{ va_hrm_leave_requests : "of_type"
    va_hrm_employees ||--o{ va_hrm_leave_requests : "submits"
    va_hrm_employees ||--o{ va_hrm_leave_quotas : "owns"
    va_hrm_attendance_shifts ||--o{ va_hrm_attendance_records : "schedules"
    va_hrm_employees ||--o{ va_hrm_attendance_records : "logs"
    va_hrm_attendance_records ||--o{ va_hrm_attendance_corrections : "corrected_by"
    va_hrm_employees ||--o{ va_hrm_provisioning_requests : "for"
    va_hrm_provisioning_requests ||--o{ va_hrm_account_provisions : "creates"
    va_hrm_account_provisions ||--o| va_hrm_email_provisions : "email"
    va_hrm_software_licenses ||--o{ va_hrm_employee_software_licenses : "seats"
    va_hrm_employees ||--o{ va_hrm_employee_software_licenses : "assigned"
    va_hrm_scoring_rules ||--o{ va_hrm_contribution_events : "applies"
    va_hrm_employees ||--o{ va_hrm_contribution_events : "earns"
    va_hrm_employees ||--o| va_hrm_contribution_scores : "ranked"
    va_hrm_users ||--o{ va_hrm_user_notifications : "receives"

    va_hrm_audit_logs {
        bigint id PK
        char ulid UK
        string auditable_type
        bigint auditable_id
        string event
        json old_values
        json new_values
        bool payroll_sensitive
    }
```

> `va_hrm_audit_logs` là **polymorphic & bất biến** (chỉ có `created_at`, không update). Bản sao schema
> `va_hrm_audit_logs_archive` dùng để archive theo cron (xem [config/audit.php](../../config/audit.php)).

---

## 5. Quan hệ chéo quan trọng (không phải FK cứng)
- `va_hrm_approval_workflows.requestable_*` → polymorphic tới `va_hrm_leave_requests` / `va_hrm_workflow_requests` /
  `va_hrm_attendance_corrections` / `va_hrm_score_adjustment_requests`. Các bảng request giữ `workflow_id`
  (nullable, không FK cứng) trỏ ngược lại.
- `va_hrm_organization_nodes.reference_type/reference_id` → polymorphic tới `va_hrm_employees` hoặc `va_hrm_departments`.
- `va_hrm_audit_logs.auditable_type/auditable_id` → polymorphic tới mọi model `implements Auditable`.
- `va_hrm_audit_logs.context->workflow_id` (JSON) dùng để truy vết theo workflow.

Xem mapping Module ⇄ Bảng ⇄ API tại từng file trong [docs/modules/](../modules/).
