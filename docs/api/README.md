# API Documentation

> REST API `/api/v1`. Suy ra từ [routes/api.php](../../routes/api.php) + `modules/*/routes/api.php`
> + Controllers. Mỗi nhóm route có 1 file riêng (link bên dưới).

## Quy ước chung
- **Base URL:** `/api/v1` (prefix do [RouteServiceProvider](../../app/Providers/RouteServiceProvider.php)
  và từng module ServiceProvider đặt).
- **Auth:** Bearer token (Sanctum). Mọi route (trừ `POST /auth/login`) yêu cầu header
  `Authorization: Bearer <token>`.
- **Middleware group `api`:** `ForceJsonResponse` → `throttle:api` → `SubstituteBindings` →
  `AuditRequest`. ([app/Http/Kernel.php](../../app/Http/Kernel.php))
- **Rate limit:** 200 req/phút (đã auth) · 60 (ẩn danh).
- **Route key:** entity dùng **ULID** (`{employee}`, `{leaveRequest}`… resolve theo `ulid`).
- **Permission:** một số route gắn `permission:<name>` (cột Permission trong từng file). Route không
  ghi permission chỉ cần `auth:sanctum` (+ Policy authorize bên trong controller).

## Envelope phản hồi ([ApiResponse](../../app/Support/ApiResponse.php))
```json
// Success (list)
{ "success": true, "data": [ ... ], "meta": { "current_page": 1, "per_page": 15, "total": 42 } }
// Success (single)
{ "success": true, "data": { ... }, "message": "Employee created successfully" }
// Message-only
{ "success": true, "message": "Department deleted" }
// Error
{ "success": false, "message": "Approval step not authorized", "code": "WORKFLOW_PERMISSION_DENIED", "workflow_id": 12 }
```

## Auth endpoints ([routes/api.php](../../routes/api.php))
| Method | Path | Auth | Mô tả |
|---|---|---|---|
| POST | `/api/v1/auth/login` | – | Đăng nhập, trả `token` + user. Sai → 422 |
| POST | `/api/v1/auth/logout` | ✅ | Xoá token hiện tại |
| POST | `/api/v1/auth/refresh` | ✅ | Cấp token mới |
| GET | `/api/v1/auth/me` | ✅ | Thông tin user + roles + permissions |
| GET | `/api/v1/health` | ✅ | Health check |

## Nhóm endpoint theo module
| Nhóm | Prefix | File |
|---|---|---|
| Employees | `/api/v1/employees` | [employees.md](employees.md) |
| Departments | `/api/v1/departments` | [departments.md](departments.md) |
| Organization | `/api/v1/organization` | [organization.md](organization.md) |
| Leave | `/api/v1/leave` | [leave.md](leave.md) |
| Requests | `/api/v1/requests` | [requests.md](requests.md) |
| Approvals | `/api/v1/approvals` | [approvals.md](approvals.md) |
| Attendance & Shifts | `/api/v1/attendance`, `/api/v1/shifts` | [attendance.md](attendance.md) |
| Contribution | `/api/v1/contribution` | [contribution.md](contribution.md) |
| Notifications | `/api/v1/notifications` | [notifications.md](notifications.md) |
| Provisioning | `/api/v1/provisioning` | [provisioning.md](provisioning.md) |
| Permissions | `/api/v1/permissions` | [permissions.md](permissions.md) |
| Audit | `/api/v1/audit` | [audit.md](audit.md) |

## <a name="error-codes"></a>Mã lỗi nghiệp vụ (WorkflowException & cộng sự)
| Code | HTTP | Ý nghĩa |
|---|---|---|
| `WORKFLOW_CONFIG_MISSING` | 422 | Không có cấu hình workflow active cho type |
| `WORKFLOW_EMPTY_CHAIN` | 422 | Không sinh được bước duyệt |
| `WORKFLOW_PERMISSION_DENIED` | 403 | Không phải approver của bước |
| `WORKFLOW_INVALID_TRANSITION` | 409 | Chuyển trạng thái không hợp lệ |
| `LEAVE_DATE_INVALID` | 422 | `end_date` < `start_date` |
| `ATTENDANCE_ALREADY_CHECKED_IN` | 409 | Đã check-in hôm nay |
| `ATTENDANCE_NO_CHECKIN` | 422 | Check-out khi chưa check-in |

Validation lỗi → 422 (Laravel default `{message, errors}`). Auth thiếu → 401. Policy chặn → 403.
