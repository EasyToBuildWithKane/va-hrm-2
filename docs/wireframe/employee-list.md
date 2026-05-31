# Wireframe — Employee List (PROPOSED)

> ⚠️ **PROPOSED — Need Human Validation.** Chưa có frontend; suy ra từ
> [api/employees.md](../api/employees.md) (`GET /api/v1/employees`).

## Screen Purpose
Danh sách nhân viên có tìm kiếm/lọc/phân trang để HR/Manager tra cứu và mở chi tiết.

## Layout
```txt
┌───────────────────────────────────────────────┐
│ Header: "Nhân viên"            [+ Tạo nhân viên]│
├───────────────────────────────────────────────┤
│ Toolbar: [search] [status▼] [department▼] [type▼]│
├───────────────────────────────────────────────┤
│ Table:                                         │
│  # | Họ tên | Mã NV | Phòng | Chức danh | Trạng│
│    | ...    | EMP-..| ...   | ...       | ●active│
├───────────────────────────────────────────────┤
│ Footer: phân trang (current/total/per_page)    │
└───────────────────────────────────────────────┘
```

## Components
Toolbar (search + Select filters), Table sortable, Status badge, Pagination, Button "Tạo".

## User Actions
Tìm/lọc (search,status,department_id,employment_type,from,to,sort,direction) · sort cột · mở chi tiết
([employee-detail.md](employee-detail.md)) · tạo mới · xoá (archive)/restore.

## Validation Rules
Filter `from/to` là date; `per_page` số. (Tạo/sửa: xem employee-detail.)

## Permission Rules
Cần `viewAny` (Policy). Nút Tạo/Xoá ẩn nếu không có `create`/`delete`. Employee thường chỉ thấy mình.

## Loading / Empty / Error States
- Loading: skeleton rows.
- Empty: "Chưa có nhân viên" + CTA tạo.
- Error: hiển thị `message`/`code` + nút thử lại.

## Mobile Layout
Table → card list (họ tên + mã + trạng thái); filter gom vào bottom-sheet.
