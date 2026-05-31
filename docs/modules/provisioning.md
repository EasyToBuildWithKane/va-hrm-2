# Module: Provisioning

## Business Purpose
Tự động **cấp phát / thu hồi** tài khoản, email, license phần mềm theo vòng đời nhân sự
(onboarding/offboarding) và theo yêu cầu được duyệt.

## Actors
IT Support (`provisioning.account.manage`, `provisioning.license.manage`), HR (trigger),
`provisioning.offboarding.execute` cho offboarding.

## Features
- Engine xử lý 4 loại: onboarding, offboarding, access_change, license_assign.
- Onboarding: tạo email + account, ghi log, audit `ACTIVATED`, thông báo nhân viên.
- Offboarding: thu hồi toàn bộ access, audit `DEACTIVATED`.
- Account lifecycle: pending→active→suspended→disabled→revoked.
- License: kho seat, gán/thu hồi (kiểm `used_seats < total_seats`, unique/nhân viên).
- Dashboard thống kê + provisioning logs.

## Screens (PROPOSED)
Provisioning dashboard · Accounts · Licenses · Logs theo nhân viên.

## APIs → [docs/api/provisioning.md](../api/provisioning.md)
`/dashboard`, `/accounts` (+suspend/activate/revoke), `/licenses` (CRUD + assign/revoke),
`/onboarding/{employee}`, `/offboarding/{employee}`, `/logs/{employee}`.

## Database Tables → [table-dictionary](../database/table-dictionary.md#provisioning_requests)
`provisioning_requests`, `account_provisions`, `email_provisions`, `software_licenses`,
`employee_software_licenses`, `provisioning_logs`.

## Code chính
[ProvisioningEngine](../../modules/Provisioning/Engine/ProvisioningEngine.php) (+ EmailProvisioner,
AccountProvisioner, AccessRevoker, AssetProvisioner),
[ProvisioningService](../../modules/Provisioning/Services/ProvisioningService.php),
Actions: ProvisionAccount/Suspend/RevokeAccess/OffboardEmployeeAccess,
Listener: ExecuteProvisioningOnApproval.

## Business rules → [business-rules](../business/business-rules.md#provisioning)
R-PV-1..4. Config: [provisioning.php](../../config/provisioning.php) (email domain, offboarding policy).

## Dependencies
Depends: Employee, Audit, Notification, Approval (listener khi workflow approved). Kích hoạt bởi
event `EmployeeCreated`→TriggerProvisioningOnCreate, `EmployeeTerminated`→TriggerOffboarding,
`ApprovalWorkflowCompleted`→ExecuteProvisioningOnApproval.

## Trạng thái hiện thực
✅ Engine onboarding/offboarding + log + audit; license assign/revoke.
⚠️ access_change/license_assign chỉ ghi log (chưa thực thi thao tác hệ thống thật).
TODO: Need Human Validation — EmailProvisioner/AccountProvisioner/AccessRevoker chi tiết (tích hợp
hệ thống ngoài như Google Workspace?).

## Future Improvements
- Tích hợp IdP/Google Workspace thật; rollback khi fail; lịch suspend→disable email 30 ngày tự động.

## Liên kết chéo
Flow: [employee-onboarding](../flows/employee-onboarding.md), [offboarding-provisioning](../flows/offboarding-provisioning.md)
· API: [provisioning](../api/provisioning.md) · DB: [provisioning_requests](../database/table-dictionary.md#provisioning_requests).
