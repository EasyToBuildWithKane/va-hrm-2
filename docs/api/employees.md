# API — Employees

Prefix `/api/v1/employees` · Auth: `auth:sanctum` · Controller:
[EmployeeController](../../modules/Employee/Controllers/EmployeeController.php) (+ Contract/Document/
Timeline controllers). Module: [employee.md](../modules/employee.md). Bảng: [employees](../database/table-dictionary.md#employees).

Authorize qua `EmployeePolicy` (viewAny/view/create/update/delete/restore/onboard/terminate).

| Method | Path | Purpose | Permission/Policy |
|---|---|---|---|
| GET | `/` | List + filter (`search,status,department_id,employment_type,from,to,sort,direction,per_page`) | `viewAny` |
| POST | `/` | Tạo employee | `create` |
| GET | `/{employee}` | Chi tiết (load department/position/manager) | `view` |
| PUT | `/{employee}` | Cập nhật | `update` |
| DELETE | `/{employee}` | Archive (soft delete) | `delete` |
| POST | `/{ulid}/restore` | Khôi phục | `restore` |
| GET | `/{employee}/timeline` | Timeline sự kiện (≤100) | `view` |
| GET | `/{employee}/contracts` | DS hợp đồng | — |
| POST | `/{employee}/contracts` | Thêm hợp đồng | — |
| GET | `/{employee}/documents` | DS tài liệu | — |
| POST | `/{employee}/documents` | Thêm tài liệu | — |
| POST | `/{employee}/onboard` | Hoàn tất onboarding | `onboard` |
| POST | `/{employee}/offboard` | Offboard | `terminate` |
| POST | `/{employee}/terminate` | Chấm dứt (body: `reason*`, `effective_date?`) | `terminate` |
| POST | `/{employee}/transfer` | Chuyển phòng (body: `department_id*`) | `update` |

## POST `/` — tạo employee
**Request** (validate bởi `CreateEmployeeRequest`; các trường suy từ schema):
```json
{
  "first_name": "John", "last_name": "Doe", "email": "john@company.com",
  "department_id": 1, "position_id": 2, "employment_type": "full_time",
  "join_date": "2026-01-15", "phone": "0900000000"
}
```
**Response 201**
```json
{ "success": true, "message": "Employee created successfully",
  "data": { "ulid": "01J...", "employee_number": "EMP-XXXXXXXX", "first_name": "John", ... } }
```
> `salary`, `bank_account_number` bị ẩn (hidden) trong response. TODO: Need Human Validation — danh
> sách field validate chi tiết nằm trong `CreateEmployeeRequest`/`UpdateEmployeeRequest`.

## GET `/` — list
Trả mảng `EmployeeListResource` + `meta` phân trang. Filter nhận qua query string.

Related: [flow onboarding](../flows/employee-onboarding.md) · [flow offboarding](../flows/offboarding-provisioning.md)
· [wireframe employee-list](../wireframe/employee-list.md) · [wireframe employee-detail](../wireframe/employee-detail.md).
