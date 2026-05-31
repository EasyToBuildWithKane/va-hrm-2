# Module: Organization

## Business Purpose
Mô hình hoá cơ cấu tổ chức dưới dạng **đồ thị có hướng + trọng số** (linh hoạt hơn cây phòng ban):
node = employee/department/role/project/approval_authority; edge = REPORT_TO/MANAGE/BELONG_TO/...

## Actors
Mọi user xem graph; chỉnh quan hệ & sync cần quyền (`permission.role.manage` cho sync).

## Features
- Build graph (toàn bộ / theo phòng ban) → `{nodes, edges, meta}` cho client vẽ.
- Subtree theo BFS với `depth` giới hạn ([OrganizationGraphService::traverse](../../modules/Organization/Services/OrganizationGraphService.php)).
- Reporting chain (chuỗi quản lý) theo `manager_id`.
- CRUD quan hệ (relationships) thủ công.
- **Sync** tự động từ employees/departments (cron `organization:sync-graph` mỗi 15').

## Screens (PROPOSED)
Org chart canvas (zoom/pan), subtree explorer. [wireframe organization-chart](../wireframe/organization-chart.md).

## APIs → [docs/api/organization.md](../api/organization.md)
`/graph`, `/graph/{nodeId}/subtree`, `/employees/{employee}/reporting-chain`,
`/departments/{department}/hierarchy`, CRUD `/relationships`, `/sync`.

## Database Tables → [table-dictionary](../database/table-dictionary.md#organization_nodes)
`organization_nodes`, `organization_relationships`.

## Code chính
[OrganizationGraphController](../../modules/Organization/Controllers/OrganizationGraphController.php),
[OrganizationGraphService](../../modules/Organization/Services/OrganizationGraphService.php),
Graph/: OrganizationGraphBuilder, GraphNodeFactory, GraphRelationshipResolver.

## Dependencies
Depends: Employee, Department. Cập nhật bởi event `EmployeeCreated/Updated/Terminated`
(UpdateOrganizationGraphOnChange).

## Trạng thái hiện thực
✅ Service sync + subtree + reporting-chain + CRUD relationship.
TODO: Need Human Validation — chi tiết `OrganizationGraphBuilder::build()`, `GraphNodeFactory`,
`GraphRelationshipResolver` (định dạng nodes/edges trả về cho FE).

## Future Improvements
- Phát hiện chu trình REPORT_TO; layout server-side; quan hệ matrix (dotted-line) báo cáo kép.

## Liên kết chéo
API: [organization](../api/organization.md) · Employee: [employee](employee.md) ·
DB: [organization_nodes](../database/table-dictionary.md#organization_nodes).
