# Module: Permission

## Business Purpose
RBAC nền tảng (dựa trên **spatie/laravel-permission**): role, permission, gán cho user, ma trận
role×permission, và **uỷ quyền tạm thời** (delegation).

## Actors
Super Admin / HR Director (`permission.role.manage`, `permission.matrix.view`, `permission.delegate`).

## Features
- CRUD role + sync permissions; ma trận role×permission.
- Xem effective permissions của user (trực tiếp ∪ role ∪ delegation).
- Gán/gỡ role cho user.
- Delegation: uỷ quyền approval/role/permission trong khoảng thời gian (≤90 ngày).
- Middleware `permission:<name>` bảo vệ route (fallback delegation).

## Screens (PROPOSED)
Role management · Permission matrix · User roles · Delegation management.

## APIs → [docs/api/permissions.md](../api/permissions.md)
`/roles` (CRUD + sync), `/matrix`, `/users/{user}` (+assign/revoke), `/delegate` (CRUD).

## Database Tables → [table-dictionary](../database/table-dictionary.md#identity--rbac)
`roles`, `permissions`, `role_has_permissions`, `model_has_roles`, `model_has_permissions`,
`permission_delegations`.

## Code chính
[PermissionService](../../modules/Permission/Services/PermissionService.php) (effective, delegate),
RoleService (list/create/sync/matrix), RoleController, PermissionController,
PermissionDelegationController, models Role/Permission/PermissionDelegation.
Middleware: [CheckPermission](../../app/Http/Middleware/CheckPermission.php). Enum
[PermissionScope](../../app/Enums/PermissionScope.php).

## Seed dữ liệu
[PermissionSeeder](../../database/seeders/PermissionSeeder.php) (~40 permission),
[RoleSeeder](../../database/seeders/RoleSeeder.php) (9 role, mapping permission).

## Business rules → [business-rules](../business/business-rules.md#permission--delegation)
R-PM-1..3.

## Dependencies
Module nền — nạp **đầu tiên** ([config/modules.php](../../config/modules.php)). Được Audit, Employee,
Approval... dùng để authorize.

## Trạng thái hiện thực
✅ Role/permission/delegation đầy đủ + matrix + middleware.
TODO: Need Human Validation — `RoleService::buildMatrix()`, áp dụng `scope` (organization/department/
team/own) vào kiểm quyền (hiện scope là enum + config, chưa thấy enforcement chi tiết).

## Future Improvements
- Enforce scope theo phòng/team trong policy; UI audit thay đổi quyền; expiry tự động cho delegation.

## Liên kết chéo
API: [permissions](../api/permissions.md) · RBAC tổng quan: [personas-actors](../business/personas-actors.md)
· DB: [permission_delegations](../database/table-dictionary.md#permission_delegations).
