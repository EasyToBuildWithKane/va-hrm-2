# Business — Personas & Actors

> Vai trò lấy từ [config/permission_hrm.php](../../config/permission_hrm.php) và seed
> [RoleSeeder](../../database/seeders/RoleSeeder.php). Quyền cụ thể: [PermissionSeeder](../../database/seeders/PermissionSeeder.php).
> RBAC chi tiết: [docs/modules/permission.md](../modules/permission.md).

## Danh sách Actor

| Role (code) | Mô tả | Tác vụ chính |
|---|---|---|
| **Super Admin** | Toàn quyền hệ thống | Cấu hình, quản trị role/permission |
| **HR Director** | Giám đốc nhân sự | Duyệt cấp cao, đích **escalation** mặc định ([workflow.php](../../config/workflow.php)) |
| **HR Staff** | Nhân viên HR | Tạo/sửa hồ sơ, hợp đồng, kích hoạt onboarding/offboarding |
| **Department Manager** | Trưởng phòng | Duyệt nghỉ/đơn của nhân viên trong phòng, xem phân tích phòng ban |
| **Team Leader** | Trưởng nhóm | Duyệt cấp 1 (tuỳ workflow config) |
| **Employee** | Nhân viên | Self-service: chấm công, xin nghỉ, gửi request, xem điểm |
| **IT Support** | Hỗ trợ CNTT | Provisioning tài khoản/license |
| **Finance** | Tài chính | Request/duyệt hoàn ứng, điều chỉnh lương |
| **Auditor** | Kiểm toán | Chỉ đọc audit log (`audit.view`) |

## Scope phân quyền
Enum [PermissionScope](../../app/Enums/PermissionScope.php): `organization` › `department` › `team` › `own`.
Ví dụ kiểm tra scope trong code: `User::isManagerOf($departmentId)` so khớp `employee.department_id`
+ role `Department Manager`.

## Actor ↔ Module (ma trận thô)
| Module | Employee | Dept Manager | HR Staff/Director | IT/Finance | Auditor |
|---|:--:|:--:|:--:|:--:|:--:|
| Employee | xem mình | xem phòng | full | – | – |
| Leave | tạo/huỷ | duyệt | cấu hình policy | – | – |
| Request | tạo/huỷ | duyệt | – | duyệt (finance) | – |
| Approval | – | duyệt/uỷ quyền | cấu hình workflow | duyệt | – |
| Attendance | check-in/out | xem | – | – | – |
| Provisioning | – | – | trigger | quản lý account/license | – |
| Contribution | xem điểm | xem | điều chỉnh/quy tắc | – | – |
| Audit | – | – | – | – | xem/export |

> Ma trận là tóm tắt; phân quyền thực thi qua Policy + middleware `permission:*` trên route
> (xem [docs/api/](../api/README.md) cột Permission).
