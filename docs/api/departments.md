# API — Departments

Prefix `/api/v1/departments` · Controller:
[DepartmentController](../../modules/Department/Controllers/DepartmentController.php) ·
Module: [department.md](../modules/department.md) · Bảng: [departments](../database/table-dictionary.md#departments).

| Method | Path | Purpose | Policy |
|---|---|---|---|
| GET | `/` | List + filter (`search,is_active,parent_id,sort,direction,per_page`) | `viewAny` |
| POST | `/` | Tạo phòng ban (`StoreDepartmentRequest`) | `create` |
| GET | `/{department}` | Chi tiết (load manager, parent) | `view` |
| PUT | `/{department}` | Cập nhật (`UpdateDepartmentRequest`) | `update` |
| DELETE | `/{department}` | Xoá (soft) | `delete` |
| GET | `/{department}/employees` | Nhân viên thuộc phòng | — |
| GET | `/{department}/hierarchy` | Cây phòng ban con | — |
| GET | `/{department}/analytics` | `{total_headcount, hierarchy_depth, active_employees}` | — |
| GET | `/{department}/headcount` | `{department_id, headcount}` | — |

## POST `/` — tạo phòng ban
**Request** (suy từ schema + StoreDepartmentRequest):
```json
{ "name": "Engineering", "code": "ENG", "parent_id": null, "manager_id": null, "headcount_limit": 50 }
```
**Response 201**: `DepartmentResource`.

Related: [wireframe organization-chart](../wireframe/organization-chart.md) · [Organization API](organization.md).
