# API — Provisioning

Prefix `/api/v1/provisioning` · Controllers:
[ProvisioningController](../../modules/Provisioning/Controllers/ProvisioningController.php),
[SoftwareLicenseController](../../modules/Provisioning/Controllers/SoftwareLicenseController.php) ·
Module: [provisioning.md](../modules/provisioning.md) ·
Bảng: [provisioning_requests](../database/table-dictionary.md#provisioning_requests),
[account_provisions](../database/table-dictionary.md#account_provisions).

| Method | Path | Purpose | Permission |
|---|---|---|---|
| GET | `/dashboard` | Thống kê account/pending | — |
| GET | `/accounts` | List account (filter `employee_id,account_type,status`) | — |
| POST | `/accounts` | Khởi tạo provisioning cho nhân viên (`employee_id`) | `permission:provisioning.account.manage` |
| GET | `/accounts/{account}` | Chi tiết account | — |
| PATCH | `/accounts/{account}/suspend` | Tạm ngưng | `permission:provisioning.account.manage` |
| PATCH | `/accounts/{account}/activate` | Kích hoạt lại | `permission:provisioning.account.manage` |
| PATCH | `/accounts/{account}/revoke` | Thu hồi | `permission:provisioning.account.manage` |
| GET | `/licenses` | DS license | `permission:provisioning.license.manage` |
| POST | `/licenses` | Tạo license (`name,total_seats,...`) | `permission:provisioning.license.manage` |
| PUT | `/licenses/{license}` | Sửa license | `permission:provisioning.license.manage` |
| POST | `/licenses/{license}/assign` | Gán license (`employee_id`) | `permission:provisioning.license.manage` |
| DELETE | `/licenses/{license}/revoke/{employee}` | Thu hồi license của nhân viên | `permission:provisioning.license.manage` |
| POST | `/onboarding/{employee}` | Trigger onboarding | `permission:provisioning.account.manage` |
| POST | `/offboarding/{employee}` | Trigger offboarding (`reason?`) | `permission:provisioning.offboarding.execute` |
| GET | `/logs/{employee}` | Nhật ký provisioning của nhân viên | — |

## POST `/onboarding/{employee}`
Khởi tạo `ProvisioningRequest(type=onboarding)` → [ProvisioningEngine](../../modules/Provisioning/Engine/ProvisioningEngine.php)
tạo email + account, ghi log, audit `ACTIVATED`, gửi `provisioning.completed`.

License: gán fail nếu `used_seats >= total_seats`; unique 1 nhân viên/1 license.

Related: [flow offboarding-provisioning](../flows/offboarding-provisioning.md) · [flow employee-onboarding](../flows/employee-onboarding.md).
