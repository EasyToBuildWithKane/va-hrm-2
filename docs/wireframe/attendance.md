# Wireframe — Attendance (PROPOSED)

> ⚠️ **PROPOSED — Need Human Validation.** Suy ra từ [api/attendance.md](../api/attendance.md).

## Screen Purpose
Nhân viên check-in/out trong ngày và xem lịch sử chấm công; HR xem/quản lý.

## Layout
```txt
┌───────────────────────────────────────────────┐
│ Hôm nay: 30/05/2026   Ca: Hành chính 08:00–17:00│
│   [ CHECK-IN ]  hoặc  [ CHECK-OUT ]            │
│   Trạng thái: ● present (trễ 0')               │
├───────────────────────────────────────────────┤
│ Lịch sử: bảng ngày | in | out | trễ | OT | TT  │
│ [+ Gửi correction]                             │
└───────────────────────────────────────────────┘
```

## Components
Big check-in/out button (đổi theo trạng thái ngày), Status badge, History table, Correction form modal.

## User Actions
Check-in (`/check-in`), check-out (`/check-out`), lọc lịch sử (employee_id,status,from,to), gửi
correction (`/corrections`).

## Validation Rules
Correction: `attendance_record_id`,`proposed_values`,`reason` bắt buộc. Check-in chặn nếu đã có
(409), check-out chặn nếu chưa check-in (422).

## Permission Rules
Check-in/out cần employee profile. Xem của người khác: theo phân quyền (TODO xác nhận).

## Loading / Empty / Error States
Loading khi bấm (disable); Empty "Chưa có dữ liệu"; Error: hiển thị `code` (vd ALREADY_CHECKED_IN).

## Mobile Layout
Ưu tiên mobile (chấm công tại chỗ): nút lớn, lịch sử dạng card.
