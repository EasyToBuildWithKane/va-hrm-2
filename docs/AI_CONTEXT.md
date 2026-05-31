# AI_CONTEXT — Knowledge Base cho AI Agent

> **File quan trọng nhất.** Đọc file này là đủ để một AI Agent (hoặc dev mới) hiểu toàn bộ hệ thống
> mà không cần hỏi lại. Mọi thông tin suy ra từ source code thật; phần chưa chắc đánh dấu
> `TODO: Need Human Validation`.

## Project Summary
**VA-HRM** = nền tảng HRM cấp doanh nghiệp, **Laravel 10 modular monolith, API-first** (REST là
surface chính). Quản lý vòng đời nhân sự, cơ cấu tổ chức (graph), workflow phê duyệt đa cấp dùng chung,
provisioning tài khoản, audit bất biến, contribution scoring, RBAC + delegation.

## Architecture Summary
- Layering: **Controller (mỏng) → Action/Service → Repository → Eloquent Model**.
- Cross-module qua **Domain Events** ([EventServiceProvider](../app/Providers/EventServiceProvider.php));
  ngoại lệ có chủ đích: `ApprovalEngine`, `AuditService`, `NotificationService` gọi trực tiếp.
- Auth: **Sanctum** Bearer token. Phân quyền: middleware `permission:<name>`
  ([CheckPermission](../app/Http/Middleware/CheckPermission.php)) + spatie + delegation.
- Response: envelope qua [ApiResponse](../app/Support/ApiResponse.php).
- 12 module nạp qua [ModuleServiceProvider](../app/Providers/ModuleServiceProvider.php) +
  [config/modules.php](../config/modules.php). Mỗi module tự nạp route prefix `api/v1/<module>`.
- Base path API: `/api/v1` ([RouteServiceProvider](../app/Providers/RouteServiceProvider.php)),
  rate limit 200/phút (auth) · 60 (anon).
- **TDXP (web):** `GET /phongcongnghe` — landing Phòng Công Nghệ (Inertia + React/Vite, mock data trong
  `resources/js/data/`). Không thuộc API envelope; dùng showcase nội bộ, CMS sau này.
- Async: queue (default/notifications/provisioning/audit) + Horizon; cron trong
  [Console/Kernel](../app/Console/Kernel.php).
- Chi tiết: [architecture/](architecture/high-level-architecture.md).

## Core Modules (12)
Thứ tự phụ thuộc: **Permission → Audit → Notification → Department → Employee → Organization →
Approval → Request → Attendance → Leave → Provisioning → Contribution**.
Mỗi module: [docs/modules/](modules/). Map prefix API: [docs/api/README.md](api/README.md).

| Module | Prefix API | Lõi |
|---|---|---|
| Employee | /employees | hồ sơ, lifecycle, timeline |
| Department | /departments | cây phòng ban, positions |
| Organization | /organization | graph nodes/relationships |
| Approval | /approvals | ApprovalEngine (workflow đa cấp) |
| Request | /requests | yêu cầu polymorphic |
| Leave | /leave | đơn nghỉ, quota |
| Attendance | /attendance,/shifts | chấm công, ca |
| Provisioning | /provisioning | account/email/license |
| Contribution | /contribution | điểm & ranking |
| Notification | /notifications | thông báo đa kênh |
| Permission | /permissions | RBAC + delegation |
| Audit | /audit | log bất biến |

## Business Rules (cốt lõi)
Đầy đủ: [business/business-rules.md](business/business-rules.md). Quan trọng nhất:
- Mọi yêu cầu duyệt qua **một** ApprovalEngine; approve bước cuối → approved, reject bất kỳ → rejected.
- Chỉ approver của step (hoặc người có delegation) được quyết định (else 403
  `WORKFLOW_PERMISSION_DENIED`).
- Leave: `end>=start`, `days=diff+1`, approve trừ quota / cancel hoàn quota.
- Attendance: 1 record/ngày, chống check-in kép (409), late/overtime theo shift.
- Audit bất biến, mask field nhạy cảm (`salary`,`bank_account_number`).
- Provisioning onboarding tạo account/email; offboarding revokeAll.

## Coding Standards
`declare(strict_types=1)` mọi file; typed + `readonly` DI; Action `final` + `__invoke`; enum backed.
Controller không chứa nghiệp vụ; Service bọc `DB::transaction`; Repository giữ data access.
Chi tiết: [developer-guide/engineering-standards.md](developer-guide/engineering-standards.md).

