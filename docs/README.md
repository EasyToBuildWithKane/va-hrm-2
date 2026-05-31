# VA-HRM — Documentation (Single Source of Truth)

> Hệ thống tài liệu "AI-Readable Codebase Documentation". Mục tiêu: người mới hiểu hệ thống trong
> ~30 phút; AI Agent đọc `/docs` là hiểu toàn bộ codebase. Tài liệu suy luận từ **source code thật**;
> phần chưa chắc/đề xuất đánh dấu `TODO: Need Human Validation` / `PROPOSED`.

**Hệ thống:** Laravel 10 modular monolith · API-first (`/api/v1`) · Sanctum · MySQL · Redis/Horizon ·
spatie permission · 12 module. KHÔNG có frontend (UI là đề xuất).

---

## 🚀 Đọc theo vai trò

| Bạn là… | Đọc theo thứ tự |
|---|---|
| **Người mới (30')** | [project-overview.md](project-overview.md) → [AI_CONTEXT.md](AI_CONTEXT.md) → 1 module bất kỳ |
| **AI Agent** | Cursor rule: [`.cursor/rules/va-hrm-documentation.mdc`](../.cursor/rules/va-hrm-documentation.mdc) · [AI_CONTEXT.md](AI_CONTEXT.md) → tra cứu chi tiết theo link |
| **Backend Dev** | [developer-guide/getting-started.md](developer-guide/getting-started.md) → [architecture/](architecture/backend-architecture.md) → [api/](api/README.md) |
| **BA / PM** | [business/product-vision.md](business/product-vision.md) → [business/business-rules.md](business/business-rules.md) → [flows/](flows/leave-approval-flow.md) |
| **Designer (FE tương lai)** | [uiux/design-system.md](uiux/design-system.md) → [wireframe/](wireframe/employee-list.md) (PROPOSED) |
| **DBA** | [database/erd.md](database/erd.md) → [database/table-dictionary.md](database/table-dictionary.md) |

---

## 📁 Cấu trúc /docs

| Thư mục | Nội dung |
|---|---|
| [project-overview.md](project-overview.md) | Tổng quan: vision, stack, folder structure (Phase 1) |
| [AI_CONTEXT.md](AI_CONTEXT.md) | **Knowledge base cho AI** — file quan trọng nhất (Phase 8) |
| [architecture/](architecture/high-level-architecture.md) | High-level, module, request flow, backend, workflow engine (Phase 9) |
| [business/](business/product-vision.md) | Vision, personas/actors, business rules |
| [modules/](modules/employee.md) | 12 module (Phase 2) |
| [database/](database/erd.md) | ERD (mermaid) + table dictionary (Phase 4) |
| [api/](api/README.md) | REST API v1 theo nhóm route (Phase 5) |
| [flows/](flows/leave-approval-flow.md) | Business flows (Phase 3) |
| [uiux/](uiux/design-system.md) | Design system — PROPOSED (Phase 6) |
| [wireframe/](wireframe/employee-list.md) | Wireframe màn hình — PROPOSED (Phase 7) |
| [developer-guide/](developer-guide/getting-started.md) | Setup, standards, naming, thêm module, testing |

---

## 🧭 Bản đồ liên kết chéo (Module ⇄ API ⇄ DB ⇄ Flow ⇄ Wireframe)

| Module | API | Bảng chính | Flow | Wireframe |
|---|---|---|---|---|
| [Employee](modules/employee.md) | [employees](api/employees.md) | employees | [onboarding](flows/employee-onboarding.md) | [list](wireframe/employee-list.md)/[detail](wireframe/employee-detail.md) |
| [Department](modules/department.md) | [departments](api/departments.md) | departments | – | [org-chart](wireframe/organization-chart.md) |
| [Organization](modules/organization.md) | [organization](api/organization.md) | organization_nodes | – | [org-chart](wireframe/organization-chart.md) |
| [Approval](modules/approval.md) | [approvals](api/approvals.md) | approval_workflows | [request-approval](flows/request-approval-flow.md) | [approval-inbox](wireframe/approval-inbox.md) |
| [Request](modules/request.md) | [requests](api/requests.md) | workflow_requests | [request-approval](flows/request-approval-flow.md) | – |
| [Leave](modules/leave.md) | [leave](api/leave.md) | leave_requests | [leave-approval](flows/leave-approval-flow.md) | [leave-request](wireframe/leave-request.md) |
| [Attendance](modules/attendance.md) | [attendance](api/attendance.md) | attendance_records | [attendance](flows/attendance-flow.md) | [attendance](wireframe/attendance.md) |
| [Provisioning](modules/provisioning.md) | [provisioning](api/provisioning.md) | provisioning_requests | [offboarding](flows/offboarding-provisioning.md) | – |
| [Contribution](modules/contribution.md) | [contribution](api/contribution.md) | contribution_scores | – | – |
| [Notification](modules/notification.md) | [notifications](api/notifications.md) | user_notifications | – | [notifications](wireframe/notifications.md) |
| [Permission](modules/permission.md) | [permissions](api/permissions.md) | permission_delegations | – | – |
| [Audit](modules/audit.md) | [audit](api/audit.md) | audit_logs | – | – |

---

## ⚠️ Lưu ý
- Phần **PROPOSED**: toàn bộ `uiux/` và `wireframe/` (chưa có frontend trong code).
- Phần **TODO: Need Human Validation**: một số engine/integration còn stub — xem chú thích trong
  từng module/flow.
- Tham chiếu thiết kế schema mở rộng (53 bảng): [../data-structures/](../data-structures/README.md).
