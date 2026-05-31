# `data-structures/` — Schema Reference

Nguồn tham chiếu schema duy nhất cho HRM Platform. Mỗi module trong [docs/02_FOLDER_STRUCTURE.md](../docs/02_FOLDER_STRUCTURE.md) có một thư mục con tương ứng ở đây, mỗi bảng có một file SQL riêng.

> **Phạm vi**: chỉ DDL (CREATE TABLE + index + FK). Không chứa seed, không chứa migration Laravel. Khi triển khai, mỗi file SQL ở đây sẽ được port sang `database/migrations/<module>/` của backend.

---

## Conventions

- **1 file = 1 bảng**, tên file `= tên bảng`.
- Mỗi file chứa nguyên một `CREATE TABLE` cộng `INDEX` / `UNIQUE` / `FOREIGN KEY` đầy đủ.
- Tuân thủ §1 [04_DATABASE_DESIGN.md](../docs/04_DATABASE_DESIGN.md):
  - `id BIGINT UNSIGNED AUTO_INCREMENT`
  - `ulid CHAR(26) UNIQUE` cho entity có public-facing ID
  - `created_at`, `updated_at`, `deleted_at` (soft delete cho entity chính)
  - `created_by`, `updated_by`, `deleted_by` cho entity cần audit nguồn
  - JSON column cho metadata linh hoạt
- Header comment bắt buộc đầu mỗi file:
  ```sql
  -- Module: <module>
  -- Purpose: <một dòng>
  -- Related: <bảng FK chính>
  -- Legacy origin: <users.sql | user_info.sql | n/a>
  ```
- `_legacy/` **read-only** — chỉ dùng đối chiếu khi migrate dữ liệu, không bao giờ sửa.

---

## Module Index

| Module | Mục đích | Số bảng |
|---|---|---|
| [`core/`](core/)                   | Auth, identity nền tảng, OAuth, login telemetry | 4 |
| [`permission/`](permission/)       | RBAC theo Spatie + role hierarchy + delegation | 6 |
| [`employee/`](employee/)           | Hồ sơ nhân sự + bảng vệ tinh (PII, giấy tờ, contact, banking, contract, document, timeline, emergency) | 9 |
| [`department/`](department/)       | Phòng ban + concurrent membership | 2 |
| [`organization/`](organization/)   | Organization graph (node + relationship) | 2 |
| [`attendance/`](attendance/)       | Chấm công + ca + correction | 3 |
| [`leave/`](leave/)                 | Loại phép, quota, đơn, policy | 4 |
| [`request/`](request/)             | Polymorphic workflow request base + chuyên biệt | 6 |
| [`approval/`](approval/)           | Approval engine: workflow, step, decision, delegation, config | 5 |
| [`provisioning/`](provisioning/)   | Provision tài khoản, email, license, log | 6 |
| [`audit/`](audit/)                 | Immutable audit log (polymorphic) | 1 |
| [`contribution/`](contribution/)   | Scoring engine: rules, events, scores, adjustments | 4 |
| [`notification/`](notification/)   | In-app / multi-channel notifications | 1 |
| [`_legacy/`](_legacy/)             | Schema cũ — chỉ tham chiếu | 2 |

**Tổng**: 53 bảng schema mới + 2 legacy.

---

## Full Table Index

### `core/`
- [`users`](core/users.sql) — auth identity lean
- [`user_oauth_providers`](core/user_oauth_providers.sql) — polymorphic OAuth (Google, Strava, …)
- [`user_login_events`](core/user_login_events.sql) — login/logout telemetry
- [`personal_access_tokens`](core/personal_access_tokens.sql) — Laravel Sanctum

### `permission/`
- [`roles`](permission/roles.sql)
- [`permissions`](permission/permissions.sql)
- [`role_has_permissions`](permission/role_has_permissions.sql)
- [`model_has_roles`](permission/model_has_roles.sql)
- [`model_has_permissions`](permission/model_has_permissions.sql)
- [`permission_delegations`](permission/permission_delegations.sql)

