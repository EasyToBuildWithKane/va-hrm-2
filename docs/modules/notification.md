# Module: Notification

## Business Purpose
Gửi thông báo đa kênh (in-app, email) cho user dựa trên template, và quản lý trạng thái đã đọc.

## Actors
Mọi user (nhận & đọc thông báo của mình). Hệ thống (tạo thông báo qua service).

## Features
- `notify(user, template, context, channels)` render template + gửi kênh in_app/email.
- `notifyMany()` cho nhiều user.
- Đánh dấu đã đọc / đọc tất cả; đếm `unread`.
- Template registry: approval.requested/completed/rejected, provisioning.completed, employee.welcome.

## Screens (PROPOSED)
Notification bell + dropdown · Notification center (list, mark read). [wireframe notifications](../wireframe/notifications.md).

## APIs → [docs/api/notifications.md](../api/notifications.md)
`GET /`, `POST /{notification}/read`, `POST /read-all`.

## Database Tables → [table-dictionary](../database/table-dictionary.md#user_notifications)
`user_notifications`.

## Code chính
[NotificationService](../../modules/Notification/Services/NotificationService.php) (singleton),
Channels: InAppChannel, EmailChannel, SlackChannel,
[TemplateRegistry](../../modules/Notification/Templates/TemplateRegistry.php),
SendWelcomeNotificationListener, NotificationController.

## Dependencies
Được dùng bởi: Approval (notify approver/requestor), Provisioning (provisioning.completed),
Employee (welcome qua listener). Mail: SMTP.

## Trạng thái hiện thực
✅ in_app + email + templates + read/unread.
⚠️ `SlackChannel` có class nhưng chưa được gọi trong flow → TODO: Need Human Validation.
TODO — chi tiết InAppChannel/EmailChannel::send().

## Future Improvements
- Web push/realtime (broadcast hiện `log`); preference per-user per-channel; gom nhóm thông báo.

## Liên kết chéo
API: [notifications](../api/notifications.md) · Approval: [approval](approval.md) ·
DB: [user_notifications](../database/table-dictionary.md#user_notifications).
