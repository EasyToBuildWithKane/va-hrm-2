# API — Approvals

Prefix `/api/v1/approvals` · Controllers:
[ApprovalController](../../modules/Approval/Controllers/ApprovalController.php),
[WorkflowConfigurationController](../../modules/Approval/Controllers/WorkflowConfigurationController.php) ·
Module: [approval.md](../modules/approval.md) · Engine: [workflow-engine](../architecture/workflow-engine.md) ·
Bảng: [approval_workflows](../database/table-dictionary.md#approval_workflows).

| Method | Path | Purpose | Permission/Policy |
|---|---|---|---|
| GET | `/queue` | Bước chờ tôi duyệt (sort theo SLA) | — |
| GET | `/history` | Lịch sử quyết định của tôi | — |
| GET | `/analytics` | Thống kê workflow (in_progress/approved/rejected/escalated/overdue) | — |
| GET | `/workflows/{workflow}` | Chi tiết workflow (steps, requestable, creator) | Policy `view` |
| POST | `/workflows/{workflow}/approve` | Duyệt bước hiện tại (body `notes?`) | Policy `approve` |
| POST | `/workflows/{workflow}/reject` | Từ chối (body `reason*`) | Policy `reject` |
| POST | `/workflows/{workflow}/delegate` | Uỷ quyền (body `to_user_id*, reason?`) | Policy `delegate` |
| POST | `/workflows/{workflow}/escalate` | Leo thang bước hiện tại | — |
| GET | `/configurations` | DS cấu hình workflow | `permission:approval.workflow.configure` |
| POST | `/configurations` | Tạo cấu hình (`workflow_type, config.steps[]`) | `permission:approval.workflow.configure` |
| PUT | `/configurations/{configuration}` | Sửa cấu hình | `permission:approval.workflow.configure` |

## POST `/workflows/{workflow}/approve`
Duyệt `currentStep()`. Nếu là bước cuối → workflow `approved` (bắn `ApprovalWorkflowCompleted`);
ngược lại tiến bước. Không phải approver → 403 `WORKFLOW_PERMISSION_DENIED`.
```json
{ "notes": "Looks good" }
```

## POST `/workflows/{workflow}/reject`
```json
{ "reason": "Budget exceeded" }
```
→ workflow `rejected`, bắn `ApprovalRejected`.

## POST `/configurations`
```json
{ "workflow_type": "leave_request", "config": { "steps": [ { "approver_role": "Department Manager" } ] }, "is_active": true }
```

Related: [flow request-approval](../flows/request-approval-flow.md) · [wireframe approval-inbox](../wireframe/approval-inbox.md).
