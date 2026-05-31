# API — Audit

Prefix `/api/v1/audit` · Controller: [AuditController](../../modules/Audit/Controllers/AuditController.php) ·
Module: [audit.md](../modules/audit.md) · Bảng: [audit_logs](../database/table-dictionary.md#audit_logs).
Hầu hết route yêu cầu `permission:audit.view`.

| Method | Path | Purpose | Permission |
|---|---|---|---|
| GET | `/logs` | List (filter `event,module,performed_by,from,to`) | `audit.view` |
| GET | `/logs/{log}` | Chi tiết 1 log | `audit.view` |
| GET | `/logs/{log}/diff` | Diff before/after (`DiffService`) | `audit.view` |
| GET | `/employees/{employee}` | Audit của 1 nhân viên | `audit.view` |
| GET | `/workflows/{workflowId}` | Audit theo workflow (`context->workflow_id`) | `audit.view` |
| GET | `/provisioning/{employee}` | Audit provisioning (activated/deactivated/...) | `audit.view` |
| GET | `/permissions` | Audit liên quan Permission/Role | `audit.view` |
| GET | `/export` | Xếp hàng export (trả message) | `permission:audit.export` |

## GET `/logs/{log}/diff`
**Response**
```json
{ "success": true, "data": { "salary": { "before": "[REDACTED]", "after": "[REDACTED]", "type": "modified" } } }
```
> Audit bất biến; trường nhạy cảm bị mask `[REDACTED]`. Retention: hot 2 năm, payroll 7 năm
> ([config/audit.php](../../config/audit.php)).
