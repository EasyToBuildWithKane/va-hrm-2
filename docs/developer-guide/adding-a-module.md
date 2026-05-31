# Developer Guide — Thêm Module / Feature mới

> Theo đúng pattern của 12 module hiện có. Tham chiếu mẫu: module Employee, Leave.

## Tạo Module mới
1. **Migration** — tạo bảng trong `database/migrations/` (id, ulid, timestamps, softDeletes,
   created_by/updated_by, FK rõ action, index). Đối chiếu thiết kế tại
   [data-structures/](../../data-structures/) nếu có.
2. **Model** — `modules/<Module>/Models/<Entity>.php` extends `BaseModel`; khai `fillable`, `casts`,
   relationships; `implements Auditable` + `use HasAuditLog` nếu cần audit; `use HasUlid` cho public id.
3. **Repository** (nếu cần truy vấn phức tạp) — interface trong `Repositories/Contracts/`, impl trong
   `Repositories/`; bind ở [RepositoryServiceProvider](../../app/Providers/RepositoryServiceProvider.php).
4. **Service** — business logic, `DB::transaction` cho thao tác nhiều bước.
5. **Action** (tuỳ chọn) — `final` + `__invoke()` cho thao tác đơn được controller gọi.
6. **Controller** — mỏng: validate/authorize → gọi Service/Action → `ApiResponse`.
7. **Routes** — `modules/<Module>/routes/api.php` (path tương đối).
8. **ServiceProvider** — `modules/<Module>/<Module>ServiceProvider.php`:
   ```php
   Route::middleware(['api','auth:sanctum'])
       ->prefix('api/v1/<module>')
       ->group(__DIR__.'/routes/api.php');
   ```
9. **Đăng ký** — thêm tên module vào [config/modules.php](../../config/modules.php) `enabled` (đặt
   sau các dependency của nó).
10. **Policy / Permission** — thêm permission vào `PermissionSeeder`, gán role trong `RoleSeeder`,
    bảo vệ route bằng `permission:<name>` hoặc `authorize()` trong controller.
11. **Events/Listeners** (nếu giao tiếp chéo module) — khai báo trong
    [EventServiceProvider](../../app/Providers/EventServiceProvider.php).
12. **Docs** — thêm `docs/modules/<module>.md`, `docs/api/<module>.md`, cập nhật
    [docs/README.md](../README.md) và [docs/AI_CONTEXT.md](../AI_CONTEXT.md).

## Thêm loại Request mới
1. Thêm `request_type` vào `config('workflow.allowed_workflow_types')`.
2. (Nếu có dữ liệu riêng) tạo bảng chuyên biệt + nhánh trong
   [RequestService::persistSpecific()](../../modules/Request/Services/RequestService.php).
3. Tạo `workflow_configurations` (qua API `/approvals/configurations`) cho `request_type` đó.

## Thêm Workflow type mới
Chỉ cần 1 bản ghi `workflow_configurations` active với `config.steps[]`. Không cần code nếu dùng
chuỗi step chuẩn.
