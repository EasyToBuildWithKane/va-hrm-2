# Wireframe — Leave Request (PROPOSED)

> ⚠️ **PROPOSED — Need Human Validation.** Suy ra từ [api/leave.md](../api/leave.md).

## Screen Purpose
Nhân viên tạo đơn nghỉ, xem quota còn lại và trạng thái đơn.

## Layout
```txt
┌───────────────────────────────────────────────┐
│ Header: "Xin nghỉ phép"        Quota: 8/12 ngày│
├───────────────────────────────────────────────┤
│ Form:  Loại nghỉ [▼]                            │
│        Từ ngày [📅]   Đến ngày [📅]   = 3 ngày  │
│        Lý do  [textarea]                         │
│        Đính kèm [upload]                         │
│                         [Huỷ]  [Gửi đơn]        │
├───────────────────────────────────────────────┤
│ Đơn của tôi: bảng status (pending/approved...)  │
└───────────────────────────────────────────────┘
```

## Components
Select loại nghỉ, Date range picker, Textarea, Upload, Quota badge, Table đơn, Status badge.

## User Actions
Chọn loại + ngày (tự tính số ngày), gửi đơn (`POST /requests`), huỷ đơn (`DELETE`), xem chi tiết.

## Validation Rules
`leave_type_id` bắt buộc; `end_date >= start_date` (nếu sai → `LEAVE_DATE_INVALID`); attachments tuỳ chọn.

## Permission Rules
Cần `leave.request.create`. Huỷ: Policy `cancel`. Phải có employee profile.

## Loading / Empty / Error States
Loading khi submit (disable nút); Empty "Chưa có đơn"; Error hiển thị `code`/`message`.

## Mobile Layout
Form 1 cột, date picker full-screen; bảng đơn → card.
