# Module: Attendance

## Business Purpose
Chấm công (check-in/out), quản lý ca làm (shifts), tính trễ/tăng ca, và quy trình sửa chấm công
(corrections) cần duyệt.

## Actors
Employee (check-in/out, gửi correction), HR/Manager (xem, quản lý ca).

## Features
- Check-in/out tự lấy IP; 1 bản ghi/ngày (`unique(employee_id,date)`).
- Tính `late_minutes` (theo shift + grace) và `overtime_minutes`.
- Correction: tạo đề nghị sửa (`pending`) → có thể gắn workflow.
- Quản lý ca (`attendance_shifts`), gán ca cho nhân viên (`employee_shifts`).
- Analytics: present/late/absent/avg overtime.

## Screens (PROPOSED)
Attendance dashboard (nút check-in/out) · Logs · Shift management · Correction form.
[wireframe attendance](../wireframe/attendance.md).

## APIs → [docs/api/attendance.md](../api/attendance.md)
`/attendance/check-in|check-out`, `/attendance`, `/attendance/analytics`, `/attendance/{id}`,
`/attendance/corrections`; `/shifts` (CRUD + assign).

## Database Tables → [table-dictionary](../database/table-dictionary.md#attendance_records)
`attendance_shifts`, `employee_shifts`, `attendance_records`, `attendance_corrections`.

## Code chính
[AttendanceController](../../modules/Attendance/Controllers/AttendanceController.php),
[AttendanceService](../../modules/Attendance/Services/AttendanceService.php), ShiftService,
Actions: CheckIn/CheckOut/CorrectAttendance, AttendanceRepository.

## Business rules → [business-rules](../business/business-rules.md#attendance)
R-AT-1..6.

## Dependencies
Depends: Employee. Correction có thể dùng Approval (workflow_id). `resolveShift()` hiện lấy shift
active đầu tiên (chưa map theo `employee_shifts`) → TODO: Need Human Validation.

## Trạng thái hiện thực
✅ Check-in/out + tính trễ/OT + correction + shift CRUD + analytics.
⚠️ Gán ca cá nhân (`employee_shifts`) tồn tại ở schema nhưng `resolveShift()` chưa truy vấn theo đó.

## Future Improvements
- Resolve ca theo `employee_shifts` + ngày; geofencing/IP allowlist; tích hợp leave (ngày nghỉ).

## Liên kết chéo
Flow: [attendance-flow](../flows/attendance-flow.md) · API: [attendance](../api/attendance.md) ·
DB: [attendance_records](../database/table-dictionary.md#attendance_records).