### `employee/`
- [`employees`](employee/employees.sql)
- [`employee_personal_info`](employee/employee_personal_info.sql)
- [`employee_identity_documents`](employee/employee_identity_documents.sql)
- [`employee_contacts`](employee/employee_contacts.sql)
- [`employee_banking`](employee/employee_banking.sql)
- [`employee_contracts`](employee/employee_contracts.sql)
- [`employee_documents`](employee/employee_documents.sql)
- [`employee_timeline`](employee/employee_timeline.sql)
- [`employee_emergency_contacts`](employee/employee_emergency_contacts.sql)

### `department/`
- [`departments`](department/departments.sql)
- [`department_members`](department/department_members.sql)

### `organization/`
- [`organization_nodes`](organization/organization_nodes.sql)
- [`organization_relationships`](organization/organization_relationships.sql)

### `attendance/`
- [`attendance_shifts`](attendance/attendance_shifts.sql)
- [`attendance_records`](attendance/attendance_records.sql)
- [`attendance_corrections`](attendance/attendance_corrections.sql)

### `leave/`
- [`leave_types`](leave/leave_types.sql)
- [`leave_quotas`](leave/leave_quotas.sql)
- [`leave_requests`](leave/leave_requests.sql)
- [`leave_policies`](leave/leave_policies.sql)

### `request/`
- [`workflow_requests`](request/workflow_requests.sql) — polymorphic base
- [`equipment_requests`](request/equipment_requests.sql)
- [`account_requests`](request/account_requests.sql)
- [`software_access_requests`](request/software_access_requests.sql)
- [`reimbursement_requests`](request/reimbursement_requests.sql)
- [`salary_adjustment_requests`](request/salary_adjustment_requests.sql)

### `approval/`
- [`approval_workflows`](approval/approval_workflows.sql)
- [`approval_steps`](approval/approval_steps.sql)
- [`approval_decisions`](approval/approval_decisions.sql)
- [`approval_delegations`](approval/approval_delegations.sql)
- [`workflow_configurations`](approval/workflow_configurations.sql)

### `provisioning/`
- [`provisioning_requests`](provisioning/provisioning_requests.sql)
- [`account_provisions`](provisioning/account_provisions.sql)
- [`email_provisions`](provisioning/email_provisions.sql)
- [`software_licenses`](provisioning/software_licenses.sql)
- [`employee_software_licenses`](provisioning/employee_software_licenses.sql)
- [`provisioning_logs`](provisioning/provisioning_logs.sql)

### `audit/`
- [`audit_logs`](audit/audit_logs.sql)

### `contribution/`
- [`scoring_rules`](contribution/scoring_rules.sql)
- [`contribution_events`](contribution/contribution_events.sql)
- [`contribution_scores`](contribution/contribution_scores.sql)
- [`score_adjustment_requests`](contribution/score_adjustment_requests.sql)

### `notification/`
- [`user_notifications`](notification/user_notifications.sql)

### `_legacy/`
- [`users.sql`](_legacy/users.sql) — schema cũ
- [`user_info.sql`](_legacy/user_info.sql) — schema cũ

---

## Legacy → New Mapping

### `_legacy/users.sql`

| Cột legacy | Đích mới | Ghi chú |
|---|---|---|
| `id`, `name`, `email`, `email_verified_at`, `avatar`, `password`, `remember_token`, `created_at`, `updated_at`, `deleted_at` | [`core/users`](core/users.sql) | Giữ nguyên |
| `check_first_login`, `first_login_at`, `last_login_at`, `last_logout_at` | [`core/user_login_events`](core/user_login_events.sql) | 1 row / event; `last_login_at` vẫn cache trên `core/users` |
| `point` | [`contribution/contribution_scores.monthly_points`](contribution/contribution_scores.sql) | Điểm kỳ hiện tại |
| `contribution_point` | [`contribution/contribution_scores.total_points`](contribution/contribution_scores.sql) | Điểm tích lũy |
| `level` | [`contribution/contribution_scores.level`](contribution/contribution_scores.sql) | |
| `google_id`, `google_access_token`, `google_refresh_token`, `google_token_expires_at`, `google_scopes` | [`core/user_oauth_providers`](core/user_oauth_providers.sql) (provider='google') | |
| `google_event_id`, `google_calendar_id` | [`core/user_oauth_providers.metadata`](core/user_oauth_providers.sql) (JSON) | |
| `strava_reconnect_suggested` | [`core/user_oauth_providers.reconnect_suggested`](core/user_oauth_providers.sql) (provider='strava') | |

