# Module: Audit

## Business Purpose
Ghi **audit log bất biến** cho mọi thay đổi trên model nhạy cảm (polymorphic), kèm diff before/after,
redaction dữ liệu nhạy cảm, và archive theo retention.

## Actors
Auditor / HR Director (`audit.view`, `audit.export`). Chỉ đọc.

## Features
- Tự ghi log qua `AuditObserver` (gắn bởi trait [HasAuditLog](../../app/Concerns/HasAuditLog.php)) khi
  created/updated/deleted/restored.
- Ghi thủ công sự kiện nghiệp vụ (approved/rejected/activated/deactivated...) qua `AuditService::log()`.
- Diff before/after (`DiffService`); mask `sensitiveFields` → `[REDACTED]`.
- Truy vấn theo employee / workflow (`context->workflow_id`) / provisioning / permission.
- Archive sang `audit_logs_archive` (cron `audit:archive-old` hàng tháng).

## Screens (PROPOSED)
Audit log explorer (filter) · Log detail + diff viewer · Export.

## APIs → [docs/api/audit.md](../api/audit.md)
`/logs`, `/logs/{id}`, `/logs/{id}/diff`, `/employees/{employee}`, `/workflows/{id}`,
`/provisioning/{employee}`, `/permissions`, `/export`.

## Database Tables → [table-dictionary](../database/table-dictionary.md#audit_logs)
`audit_logs`, `audit_logs_archive` (schema giống hệt).

## Code chính
[AuditService](../../modules/Audit/Services/AuditService.php), DiffService, AuditArchiveService,
AuditObserver, AuditRepository, AuditApprovalDecisionListener,
[AuditController](../../modules/Audit/Controllers/AuditController.php).

## Business rules → [business-rules](../business/business-rules.md#audit)
R-AU-1..4 (bất biến, redaction, performed_by=0=system, retention 2/7 năm).

## Dependencies
Depends: middleware `AuditRequest` (nạp IP/UA), enum `AuditEvent`. Được mọi model `implements
Auditable` sử dụng (Employee, Department, Leave...).

## Trạng thái hiện thực
✅ AuditService.log + buildDiff + truy vấn đa chiều + listener approval.
TODO: Need Human Validation — `AuditObserver` (map created/updated/deleted → AuditEvent + lấy
old/new/changed), `DiffService::compute`, `AuditArchiveService`.

## Future Improvements
- Ký/hashing chống sửa (tamper-evident); export CSV/PDF thật (hiện chỉ message); UI timeline.

## Liên kết chéo
API: [audit](../api/audit.md) · Backend cơ chế: [backend-architecture §4](../architecture/backend-architecture.md)
· DB: [audit_logs](../database/table-dictionary.md#audit_logs).
