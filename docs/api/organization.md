# API — Organization (Graph)

Prefix `/api/v1/organization` · Controller:
[OrganizationGraphController](../../modules/Organization/Controllers/OrganizationGraphController.php) ·
Module: [organization.md](../modules/organization.md) ·
Bảng: [organization_nodes](../database/table-dictionary.md#organization_nodes),
[organization_relationships](../database/table-dictionary.md#organization_relationships).

| Method | Path | Purpose | Permission |
|---|---|---|---|
| GET | `/graph` | Toàn đồ thị (query `department_id?`) → `{nodes, edges, meta}` | — |
| GET | `/graph/{nodeId}/subtree` | Subtree từ node (query `depth=3`) BFS | — |
| GET | `/employees/{employee}/reporting-chain` | Chuỗi quản lý lên trên | — |
| GET | `/departments/{department}/hierarchy` | `{department, parents[], children[]}` | — |
| POST | `/relationships` | Tạo cạnh (`from_node_id,to_node_id,relationship_type,weight?`) | — |
| PUT | `/relationships/{relationship}` | Sửa cạnh (`weight,is_active,valid_from,valid_until`) | — |
| DELETE | `/relationships/{relationship}` | Xoá cạnh | — |
| POST | `/sync` | Resync graph từ DB | `permission:permission.role.manage` |

## POST `/relationships`
**Request**
```json
{ "from_node_id": 10, "to_node_id": 3, "relationship_type": "REPORT_TO", "weight": 1.0 }
```
`relationship_type` ∈ `REPORT_TO, MANAGE, BELONG_TO, APPROVE_FOR, WORK_WITH, MEMBER_OF`.

## GET `/graph`
Trả node + edge để client vẽ sơ đồ tổ chức. `nodes` build qua `GraphNodeFactory`, `edges` qua
`GraphRelationshipResolver`. Sync tự động mỗi 15 phút (cron `organization:sync-graph`).

Related: [wireframe organization-chart](../wireframe/organization-chart.md).