### `_legacy/user_info.sql`

| Cột legacy | Đích mới | Ghi chú |
|---|---|---|
| `id`, `user_id` | [`employee/employees`](employee/employees.sql) (qua `user_id`) | Mỗi user trở thành 1 employee |
| (`users.name`) | [`employee/employees`](employee/employees.sql) (`first_name` + `last_name`) | Tách `users.name` khi migrate; cả 2 cột nullable nên import dần được |
| (`users.email`) | [`employee/employees.email`](employee/employees.sql) | Work email mặc định = login email; có thể đổi sau |
| `code` | [`employee/employees.employee_number`](employee/employees.sql) | |
| `gender`, `birthdate`, `birth_place`, `national`, `religion`, `hometown` | [`employee/employee_personal_info`](employee/employee_personal_info.sql) | |
| `identity`, `identity_date`, `identity_place` | [`employee/employee_identity_documents`](employee/employee_identity_documents.sql) (`identity_number`, `identity_issue_date`, `identity_issue_place`) | |
| `tax_code`, `social_insurance_number`, `health_insurance_code`, `unemployment_insurance_number` | [`employee/employee_identity_documents`](employee/employee_identity_documents.sql) | |
| `phone`, `address`, `household`, `working_place` | [`employee/employee_contacts`](employee/employee_contacts.sql) | `phone` còn cache thêm ở [`employees.phone`](employee/employees.sql) (quick-access); `employee_contacts.phone` là canonical |
| `bank_account`, `bank` | [`employee/employee_banking`](employee/employee_banking.sql) (`account_number`, `bank_name`) | |
| `start_working_date` | [`employee/employees.join_date`](employee/employees.sql) | |
| `department_id` | [`employee/employees.department_id`](employee/employees.sql) | Nullable để khớp legacy (cho phép NULL khi import) |
| `company_id` | [`employee/employees.company_id`](employee/employees.sql) | Nullable; FK `companies` còn deferred (khi multi-tenant) |
| `company_name`, `department_name`, `unit_name`, `headquarter_name`, `position_name`, `concurrent_position_name` | **DROPPED** — query qua FK + [`organization/`](organization/) graph | Loại bỏ snapshot string |
| `note` | [`employee/employees.metadata`](employee/employees.sql) (JSON) | |
| `created_at`, `updated_at` | Có trên từng bảng vệ tinh | |

---

## Tham chiếu chéo

| Doc | Vai trò |
|---|---|
| [docs/02_FOLDER_STRUCTURE.md](../docs/02_FOLDER_STRUCTURE.md) | Module list nguồn — thư mục con ở đây phải khớp |
| [docs/04_DATABASE_DESIGN.md](../docs/04_DATABASE_DESIGN.md) | Schema chuẩn cho phần lớn bảng — đối chiếu khi cập nhật |
| [docs/05_WORKFLOW_ENGINE.md](../docs/05_WORKFLOW_ENGINE.md) | Logic approval / SLA / escalation |
| [docs/06_ORGANIZATION_GRAPH.md](../docs/06_ORGANIZATION_GRAPH.md) | Lý do loại bỏ snapshot string trong `user_info` |
| [docs/07_AUDIT_SYSTEM.md](../docs/07_AUDIT_SYSTEM.md) | Tại sao `audit_logs` immutable |
| [docs/08_PROVISIONING_ENGINE.md](../docs/08_PROVISIONING_ENGINE.md) | Lifecycle `pending → revoked` |
| [docs/09_CONTRIBUTION_SCORING.md](../docs/09_CONTRIBUTION_SCORING.md) | Lý do tách `point` / `contribution_point` |
| [docs/10_RBAC_PERMISSIONS.md](../docs/10_RBAC_PERMISSIONS.md) | Naming convention + role hierarchy |
