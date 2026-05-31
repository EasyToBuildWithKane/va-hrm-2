# Business — Business Rules

> Các quy tắc nghiệp vụ quan trọng **trích trực tiếp từ code**. Mỗi rule kèm nguồn.

## Approval / Workflow
- **R-AP-1** Mọi request đi qua [ApprovalEngine::initiate()](../../modules/Approval/Engine/ApprovalEngine.php).
  Nếu `workflow_type` không có cấu hình active → ném `WORKFLOW_CONFIG_MISSING` (422).
- **R-AP-2** Chuỗi bước rỗng → ném `WORKFLOW_EMPTY_CHAIN`.
- **R-AP-3** Approve bước cuối (`step_number >= total_steps`) → workflow `approved` + bắn
  `ApprovalWorkflowCompleted`; chưa cuối → tăng `current_step` và thông báo approver kế tiếp.
- **R-AP-4** Reject **bất kỳ** bước → toàn workflow `rejected` ngay, bắn `ApprovalRejected`.
- **R-AP-5** Chỉ `approver_id` của bước (hoặc người có delegation `approval.approve`) mới được quyết
  định; sai → `WORKFLOW_PERMISSION_DENIED` (403). Nguồn: `ApprovalEngine::authorize()`.
- **R-AP-6** SLA mặc định 24h/bước ([workflow.php](../../config/workflow.php) `default_sla_hours`);
  escalation đẩy lên `HR Director` mặc định ([EscalationHandler](../../modules/Approval/Engine/EscalationHandler.php)).
- **R-AP-7** Loại workflow hợp lệ bị giới hạn bởi `workflow.allowed_workflow_types` (12 loại).

## Leave
- **R-LV-1** `end_date` phải ≥ `start_date`, nếu không → `LEAVE_DATE_INVALID` (422).
  Nguồn: [LeaveService::submit()](../../modules/Leave/Services/LeaveService.php).
- **R-LV-2** `days_count = diffInDays(start,end) + 1` (tính cả ngày đầu).
- **R-LV-3** Khi **approved** → trừ quota (`LeaveQuotaService::deductDays`); khi **cancel** một đơn
  đã approved → hoàn quota (`refundDays`).
- **R-LV-4** Submit đơn tạo workflow `leave_request` và gán `workflow_id`.

## Attendance
- **R-AT-1** Không cho check-in 2 lần/ngày → `ATTENDANCE_ALREADY_CHECKED_IN` (409).
- **R-AT-2** Check-out khi chưa check-in → `ATTENDANCE_NO_CHECKIN` (422).
- **R-AT-3** `late_minutes = max(0, trễ - grace_minutes)`; status `late` nếu >0, ngược lại `present`.
- **R-AT-4** `overtime_minutes = max(0, checkout - shift end)`. Nguồn: [AttendanceService](../../modules/Attendance/Services/AttendanceService.php).
- **R-AT-5** Mỗi nhân viên tối đa 1 bản ghi/ngày (`unique(employee_id,date)`).
- **R-AT-6** Correction được tạo ở trạng thái `pending` (chờ duyệt).

## Employee lifecycle
- **R-EM-1** `employee_number` tự sinh `EMP-XXXXXXXX` đảm bảo duy nhất.
- **R-EM-2** Tạo employee tự `firstOrCreate` user theo email nếu chưa có `user_id`.
- **R-EM-3** Terminate → set `employment_status=terminated`, `offboarding_status=in_progress`,
  ghi timeline (bắn event → trigger offboarding provisioning).
- **R-EM-4** Mọi đổi trạng thái/phòng ban đều ghi `employee_timeline`.

## Provisioning
- **R-PV-1** Onboarding → tạo email + account, ghi log, audit `ACTIVATED`, thông báo nhân viên.
- **R-PV-2** Offboarding → `revokeAll` access, audit `DEACTIVATED`.
  Nguồn: [ProvisioningEngine](../../modules/Provisioning/Engine/ProvisioningEngine.php).
- **R-PV-3** License: không gán nếu `used_seats >= total_seats`; gán/thu hồi increment/decrement
  `used_seats`; unique 1 nhân viên/1 license.
- **R-PV-4** Offboarding policy: suspend→disable email sau 30 ngày, thu hồi license & role ngay
  ([provisioning.php](../../config/provisioning.php)).

## Contribution
- **R-CT-1** Điều chỉnh điểm tạo `score_adjustment_request` trạng thái `pending` (cần duyệt).
- **R-CT-2** Trọng số sự kiện & caps (daily 100, monthly 500), decay half-life 180 ngày
  ([config/contribution.php](../../config/contribution.php)).
- **R-CT-3** `contribution_scores` 1 dòng/nhân viên (unique employee_id).

## Audit
- **R-AU-1** Audit log **bất biến** — chỉ `created_at`, không sửa/xoá.
- **R-AU-2** Trường `sensitiveFields` (vd `salary`, `bank_account_number`) bị mask `[REDACTED]`.
- **R-AU-3** `performed_by = 0` nghĩa là hệ thống (không có user đăng nhập).
- **R-AU-4** Retention: hot 2 năm, payroll-sensitive 7 năm; archive cron hàng tháng
  ([config/audit.php](../../config/audit.php)).

## Permission / Delegation
- **R-PM-1** Quyền hiệu lực = permission trực tiếp ∪ qua role ∪ qua delegation đang hiệu lực.
- **R-PM-2** Delegation tối đa 90 ngày ([permission_hrm.php](../../config/permission_hrm.php)).
- **R-PM-3** Middleware `permission:<name>` chặn route; có fallback delegation
  ([CheckPermission](../../app/Http/Middleware/CheckPermission.php)).

## Rate limiting
- **R-RL-1** API: 200 req/phút cho user đã auth, 60 cho ẩn danh
  ([RouteServiceProvider](../../app/Providers/RouteServiceProvider.php)).
