# Database — Table Dictionary

> Từ điển bảng đầy đủ, suy ra trực tiếp từ [database/migrations/](../../database/migrations/).
> Sơ đồ quan hệ: [erd.md](erd.md). Mỗi bảng ghi: Purpose · Columns · Indexes · Relationships.
> Anchor (vd `#va_hrm_leave_requests`) được các module/API doc link tới.

Quy ước: `PK`=primary key, `UK`=unique, `FK`=foreign key, `SD`=có soft delete (`deleted_at`).
Tên bảng trong tài liệu là **tên vật lý trên MySQL** (`va_hrm_*`), áp dụng qua `prefix` connection trong [config/database.php](../../config/database.php); migration giữ tên logic (không prefix).

---

## Identity & RBAC

### va_hrm_users
**Purpose:** Tài khoản đăng nhập (auth identity). Migration gốc Laravel + `extend_users_table`.
| Column | Type | Note |
|---|---|---|
| id | bigint PK | |
| ulid | char(26) UK | public id (thêm bởi extend) |
| name | string | |
| email | string UK | dùng login |
| email_verified_at | timestamp null | |
| password | string | hashed |
| remember_token | string null | |
| status | enum(active,inactive,suspended) | default active |
| last_login_at | timestamp null | cập nhật khi login |
| deleted_at | timestamp null | SD |
**Indexes:** unique(email), unique(ulid), index(status).
**Relationships:** hasOne `va_hrm_employees` (user_id); hasRoles/permissions (spatie; bảng `va_hrm_roles`, `va_hrm_permissions`); hasMany `va_hrm_permission_delegations`, `va_hrm_user_notifications`.

### va_hrm_roles / va_hrm_permissions / va_hrm_role_has_permissions / va_hrm_model_has_roles / va_hrm_model_has_permissions
**Purpose:** Bảng chuẩn **spatie/laravel-permission** (RBAC). `va_hrm_model_has_roles`/`va_hrm_model_has_permissions`
gán role/permission cho `User`. Tạo bởi `create_permission_tables` dùng `config('permission.table_names')`.
**Relationships:** va_hrm_roles ↔ va_hrm_permissions (many-to-many qua va_hrm_role_has_permissions).

### va_hrm_permission_delegations
**Purpose:** Uỷ quyền tạm thời role/permission/approval giữa 2 user trong khoảng thời gian.
| Column | Type | Note |
|---|---|---|
| id | bigint PK | |
| ulid | char(26) UK | |
| delegated_by | FK va_hrm_users | người uỷ quyền |
| delegated_to | FK va_hrm_users | người nhận |
| delegation_type | enum(approval,role,permission) | |
| role_id | bigint null | nếu uỷ quyền role |
| permission | string(100) null | nếu uỷ quyền 1 permission |
| scope_type / scope_id | string/bigint null | giới hạn phạm vi |
| valid_from / valid_until | timestamp | hiệu lực |
| reason | text null | |
| created_by | FK va_hrm_users | |
**Indexes:** index(delegated_to), index(valid_from,valid_until), index(permission).
**Relationships:** belongsTo va_hrm_users (delegator/delegatee). Dùng bởi
[CheckPermission middleware](../../app/Http/Middleware/CheckPermission.php) qua `hasActiveDelegationFor()`.

---

## HR Core

### va_hrm_departments
**Purpose:** Phòng ban, cấu trúc cây (self-parent). → [module](../modules/department.md)
| Column | Type | Note |
|---|---|---|
| id | bigint PK | |
| ulid | char(26) UK | |
| name | string | |
| code | string(20) UK | |
| parent_id | FK va_hrm_departments null | cây phòng ban (nullOnDelete) |
| manager_id | FK va_hrm_employees null | trưởng phòng (FK thêm sau khi tạo va_hrm_employees) |
| headcount_limit | uint null | trần biên chế |
| is_active | bool | default true |
| metadata | json null | |
| created_by/updated_by | bigint null | |
| deleted_at | timestamp null | SD |
**Indexes:** unique(code,ulid), index(parent_id), index(is_active).
**Relationships:** parent/children (self), manager→va_hrm_employees, hasMany va_hrm_employees, va_hrm_positions.

### va_hrm_positions
**Purpose:** Chức danh/vị trí công việc, gắn (tuỳ chọn) vào phòng ban.
| Column | Type | Note |
|---|---|---|
| id, ulid | | PK/UK |
| name | string | |
| code | string(30) UK | |
| department_id | FK va_hrm_departments null | nullOnDelete |
| description | text null | |
| is_active | bool | default true |
| deleted_at | | SD |

