# Wireframe — Approval Inbox (PROPOSED)

> ⚠️ **PROPOSED — Need Human Validation.** Suy ra từ [api/approvals.md](../api/approvals.md).

## Screen Purpose
Hộp duyệt: danh sách bước đang chờ người dùng quyết định (sắp theo SLA), duyệt/từ chối/uỷ quyền nhanh.

## Layout
```txt
┌───────────────────────────────────────────────┐
│ Tabs: [Chờ duyệt][Lịch sử]      [Analytics]    │
├───────────────────────────────────────────────┤
│ List (sort theo SLA):                          │
│  ▸ Leave request — Nguyễn A   ⏱ còn 4h  [Xem]  │
│      [Duyệt] [Từ chối] [Uỷ quyền]              │
│  ▸ Equipment request — Trần B  ⏱ quá hạn ⚠     │
├───────────────────────────────────────────────┤
│ Drawer chi tiết workflow + các bước            │
└───────────────────────────────────────────────┘
```

## Components
Tabs (queue/history), List item với SLA countdown badge, Action buttons, Workflow detail drawer
(steps timeline), Reject reason modal, Delegate user-picker.

## User Actions
Xem workflow (`/workflows/{id}`), duyệt (notes?), từ chối (reason bắt buộc), uỷ quyền (chọn user),
escalate.

## Validation Rules
Reject: `reason` bắt buộc. Delegate: `to_user_id` bắt buộc.

## Permission Rules
Approve/reject/delegate qua Policy (`approve`/`reject`/`delegate`). Chỉ approver của step (hoặc có
delegation) mới quyết định được → sai sẽ nhận `WORKFLOW_PERMISSION_DENIED` (403).

## Loading / Empty / Error States
Loading skeleton; Empty "Không có gì chờ duyệt 🎉"; Error envelope; badge "quá hạn" khi
`sla_deadline_at < now`.

## Mobile Layout
List item full-width, action buttons trong drawer chi tiết.
