# Wireframe — Employee Detail (PROPOSED)

> ⚠️ **PROPOSED — Need Human Validation.** Suy ra từ [api/employees.md](../api/employees.md).

## Screen Purpose
Xem & quản lý toàn bộ thông tin một nhân viên: hồ sơ, hợp đồng, tài liệu, timeline, hành động vòng đời.

## Layout
```txt
┌───────────────────────────────────────────────┐
│ ◀ Back   Nguyễn Văn A (EMP-XXXX)   ●active  ⋮  │
├───────────────────────────────────────────────┤
│ Tabs: [Hồ sơ][Hợp đồng][Tài liệu][Timeline]   │
├───────────────────────────────────────────────┤
│ Tab Hồ sơ: phòng ban, chức danh, manager,      │
│   ngày vào, loại HĐ ...   [Sửa]                 │
│ (salary/bank: ẩn — chỉ role có quyền)          │
├───────────────────────────────────────────────┤
│ Actions: [Chuyển phòng][Onboard][Terminate]    │
└───────────────────────────────────────────────┘
```

## Components
Tabs, Detail fields, Edit drawer/modal, Timeline list, Action buttons (confirm modal).

## User Actions
Sửa hồ sơ (`PUT`), chuyển phòng (`/transfer`), onboard/offboard/terminate, thêm hợp đồng/tài liệu,
xem timeline.

## Validation Rules
Edit: các field theo `UpdateEmployeeRequest`. Terminate: `reason` bắt buộc, `effective_date` là date.

## Permission Rules
`view` để xem; `update` để sửa/transfer; `terminate`/`onboard` cho hành động vòng đời;
`employee.salary.view` mới thấy lương.

## Loading / Empty / Error States
Loading skeleton theo tab; Empty cho tab chưa có dữ liệu (vd chưa có tài liệu); Error dùng envelope.

## Mobile Layout
Tabs cuộn ngang; actions gom vào menu ⋮.
