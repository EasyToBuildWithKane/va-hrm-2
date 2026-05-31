# Wireframe — Notifications (PROPOSED)

> ⚠️ **PROPOSED — Need Human Validation.** Suy ra từ [api/notifications.md](../api/notifications.md).

## Screen Purpose
Trung tâm thông báo: xem danh sách thông báo, đếm chưa đọc, đánh dấu đã đọc.

## Layout
```txt
┌───────────────────────────┐
│ 🔔 Thông báo  (3 chưa đọc) │
│ [☑ Đọc tất cả] [unread ☐] │
├───────────────────────────┤
│ ● New approval request     │
│   You have a new leave...  │
│ ○ Your request was approved│
└───────────────────────────┘
```

## Components
Bell badge (unread count), List item (● chưa đọc / ○ đã đọc), toggle "chỉ chưa đọc", "Đọc tất cả",
action_url link.

## User Actions
Xem (`GET /`), lọc unread, đánh dấu đã đọc (`/{id}/read`), đọc tất cả (`/read-all`), click action_url.

## Validation Rules
Chỉ chủ sở hữu đọc được thông báo của mình (sai → 403).

## Permission Rules
Chỉ cần đăng nhập; scope theo `user_id` hiện tại.

## Loading / Empty / Error States
Loading skeleton; Empty "Không có thông báo"; Error envelope.

## Mobile Layout
Dropdown → full-screen sheet; item full-width.
