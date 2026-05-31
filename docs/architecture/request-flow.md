# Architecture — Request Flow

> Vòng đời một HTTP request và một luồng phê duyệt. Đối chiếu code thật.

## 1. Vòng đời HTTP request (đọc danh sách Employee)

```mermaid
sequenceDiagram
    participant C as Client
    participant RL as RateLimiter(api)
    participant MW as Middleware (auth:sanctum)
    participant Ctrl as EmployeeController
    participant Pol as EmployeePolicy
    participant Repo as EmployeeRepository
    participant DB as MySQL

    C->>RL: GET /api/v1/employees (Bearer token)
    RL->>MW: trong giới hạn 200/phút
    MW->>MW: xác thực token Sanctum
    MW->>Ctrl: index(Request)
    Ctrl->>Pol: authorize('viewAny', Employee)
    Pol-->>Ctrl: ok
    Ctrl->>Repo: paginate(filters, perPage)
    Repo->>DB: SELECT ... paginate
    DB-->>Repo: rows
    Repo-->>Ctrl: LengthAwarePaginator
    Ctrl-->>C: ApiResponse::success(data, meta)
```

Envelope trả về (từ [ApiResponse](../../app/Support/ApiResponse.php)):
```json
{ "success": true, "data": [ ... ], "meta": { "current_page":1, "per_page":15, "total":120, "last_page":8 } }
```

## 2. Luồng tạo + phê duyệt (submit đơn nghỉ)

```mermaid
sequenceDiagram
    participant E as Employee
    participant LC as LeaveController
    participant LS as LeaveService
    participant AE as ApprovalEngine
    participant DB as MySQL
    participant N as NotificationService
    participant M as Manager (approver)

    E->>LC: POST /api/v1/leave/requests
    LC->>LS: submit(employee, data, user)
    LS->>DB: INSERT leave_requests (status=pending)
    LS->>AE: initiate(request, 'leave_request', user)
    AE->>DB: INSERT approval_workflows + approval_steps
    AE->>N: notify(approver, 'approval.requested')
    N-->>M: in-app + email
    LS-->>E: 201 leave request

    M->>LC: POST /api/v1/approvals/workflows/{wf}/approve
    Note over AE: approve last step → workflow approved<br/>bắn ApprovalWorkflowCompleted
    AE->>N: notify requestor
```

## 3. Cross-module events (sau khi workflow approved)
`ApprovalWorkflowCompleted` → các listener (qua queue):
`ExecuteProvisioningOnApproval`, `UpdateRequestStatusListener`, `NotifyRequestorListener`
(xem [EventServiceProvider](../../app/Providers/EventServiceProvider.php)).

Chi tiết workflow engine: [workflow-engine.md](workflow-engine.md). Flow nghiệp vụ đầy đủ:
[docs/flows/](../flows/leave-approval-flow.md).
