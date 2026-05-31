# Module: Approval

## Business Purpose
**Workflow engine phê duyệt nhiều cấp** dùng chung cho mọi loại requestable (leave, request,
attendance correction, score adjustment...). Hỗ trợ SLA, uỷ quyền (delegation), leo thang (escalation),
cấu hình bước theo `workflow_type`.

## Actors
Approver (theo từng step), người uỷ quyền/nhận uỷ quyền, admin workflow (`approval.workflow.configure`).

## Features
- `initiate()` sinh workflow + steps từ `WorkflowConfiguration`.
- `approve()`/`reject()`/`delegate()`/`escalate()`/`cancel()`.
- Queue (bước chờ tôi) sort theo SLA; history; analytics (overdue...).
- CRUD `workflow_configurations` (steps + escalation JSON).
- Ghi `approval_decisions`; bắn events: ApprovalStepCompleted/WorkflowCompleted/Rejected/Escalated.

## Screens (PROPOSED)
Approval inbox (queue) · Workflow detail (timeline steps) · Workflow config admin.
[wireframe approval-inbox](../wireframe/approval-inbox.md).

## APIs → [docs/api/approvals.md](../api/approvals.md)
`/queue`, `/history`, `/analytics`, `/workflows/{ulid}` (+approve/reject/delegate/escalate),
`/configurations` (CRUD).

## Database Tables → [table-dictionary](../database/table-dictionary.md#approval_workflows)
`workflow_configurations`, `approval_workflows`, `approval_steps`, `approval_decisions`,
`approval_delegations`.

## Code chính
[ApprovalEngine](../../modules/Approval/Engine/ApprovalEngine.php) (lõi),
ApprovalChainResolver, SlaTracker, DelegationResolver,
[EscalationHandler](../../modules/Approval/Engine/EscalationHandler.php),
[ApprovalController](../../modules/Approval/Controllers/ApprovalController.php), ApprovalService.

## Business rules → [business-rules](../business/business-rules.md#approval--workflow)
R-AP-1..7 (config missing, empty chain, last-step complete, reject toàn cục, authorize, SLA 24h, allowed types).

## Dependencies
Depends: Notification (thông báo approver), Audit (AuditApprovalDecisionListener). Được dùng bởi:
Leave, Request, Attendance (correction), Contribution (adjustment), Provisioning.

## Trạng thái hiện thực
✅ Engine approve/reject/delegate/escalate + config + analytics đầy đủ.
⚠️ `auto_approve.enabled=true` trong config nhưng logic auto-approve chưa rõ trong Engine →
TODO: Need Human Validation. Chi tiết `ApprovalChainResolver`/`SlaTracker`/`DelegationResolver` chưa
trích trong tài liệu.

## Future Improvements
- Parallel steps (duyệt song song); điều kiện rẽ nhánh; nhắc SLA qua notification trước hạn.

## Liên kết chéo
Engine doc: [workflow-engine](../architecture/workflow-engine.md) · API: [approvals](../api/approvals.md)
· DB: [approval_workflows](../database/table-dictionary.md#approval_workflows).
