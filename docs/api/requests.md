# API — Requests (đa loại)

Prefix `/api/v1/requests` · Controller: [RequestController](../../modules/Request/Controllers/RequestController.php) ·
Module: [request.md](../modules/request.md) · Bảng: [workflow_requests](../database/table-dictionary.md#workflow_requests).

| Method | Path | Purpose |
|---|---|---|
| GET | `/` | List (filter `request_type,status,employee_id,from,to`) |
| POST | `/` | Gửi yêu cầu (đa loại) → khởi tạo workflow |
| GET | `/{request}` | Chi tiết + load mọi quan hệ chuyên biệt |
| DELETE | `/{request}` | Huỷ yêu cầu |

## POST `/` — gửi request
**Request**
```json
{
  "request_type": "equipment_request",
  "employee_id": 12,
  "payload": { "equipment_type": "laptop", "model": "MBP 14", "quantity": 1, "estimated_cost": 2000 },
  "justification": "New hire setup"
}
```
- `request_type` phải thuộc `config('workflow.allowed_workflow_types')` (12 loại).
- `payload` được map sang bảng chuyên biệt tuỳ loại (equipment/reimbursement/software_access/account/
  salary_adjustment) bởi [RequestService::persistSpecific()](../../modules/Request/Services/RequestService.php).
- Tạo `workflow_requests` (pending) → `ApprovalEngine::initiate()` → set `workflow_id`, status
  `in_progress`.

**Response 201**: object `WorkflowRequest`.

Các loại `payload` hỗ trợ:
| request_type | payload chính |
|---|---|
| equipment_request | equipment_type, model, quantity, estimated_cost |
| reimbursement_request | amount, currency, category, expense_date, receipts |
| software_access_request | software_name, access_level, needed_by |
| account_request | account_type, access_scopes |
| salary_adjustment_proposal | target_employee_id, current_salary, proposed_salary, effective_date |

Related: [flow request-approval](../flows/request-approval-flow.md) · [Approvals API](approvals.md).
