# Developer Guide — Naming Conventions

> Quan sát từ codebase thực tế (`modules/`, `app/`).

## PHP / Class
```txt
Controller   <Entity>Controller          EmployeeController, ApprovalController
Service      <Entity>Service             LeaveService, PermissionService
Engine       <Domain>Engine             ApprovalEngine, ProvisioningEngine, ContributionEngine
Action       <Verb><Entity>Action       CheckInAction, ApproveStepAction, ProvisionAccountAction
Repository   <Entity>Repository (+Interface)  EmployeeRepository / EmployeeRepositoryInterface
Model        <Entity> (số ít)           Employee, ApprovalWorkflow, LeaveRequest
DTO          <Verb><Entity>DTO          CreateEmployeeDTO, SubmitRequestDTO
Event        quá khứ                    EmployeeCreated, ApprovalWorkflowCompleted
Listener     ý định hiện tại            TriggerProvisioningOnCreate, NotifyRequestorListener
Policy       <Entity>Policy             EmployeePolicy
Resource     <Entity>Resource          EmployeeResource, DepartmentResource
Request      <Verb><Entity>Request      CreateEmployeeRequest, StoreDepartmentRequest
Enum         <Khái niệm> (PascalCase)   WorkflowStatus, EmploymentStatus, AuditEvent
ServiceProvider  <Module>ServiceProvider EmployeeServiceProvider
```

## Method
camelCase, động từ: `submit()`, `approve()`, `transferDepartment()`, `effectivePermissions()`.
Action dùng `__invoke()`.

## Database
- Bảng: snake_case số nhiều — `employees`, `approval_workflows`, `leave_requests`.
- Cột: snake_case — `department_id`, `created_at`, `sla_deadline_at`.
- Khoá ngoài: `<entity>_id` — `employee_id`, `workflow_id`.
- Unique index đặt tên rõ — `uq_emp_type_year`, `uq_org_relationship`.
- Pivot: `<a>_<b>` số nhiều — `employee_software_licenses`, `role_has_permissions`.

## Route / API
- Prefix module: `/api/v1/<module-số-nhiều>` (vd `/employees`, `/approvals`, `/leave`).
- Sub-resource: `/{ulid}/<action|sub>` (vd `/{employee}/timeline`, `/workflows/{wf}/approve`).
- Permission name: `<module>.<resource>.<action>` (vd `leave.policy.manage`, `approval.workflow.configure`).

## Config / Command
- Config key: snake_case (vd `default_sla_hours`).
- Artisan command: `domain:kebab-action` (vd `approvals:escalate-overdue`, `contribution:sync-scores`).

## Enum value
snake_case string: `in_progress`, `on_leave`, `full_time`, `REPORT_TO` (org relationship dùng
UPPER_SNAKE theo quy ước đồ thị).
