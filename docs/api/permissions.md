# API — Permissions & Roles

Prefix `/api/v1/permissions` · Controllers:
[RoleController](../../modules/Permission/Controllers/RoleController.php),
[PermissionController](../../modules/Permission/Controllers/PermissionController.php),
[PermissionDelegationController](../../modules/Permission/Controllers/PermissionDelegationController.php) ·
Module: [permission.md](../modules/permission.md).

| Method | Path | Purpose | Permission |
|---|---|---|---|
| GET | `/roles` | DS role | — |
| POST | `/roles` | Tạo role (`name,permissions[]`) | `permission:permission.role.manage` |
| PUT | `/roles/{role}` | Sửa role / permissions | `permission:permission.role.manage` |
| POST | `/roles/{role}/sync` | Sync permissions (`permissions[]`) | `permission:permission.role.manage` |
| GET | `/matrix` | Ma trận role × permission | `permission:permission.matrix.view` |
| GET | `/users/{user}` | Role + effective permissions của user | — |
| POST | `/users/{user}/assign` | Gán role (`role`) | `permission:permission.role.manage` |
| DELETE | `/users/{user}/revoke/{role}` | Gỡ role | `permission:permission.role.manage` |
| GET | `/delegate` | DS delegation của tôi | — |
| POST | `/delegate` | Tạo delegation | `permission:permission.delegate` |
| DELETE | `/delegate/{id}` | Thu hồi delegation | `permission:permission.delegate` |

## POST `/delegate`
```json
{ "delegated_to": 9, "delegation_type": "approval", "permission": "approval.approve",
  "reason": "Vacation cover", "valid_from": "2026-06-01", "valid_until": "2026-06-10" }
```
`delegation_type` ∈ `approval, role, permission`. Tối đa 90 ngày.

> Effective permissions = trực tiếp ∪ qua role ∪ qua delegation đang hiệu lực. Danh sách
> permission/role chuẩn: [RoleSeeder](../../database/seeders/RoleSeeder.php),
> [PermissionSeeder](../../database/seeders/PermissionSeeder.php).
