# API — Leave

Prefix `/api/v1/leave` · Controller: [LeaveController](../../modules/Leave/Controllers/LeaveController.php) ·
Module: [leave.md](../modules/leave.md) · Bảng: [leave_requests](../database/table-dictionary.md#leave_requests),
[leave_quotas](../database/table-dictionary.md#leave_quotas), [leave_types](../database/table-dictionary.md#leave_types).

| Method | Path | Purpose | Permission |
|---|---|---|---|
| GET | `/types` | Loại nghỉ đang active | — |
| GET | `/quotas` | Quota của chính mình (năm hiện tại) | — |
| GET | `/quotas/{employee}` | Quota của 1 nhân viên | — |
| GET | `/requests` | List đơn (filter `employee_id,status,leave_type_id,from,to`) | — |
| POST | `/requests` | Gửi đơn nghỉ | — |
| GET | `/requests/{leaveRequest}` | Chi tiết (load employee/type/workflow.steps) | Policy `view` |
| DELETE | `/requests/{leaveRequest}` | Huỷ đơn (hoàn quota nếu đã approved) | Policy `cancel` |
| GET | `/approvals` | Đơn pending của nhân viên do mình quản lý | — |
| GET | `/analytics` | Thống kê tổng/pending/approved/rejected/quota_used | — |
| GET | `/policies` | DS policy | — |
| POST | `/policies` | Tạo policy | `permission:leave.policy.manage` |
| PUT | `/policies/{policy}` | Sửa policy | `permission:leave.policy.manage` |

## POST `/requests` — gửi đơn
**Request** (validate inline trong controller):
```json
{ "leave_type_id": 1, "start_date": "2026-06-01", "end_date": "2026-06-03",
  "reason": "Family trip", "attachments": [] }
```
Rule: `end_date >= start_date` (sai → 422 `LEAVE_DATE_INVALID`). `days_count = số ngày + 1`.
Tạo đơn `pending` + khởi tạo workflow `leave_request`.
**Response 201**: object `LeaveRequest`.

## POST `/policies`
```json
{ "leave_type_id": 1, "department_id": null, "rules": { "min_notice_days": 3 } }
```

Related: [flow leave-approval](../flows/leave-approval-flow.md) · [wireframe leave-request](../wireframe/leave-request.md)
· [Approvals API](approvals.md).
