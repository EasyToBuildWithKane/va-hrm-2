# API — Attendance & Shifts

Prefix module: `/api/v1` (route file tự thêm `attendance/` và `shifts/`). Controllers:
[AttendanceController](../../modules/Attendance/Controllers/AttendanceController.php),
[ShiftController](../../modules/Attendance/Controllers/ShiftController.php) ·
Module: [attendance.md](../modules/attendance.md) ·
Bảng: [attendance_records](../database/table-dictionary.md#attendance_records),
[attendance_shifts](../database/table-dictionary.md#attendance_shifts).

## Attendance — `/api/v1/attendance`
| Method | Path | Purpose |
|---|---|---|
| POST | `/attendance/check-in` | Check-in (IP tự lấy). 2 lần/ngày → 409 `ATTENDANCE_ALREADY_CHECKED_IN` |
| POST | `/attendance/check-out` | Check-out. Chưa check-in → 422 `ATTENDANCE_NO_CHECKIN` |
| GET | `/attendance` | List (filter `employee_id,status,from,to`) |
| GET | `/attendance/analytics` | Thống kê (query `employee_id?`) |
| GET | `/attendance/{attendance}` | Chi tiết |
| POST | `/attendance/corrections` | Gửi đề nghị sửa (`attendance_record_id*, proposed_values*, reason*`) |

`check-in` tính `late_minutes` theo shift+grace; `check-out` tính `overtime_minutes`.

## Shifts — `/api/v1/shifts`
| Method | Path | Purpose |
|---|---|---|
| GET | `/shifts` | DS ca |
| POST | `/shifts` | Tạo ca (`name,code,start_time,end_time,grace_minutes,break_minutes,working_days`) |
| PUT | `/shifts/{shift}` | Sửa ca |
| DELETE | `/shifts/{shift}` | Xoá ca |
| POST | `/shifts/{shift}/assign` | Gán ca cho nhân viên (`employee_ids[],valid_from,valid_until?`) |

## POST `/shifts`
```json
{ "name": "Office", "code": "OFF", "start_time": "08:30", "end_time": "17:30",
  "grace_minutes": 15, "break_minutes": 60, "working_days": [1,2,3,4,5] }
```

Related: [flow attendance](../flows/attendance-flow.md) · [wireframe attendance](../wireframe/attendance.md).
