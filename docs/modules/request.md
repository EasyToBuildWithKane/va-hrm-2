# Module: Request

## Business Purpose
Engine **yêu cầu đa loại (polymorphic)**: một bảng base `workflow_requests` + bảng chuyên biệt cho
từng loại (thiết bị, hoàn ứng, cấp quyền phần mềm, tài khoản, điều chỉnh lương). Mọi loại đều đi qua
workflow phê duyệt.

## Actors
Employee/Manager (tạo), người duyệt theo workflow, Finance (request tài chính).

## Features
- Submit request với `request_type` (giới hạn bởi `config('workflow.allowed_workflow_types')`) +
  `payload` JSON; tự map sang bảng chuyên biệt.
- Khởi tạo `ApprovalEngine::initiate()`, gán `workflow_id`, status `in_progress`.
- List/filter, xem chi tiết (load mọi quan hệ), huỷ.

## Screens (PROPOSED)
Request list · Submit (form động theo loại) · Detail (+ tiến trình duyệt).

## APIs → [docs/api/requests.md](../api/requests.md)
`GET/POST /requests`, `GET/DELETE /requests/{ulid}`.

## Database Tables → [table-dictionary](../database/table-dictionary.md#workflow_requests)
`workflow_requests` + `equipment_requests`, `reimbursement_requests`, `software_access_requests`,
`account_requests`, `salary_adjustment_requests`.

## Code chính
[RequestController](../../modules/Request/Controllers/RequestController.php),
[RequestService](../../modules/Request/Services/RequestService.php) (submit + persistSpecific + cancel),
SubmitRequestAction, CancelRequestAction, SubmitRequestDTO, RequestRepository.

## Dependencies
Depends: Employee, **Approval** (ApprovalEngine). Sau khi workflow xong, listener
`UpdateRequestStatusListener` cập nhật status; `ExecuteProvisioningOnApproval` có thể kích hoạt
provisioning (vd account_request).

## Trạng thái hiện thực
✅ Submit đa loại + map payload + cancel.
TODO: Need Human Validation — quy tắc validate sâu cho từng `payload` (hiện dùng default an toàn khi
thiếu field).

## Future Improvements
- Schema-driven form (JSON schema) cho từng loại; đính kèm file; SLA hiển thị cho người gửi.

## Liên kết chéo
Flow: [request-approval-flow](../flows/request-approval-flow.md) · API: [requests](../api/requests.md)
· Approval: [approval](approval.md) · DB: [workflow_requests](../database/table-dictionary.md#workflow_requests).
