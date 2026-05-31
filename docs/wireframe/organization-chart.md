# Wireframe — Organization Chart (PROPOSED)

> ⚠️ **PROPOSED — Need Human Validation.** Suy ra từ [api/organization.md](../api/organization.md)
> (`GET /api/v1/organization/graph`).

## Screen Purpose
Trực quan hoá đồ thị tổ chức (nodes/edges), khám phá cây báo cáo và quan hệ giữa nhân viên/phòng ban.

## Layout
```txt
┌───────────────────────────────────────────────┐
│ Header: "Sơ đồ tổ chức"  [department▼][Sync]   │
├───────────────────────────────────────────────┤
│  Canvas đồ thị (zoom/pan)                       │
│     [Dept]──BELONG_TO──[Emp]──REPORT_TO──▶[Mgr]│
│                                                 │
│  Side panel: chi tiết node được chọn            │
│   + reporting-chain                             │
└───────────────────────────────────────────────┘
```

## Components
Graph canvas (node theo `node_type`, edge theo `relationship_type` màu khác nhau), depth control,
node detail panel, Sync button.

## User Actions
Lọc theo phòng ban, xem subtree (depth), chọn node → reporting-chain, tạo/sửa/xoá relationship, Sync.

## Validation Rules
Tạo relationship: `from_node_id`,`to_node_id`,`relationship_type` (enum 6 giá trị), `weight` 0–10.

## Permission Rules
Xem: đăng nhập. **Sync** cần `permission.role.manage`. Sửa relationship: theo phân quyền (TODO xác nhận).

## Loading / Empty / Error States
Loading: spinner canvas; Empty: "Chưa có dữ liệu graph — chạy Sync"; Error: envelope.

## Mobile Layout
Canvas khó dùng trên mobile → fallback danh sách reporting-chain dạng list.
