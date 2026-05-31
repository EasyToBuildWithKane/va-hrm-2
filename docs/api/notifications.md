# API — Notifications

Prefix `/api/v1/notifications` · Controller:
[NotificationController](../../modules/Notification/Controllers/NotificationController.php) ·
Module: [notification.md](../modules/notification.md) ·
Bảng: [user_notifications](../database/table-dictionary.md#user_notifications).

| Method | Path | Purpose |
|---|---|---|
| GET | `/` | Thông báo của tôi (query `unread_only?,per_page`). `meta.unread` = số chưa đọc |
| POST | `/{notification}/read` | Đánh dấu đã đọc (chỉ chủ sở hữu, sai → 403) |
| POST | `/read-all` | Đánh dấu tất cả đã đọc |

## GET `/`
**Response**
```json
{ "success": true,
  "data": [ { "ulid": "...", "type": "approval.requested", "title": "...", "body": "...", "read_at": null } ],
  "meta": { "current_page": 1, "per_page": 15, "total": 30, "unread": 4 } }
```

> Thông báo được tạo bởi [NotificationService](../../modules/Notification/Services/NotificationService.php)
> (kênh `in_app`, `email`) với template từ
> [TemplateRegistry](../../modules/Notification/Templates/TemplateRegistry.php):
> `approval.requested`, `approval.completed`, `approval.rejected`, `provisioning.completed`,
> `employee.welcome`. Kênh `Slack` có class nhưng **TODO: Need Human Validation** (chưa nối vào flow).