### va_hrm_employees
**Purpose:** Hồ sơ nhân sự cốt lõi. → [module](../modules/employee.md)
| Column | Type | Note |
|---|---|---|
| id | bigint PK | |
| ulid | char(26) UK | route key |
| user_id | FK va_hrm_users | cascadeOnDelete |
| employee_number | string(20) UK | tự sinh `EMP-XXXXXXXX` |
| department_id | FK va_hrm_departments | |
| position_id | FK va_hrm_positions | |
| manager_id | FK va_hrm_employees null | self, nullOnDelete |
| first_name, last_name | string(100) | |
| email | string | work email |
| phone | string(20) null | |
| employment_type | enum(full_time,part_time,contract,intern) | |
| employment_status | enum(active,inactive,on_leave,terminated,resigned) | default active |
| join_date | date | |
| probation_end_date | date null | |
| termination_date | date null | |
| onboarding_status | enum(pending,in_progress,completed) | |
| offboarding_status | enum(none,in_progress,completed) | |
| salary | decimal(14,2) null | **sensitive** — redacted trong audit, hidden trong API |
| bank_account_number | string(50) null | **sensitive** |
| metadata | json null | |
| created_by/updated_by | bigint null | |
| deleted_at | | SD |
**Indexes:** unique(ulid,employee_number), index(manager_id), index(employment_status), index(department_id,employment_status).
**Relationships:** belongsTo va_hrm_users/department/position/manager; hasMany directReports, contracts, documents, timeline, emergencyContacts. `implements Auditable` (audit fields gồm salary — bị mask).

### va_hrm_employee_contracts
**Purpose:** Hợp đồng lao động.
Columns: id, ulid, employee_id(FK cascade), contract_number(UK), contract_type enum(probation,fixed_term,permanent,freelance), start_date, end_date(null), base_salary(decimal null), file_path(null), status enum(draft,active,expired,terminated) default draft, metadata, created_by/updated_by, SD.
**Indexes:** index(employee_id,status).

### va_hrm_employee_documents
**Purpose:** Tài liệu nhân sự (file). Columns: id, ulid, employee_id(FK cascade), document_type, title, file_path, mime_type(null), size_bytes(null), issued_at(null), expires_at(null), metadata, uploaded_by(null), SD. Index(employee_id,document_type).

### va_hrm_employee_timeline
**Purpose:** Nhật ký sự kiện vòng đời nhân viên (created, department_transfer, terminated, onboarded, offboarded…). Columns: id, employee_id(FK cascade), event_type, title, description(null), payload(json), occurred_at, performed_by(null). Index(employee_id,occurred_at). Ghi bởi [EmployeeService::logTimeline()](../../modules/Employee/Services/EmployeeService.php).

### va_hrm_employee_emergency_contacts
**Purpose:** Liên hệ khẩn cấp. Columns: id, employee_id(FK cascade), full_name, relationship, phone, email(null), address(null), is_primary(bool).

---

## Organization → [module](../modules/organization.md)

### va_hrm_organization_nodes
**Purpose:** Đỉnh đồ thị tổ chức (polymorphic tới employee/department/role/project/approval_authority).
Columns: id, node_type enum(employee,department,role,project,approval_authority), reference_type, reference_id, label, metadata(json), is_active(bool).
**Indexes:** unique(reference_type,reference_id)=`uq_org_reference`, index(node_type), index(is_active).

### va_hrm_organization_relationships
**Purpose:** Cạnh có hướng + trọng số giữa các node.
Columns: id, from_node_id(FK nodes cascade), to_node_id(FK nodes cascade), relationship_type enum(REPORT_TO,MANAGE,BELONG_TO,APPROVE_FOR,WORK_WITH,MEMBER_OF), weight decimal(5,2) default 1.00, is_active, valid_from(null), valid_until(null).
**Indexes:** unique(from,to,type)=`uq_org_relationship`, index từng đầu node + relationship_type.

---

## Attendance → [module](../modules/attendance.md)

### va_hrm_attendance_shifts
**Purpose:** Định nghĩa ca làm. Columns: id, ulid, name, code(UK), start_time(time), end_time(time), grace_minutes(uint smallint), break_minutes, working_days(json vd [1,2,3,4,5]), is_active, SD.

### va_hrm_employee_shifts
**Purpose:** Gán ca cho nhân viên theo khoảng ngày. Columns: id, employee_id(FK cascade), shift_id(FK shifts cascade), valid_from, valid_until(null). Index(employee_id,valid_from).

### va_hrm_attendance_records
**Purpose:** Bản ghi chấm công 1 nhân viên/1 ngày. → anchor dùng bởi correction.
Columns: id, employee_id(FK cascade), shift_id(FK null), date, check_in_at(null), check_out_at(null), check_in_ip/check_out_ip(null), status enum(present,absent,late,half_day,holiday,leave) default present, late_minutes(uint), overtime_minutes(uint), notes(null), is_corrected(bool).
**Indexes:** unique(employee_id,date), index(date).