## Naming Convention
```txt
Controllers:  EmployeeController            Services:    LeaveService
Actions:      CheckInAction (__invoke)      Engines:     ApprovalEngine
Repositories: EmployeeRepository(+Interface) DTOs:       CreateEmployeeDTO
Events:       EmployeeCreated (quá khứ)     Listeners:   TriggerProvisioningOnCreate
Models:       Employee (số ít)             Enums:       WorkflowStatus
DB tables:    va_hrm_employees (snake plural, prefix va_hrm_ via config/database.php)     DB cols:     department_id
Routes:       /api/v1/employees/{ulid}     Permissions: leave.policy.manage
Commands:     contribution:sync-scores
```
Đầy đủ: [developer-guide/naming-conventions.md](developer-guide/naming-conventions.md).

## Important Files
| File | Vai trò |
|---|---|
| [app/Support/ApiResponse.php](../app/Support/ApiResponse.php) | Envelope response |
| [app/Providers/EventServiceProvider.php](../app/Providers/EventServiceProvider.php) | Wiring event↔listener |
| [app/Providers/ModuleServiceProvider.php](../app/Providers/ModuleServiceProvider.php) | Nạp module |
| [app/Http/Middleware/CheckPermission.php](../app/Http/Middleware/CheckPermission.php) | Kiểm quyền + delegation |
| [app/Concerns/HasAuditLog.php](../app/Concerns/HasAuditLog.php) / [HasUlid.php](../app/Concerns/HasUlid.php) | Audit / ULID |
| [modules/Approval/Engine/ApprovalEngine.php](../modules/Approval/Engine/ApprovalEngine.php) | Lõi phê duyệt |
| [modules/Provisioning/Engine/ProvisioningEngine.php](../modules/Provisioning/Engine/ProvisioningEngine.php) | Lõi provisioning |
| [config/workflow.php](../config/workflow.php) | SLA, escalation, allowed types |
| [config/modules.php](../config/modules.php) | Module enabled + thứ tự |
| [database/migrations/](../database/migrations/) | Schema chính thức |
| [database/seeders/RoleSeeder.php](../database/seeders/RoleSeeder.php) | Role→permission |

## Common Development Tasks
**Tạo Module Mới** (chi tiết: [developer-guide/adding-a-module.md](developer-guide/adding-a-module.md)):
```txt
1. Migration  →  2. Model (BaseModel, Auditable, HasUlid)
3. Repository (+Interface, bind)  →  4. Service  →  5. Action (tuỳ chọn)
6. Controller (mỏng + ApiResponse)  →  7. routes/api.php  →  8. ServiceProvider (prefix api/v1/...)
9. Thêm vào config/modules.php  →  10. Permission/Policy + seeders  →  11. Events/Listeners
12. Docs: modules/*.md + api/*.md + cập nhật README & AI_CONTEXT
```
**Thêm loại Request:** thêm vào `workflow.allowed_workflow_types` (+ bảng chuyên biệt + nhánh
`RequestService::persistSpecific` nếu có dữ liệu riêng) + tạo `workflow_configurations`.
**Thêm Workflow type:** chỉ cần 1 `workflow_configurations` active (không cần code).

## Known Constraints / TODO
- **Không có SPA sản phẩm HRM** — UI/UX & wireframe trong docs là **PROPOSED** (trừ TDXP tại `/phongcongnghe`).
- Một số Engine/Provisioner/Channel là **stub một phần** (escalation/scoring recalculation thật,
  EmailProvisioner/AccountProvisioner/AccessRevoker tích hợp provider, SlackChannel, duyệt
  attendance correction qua workflow) — đánh dấu **TODO: Need Human Validation** trong từng
  [module doc](modules/).
- Wiring "workflow approved → cập nhật leave_request/quota" cần xác minh listener.
- `.env` dev dùng `sync`/`file`; production cần Redis + Horizon + (S3 nếu lưu file).
- `data-structures/` mô tả 53 bảng (gồm tách PII employee, OAuth providers…) **chi tiết hơn** schema
  migration hiện tại — là thiết kế đích khi migrate dữ liệu legacy.

## Điều hướng nhanh
[README.md](README.md) (index) · [project-overview.md](project-overview.md) ·
[database/erd.md](database/erd.md) · [api/README.md](api/README.md) ·
[architecture/workflow-engine.md](architecture/workflow-engine.md).
