# Module: Leave

## Business Purpose
Quản lý nghỉ phép: loại phép, hạn mức (quota) theo năm, đơn xin nghỉ với phê duyệt qua workflow, và
chính sách theo phòng ban.

## Actors
Employee (tạo/huỷ đơn, xem quota), Department Manager (duyệt), HR (quản lý policy). Policy `LeavePolicy`.

## Features
- Loại nghỉ (`leave_types`) với quy tắc: days_per_year, is_paid, carry_forward, min_notice_days...
- Quota theo (employee, type, year): entitled/used/carried.
- Submit đơn → khởi tạo workflow `leave_request`; approve trừ quota, cancel hoàn quota.
- Approvals queue cho quản lý; analytics; CRUD policy (rules JSON).

## Screens (PROPOSED)
My leave (quota + lịch sử) · Submit request · Approvals · Policy admin. [wireframe leave-request](../wireframe/leave-request.md).

## APIs → [docs/api/leave.md](../api/leave.md)
`/types`, `/quotas`, `/quotas/{employee}`, `/requests` (CRUD), `/approvals`, `/analytics`,
`/policies` (CRUD).

## Database Tables → [table-dictionary](../database/table-dictionary.md#leave_requests)
`leave_types`, `leave_policies`, `leave_quotas`, `leave_requests`.

## Code chính
[LeaveController](../../modules/Leave/Controllers/LeaveController.php),
[LeaveService](../../modules/Leave/Services/LeaveService.php) (submit/approve/reject/cancel),
LeaveQuotaService (deduct/refund), SubmitLeaveRequestAction, LeaveRepository.

## Business rules (xem [business-rules](../business/business-rules.md#leave))
R-LV-1 end≥start · R-LV-2 days = diff+1 · R-LV-3 trừ/hoàn quota · R-LV-4 tạo workflow.

## Dependencies
Depends: Employee, **Approval** (inject `ApprovalEngine`), Department (policy). `LeaveRequest`
implements Auditable.

## Trạng thái hiện thực
✅ Submit/approve/reject/cancel + quota + analytics + policy.
⚠️ `approve()`/`reject()` của LeaveService được gọi bởi listener cập nhật trạng thái sau workflow;
liên kết workflow↔leave qua `workflow_id`. TODO: Need Human Validation — `LeaveQuotaService` accrual
chi tiết.

## Future Improvements
- Kiểm `min_notice_days`/blackout từ policy khi submit; lịch nghỉ nhóm; tích hợp attendance (ngày nghỉ).

## Liên kết chéo
Flow: [leave-approval-flow](../flows/leave-approval-flow.md) · API: [leave](../api/leave.md) ·
Approval: [approval](approval.md) · DB: [leave_requests](../database/table-dictionary.md#leave_requests).