### va_hrm_attendance_corrections
**Purpose:** Đề nghị sửa chấm công (chờ duyệt). Columns: id, ulid, attendance_record_id(FK cascade), employee_id(FK cascade), workflow_id(null), proposed_values(json), reason(text), status enum(pending,approved,rejected) default pending. Index(status).

---

## Leave → [module](../modules/leave.md)

### va_hrm_leave_types
**Purpose:** Loại nghỉ phép & quy tắc. Columns: id, name, code(UK), days_per_year(decimal), is_paid(bool), carry_forward(bool), max_carry_days(decimal), requires_docs(bool), min_notice_days(uint), is_active.

### va_hrm_leave_policies
**Purpose:** Chính sách nghỉ theo loại × phòng ban (rules JSON: eligibility, accrual, blackout). Columns: id, leave_type_id(FK cascade), department_id(FK null), rules(json), is_active.

### va_hrm_leave_quotas
**Purpose:** Hạn mức nghỉ của nhân viên theo năm. Columns: id, employee_id(FK cascade), leave_type_id(FK cascade), year, entitled_days, used_days(default 0), carried_days(default 0). **Unique** (employee,type,year)=`uq_emp_type_year`. Cập nhật bởi `LeaveQuotaService` (deduct/refund).

### va_hrm_leave_requests
**Purpose:** Đơn xin nghỉ. → anchor `#va_hrm_leave_requests`
Columns: id, ulid, employee_id(FK), leave_type_id(FK), workflow_id(null → va_hrm_approval_workflows), start_date, end_date, days_count(decimal), reason(text null), status enum(draft,pending,approved,rejected,cancelled) default pending, attachments(json), approved_at(null), cancelled_at(null), created_by(null), SD.
**Indexes:** index(employee_id,status), index(status), index(start_date,end_date).
**Relationships:** belongsTo employee/leaveType/workflow. `implements Auditable`.

---

## Approval / Workflow → [module](../modules/approval.md)

### va_hrm_workflow_configurations
**Purpose:** Cấu hình các bước cho mỗi `workflow_type` (JSON). Columns: id, workflow_type(UK), config(json: `steps[]`, `escalation`), is_active, created_by/updated_by. Seed bởi `WorkflowConfigurationSeeder`.

### va_hrm_approval_workflows
**Purpose:** Một tiến trình phê duyệt cho 1 requestable (polymorphic). → anchor `#va_hrm_approval_workflows`
Columns: id, ulid, requestable_type, requestable_id, workflow_type, current_step(default 1), total_steps, status enum(pending,in_progress,approved,rejected,cancelled,escalated) default in_progress, sla_deadline_at(null), completed_at(null), created_by(FK va_hrm_users).
**Indexes:** index(requestable_type,requestable_id), index(status), index(workflow_type).
**Relationships:** morphTo requestable; hasMany steps; hasManyThrough decisions.

### va_hrm_approval_steps
**Purpose:** Một bước duyệt trong workflow. Columns: id, workflow_id(FK cascade), step_number, approver_id(FK va_hrm_users null), approver_role(null), status enum(pending,approved,rejected,skipped,delegated,escalated), decision_at(null), notes(null), delegated_to_id(FK va_hrm_users null), sla_hours(default 24), sla_deadline_at(null). Index(workflow_id,step_number), index(approver_id), index(status).

### va_hrm_approval_decisions
**Purpose:** Bản ghi quyết định (audit nội bộ workflow). Columns: id, step_id(FK cascade), decided_by(FK va_hrm_users), decision enum(approve,reject,delegate,escalate), notes, context(json), decided_at.

### va_hrm_approval_delegations
**Purpose:** Lịch sử uỷ quyền tại 1 step. Columns: id, step_id(FK cascade), from_user_id(FK), to_user_id(FK), reason, delegated_at.

---

## Request (đa loại) → [module](../modules/request.md)

### va_hrm_workflow_requests
**Purpose:** Bảng base polymorphic cho mọi yêu cầu cần duyệt. → anchor `#va_hrm_workflow_requests`
Columns: id, ulid, request_type(vd leave_request, equipment_request…), employee_id(FK), workflow_id(null), status enum(draft,pending,in_progress,approved,rejected,cancelled), payload(json), justification(null), submitted_at/completed_at/cancelled_at(null), created_by(null), SD.
**Indexes:** index(employee_id,status), index(request_type), index(status).

### Bảng chuyên biệt (1-1 với va_hrm_workflow_requests qua `workflow_request_id` cascade)
| Bảng | Cột chính |
|---|---|
| va_hrm_equipment_requests | equipment_type, model, quantity, estimated_cost |
| va_hrm_reimbursement_requests | amount, currency(default USD), category, expense_date, receipts(json) |
| va_hrm_software_access_requests | software_name, access_level, needed_by |
| va_hrm_account_requests | account_type enum(email,system,software,device), access_scopes(json) |
| va_hrm_salary_adjustment_requests | target_employee_id(FK va_hrm_employees), current_salary, proposed_salary, effective_date, justification |

