# Module: Department

## Business Purpose
Quản lý phòng ban dạng **cây** (self-parent) và chức danh (`positions`). Cung cấp phân tích headcount
và cây phân cấp.

## Actors
HR (manage), Department Manager (`department.view`, `department.manage`), mọi user xem cơ bản. Policy
`DepartmentPolicy`.

## Features
- CRUD phòng ban + soft delete, cây cha-con (`parent_id`).
- Gán trưởng phòng (`manager_id` → employees).
- `headcount_limit`, đếm headcount, phân tích (`analytics`, `hierarchy`).
- Position (chức danh) gắn tuỳ chọn vào phòng ban.

## Screens (PROPOSED)
List · Detail · Hierarchy tree · Analytics. Xem [wireframe organization-chart](../wireframe/organization-chart.md).

## APIs → [docs/api/departments.md](../api/departments.md)
`GET/POST /departments`, `GET/PUT/DELETE /departments/{ulid}`, `/employees`, `/hierarchy`,
`/analytics`, `/headcount`.

## Database Tables → [table-dictionary](../database/table-dictionary.md#departments)
`departments`, `positions`.

## Code chính
[DepartmentController](../../modules/Department/Controllers/DepartmentController.php),
[DepartmentService](../../modules/Department/Services/DepartmentService.php), DepartmentRepository,
StoreDepartmentRequest/UpdateDepartmentRequest, DepartmentResource, DepartmentPolicy.

## Dependencies
Depends: Permission, Audit. Được dùng bởi: Employee (FK department_id/position_id), Organization
(node department), Leave (policy theo phòng).

## Trạng thái hiện thực
✅ Đầy đủ CRUD + analytics + hierarchy + factory. Có Request/Resource/Policy (module hoàn chỉnh nhất
về tầng HTTP).

## Future Improvements
- Cost center, ngân sách phòng; soft cap cảnh báo khi vượt `headcount_limit`; lịch sử thay đổi cơ cấu.

## Liên kết chéo
API: [departments](../api/departments.md) · Organization: [organization](organization.md) ·
DB: [departments](../database/table-dictionary.md#departments).
