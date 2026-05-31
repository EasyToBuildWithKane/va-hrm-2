# Module: Employee

## Business Purpose
Quản lý toàn bộ vòng đời nhân sự: hồ sơ, hợp đồng, tài liệu, timeline sự kiện, onboarding và
offboarding. Là entity trung tâm mà hầu hết module khác tham chiếu.

## Actors
HR Staff/Director (full), Department Manager (xem phòng mình), Employee (xem hồ sơ mình qua
`employee.view.own`). Phân quyền qua `EmployeePolicy`.

## Features
- CRUD hồ sơ + soft delete + restore.
- Tự sinh `employee_number` (`EMP-XXXXXXXX`), tự tạo `User` theo email nếu chưa có.
- Quản lý hợp đồng, tài liệu, liên hệ khẩn cấp.
- Timeline tự ghi: created, department_transfer, terminated, onboarded, offboarded.
- Onboarding/offboarding/terminate/transfer — bắn Domain Events kích hoạt provisioning & graph.
- Ẩn & redact dữ liệu nhạy cảm (`salary`, `bank_account_number`).

## Screens (PROPOSED — chưa có FE)
List · Detail (tabs: profile, contracts, documents, timeline) · Create · Edit. Xem
[wireframe employee-list](../wireframe/employee-list.md), [employee-detail](../wireframe/employee-detail.md).

## APIs → [docs/api/employees.md](../api/employees.md)
`GET/POST /employees`, `GET/PUT/DELETE /employees/{ulid}`, `/restore`, `/timeline`, `/contracts`,
`/documents`, `/onboard`, `/offboard`, `/terminate`, `/transfer`.

## Database Tables → [table-dictionary](../database/table-dictionary.md#employees)
`employees`, `employee_contracts`, `employee_documents`, `employee_timeline`,
`employee_emergency_contacts`. (Position thuộc module Department.)

## Code chính
- [EmployeeController](../../modules/Employee/Controllers/EmployeeController.php),
  [EmployeeService](../../modules/Employee/Services/EmployeeService.php)
- Actions: Create/Update/Terminate/Onboard/Offboard (Employee/Actions/)
- Events: EmployeeCreated/Updated/Terminated/Onboarded/Offboarded; Listeners:
  TriggerProvisioningOnCreate, TriggerOffboardingOnTermination, UpdateOrganizationGraphOnChange.

## Dependencies
Depends: Department (FK), Permission (policy), Audit (Auditable). Được dùng bởi: Organization,
Leave, Attendance, Request, Provisioning, Contribution. Bắn event tới Provisioning & Organization.

## Trạng thái hiện thực
✅ Controller/Service/Model/route/migration/timeline đầy đủ.
⚠️ Một số Action (Onboard/Offboard) gọi service + event; chi tiết provisioning xem module Provisioning.
TODO: Need Human Validation — nội dung field của `CreateEmployeeRequest`/`UpdateEmployeeRequest`,
`EmployeeContractController`/`EmployeeDocumentController` (chưa đọc trong tài liệu này).

## Future Improvements
- Bulk import nhân sự; versioning hợp đồng; export hồ sơ; gắn `position_id` vào organization graph.

## Liên kết chéo
Flow: [employee-onboarding](../flows/employee-onboarding.md), [offboarding-provisioning](../flows/offboarding-provisioning.md)
· API: [employees](../api/employees.md) · DB: [employees](../database/table-dictionary.md#employees).