---

## Provisioning → [module](../modules/provisioning.md)

### va_hrm_provisioning_requests
**Purpose:** Yêu cầu cấp/thu hồi (onboarding/offboarding/access_change/license_assign). Columns: id, ulid, employee_id(FK), workflow_id(null), type enum, status enum(pending,approved,active,suspended,disabled,revoked), requested_by(FK va_hrm_users), processed_by(FK va_hrm_users null), processed_at(null), metadata(json). Index(employee_id,type), index(status).

### va_hrm_account_provisions
**Purpose:** Tài khoản đã cấp cho nhân viên. Columns: id, employee_id(FK), provisioning_request_id(FK null), account_type enum(email,system,software,device), account_identifier, status enum(pending,active,suspended,disabled,revoked), activated_at/suspended_at/revoked_at(null), metadata(json). Index(employee_id,account_type), index(status).

### va_hrm_email_provisions
**Purpose:** Hộp thư cấp kèm account. Columns: id, employee_id(FK), account_provision_id(FK cascade), email_address, alias(null), mailbox_type enum(standard,shared,distribution).

### va_hrm_software_licenses
**Purpose:** Kho license phần mềm. Columns: id, name, vendor(null), license_key(string 500 null), total_seats(uint), used_seats(default 0), expires_at(null), metadata(json).

### va_hrm_employee_software_licenses
**Purpose:** Gán seat license cho nhân viên (pivot). Columns: id, employee_id(FK), software_license_id(FK), assigned_at(useCurrent), revoked_at(null), assigned_by(FK va_hrm_users). **Unique**(employee,license)=`uq_emp_license`. `used_seats` được increment/decrement bởi `ProvisioningService`.

### va_hrm_provisioning_logs
**Purpose:** Nhật ký thao tác provisioning. Columns: id, provisioning_request_id(FK null), employee_id(FK), action, subject(null), result enum(success,failure,skipped) default success, message(null), context(json). Index(employee_id,created_at).

---

## Contribution → [module](../modules/contribution.md)

### va_hrm_scoring_rules
**Purpose:** Quy tắc tính điểm theo `event_type`. Columns: id, name, event_type, base_points(decimal), multiplier(default 1.00), conditions(json null), is_active. Index(event_type), index(is_active). Seed `ScoringRuleSeeder`. Trọng số tham khảo: [config/contribution.php](../../config/contribution.php).

### va_hrm_contribution_events
**Purpose:** Sự kiện ghi điểm cho nhân viên. Columns: id, employee_id(FK), rule_id(FK va_hrm_scoring_rules), event_type, points_earned(decimal), reference_type/reference_id(null, polymorphic nguồn), description(null), occurred_at. Index(employee_id,occurred_at), index(event_type), index(reference_type,reference_id).

### va_hrm_contribution_scores
**Purpose:** Điểm & hạng tổng hợp (1 dòng/nhân viên). Columns: id, employee_id(FK **unique**), total_points, monthly_points, quarterly_points, rank_overall(null), rank_department(null), last_calculated_at(null). Index(total_points), index(rank_overall).

### va_hrm_score_adjustment_requests
**Purpose:** Đề nghị điều chỉnh điểm (chờ duyệt). Columns: id, ulid, employee_id(FK), workflow_id(null), adjustment_points(decimal), reason(text), status enum(pending,approved,rejected), requested_by(FK va_hrm_users).

---

## Audit & Notification

### va_hrm_audit_logs (+ va_hrm_audit_logs_archive)
**Purpose:** Audit log **bất biến, polymorphic**. → [module](../modules/audit.md)
Columns: id, ulid, auditable_type, auditable_id, event enum(created,updated,deleted,restored,approved,rejected,assigned,revoked,activated,deactivated), old_values(json), new_values(json), changed_fields(json), performed_by(default 0 = system), ip_address(null), user_agent(null), context(json), payroll_sensitive(bool), created_at(useCurrent — **không có updated_at**).
**Indexes:** index(auditable_type,auditable_id), index(performed_by), index(event), index(created_at).
`va_hrm_audit_logs_archive` có schema y hệt; archive theo cron [config/audit.php](../../config/audit.php).

### va_hrm_user_notifications
**Purpose:** Thông báo in-app/đa kênh cho user. → [module](../modules/notification.md)
Columns: id, ulid, user_id(FK cascade), channel(default in_app), type, title, body(text), payload(json), action_url(null), read_at(null). Index(user_id,read_at), index(type).

---

## Bảng hệ thống (Laravel mặc định)
`va_hrm_password_reset_tokens`, `va_hrm_failed_jobs`, `va_hrm_personal_access_tokens` (Sanctum — lưu token API).
