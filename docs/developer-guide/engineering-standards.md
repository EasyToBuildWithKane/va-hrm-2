# Developer Guide — Engineering Standards

> Convention thực tế quan sát từ codebase. Bổ sung naming:
> [naming-conventions.md](naming-conventions.md).

## PHP / Laravel
- `declare(strict_types=1);` ở **mọi** file PHP.
- Typed everything: tham số, return type, property. DI qua constructor `private readonly`.
- Action class `final`, single-responsibility, dùng `__invoke()`.
- Enum backed (string) cho mọi tập trạng thái (xem `app/Enums/`).

## Layering (bắt buộc)
- **Controller mỏng**: chỉ validate (`$request->validate` hoặc FormRequest), `authorize()`, gọi
  Action/Service, trả `ApiResponse`. Không truy vấn Eloquent nghiệp vụ trong controller.
- **Service**: chứa business rule, bọc `DB::transaction` khi nhiều bước.
- **Repository**: truy cập dữ liệu (bind interface→impl qua `RepositoryServiceProvider`).
- **Cross-module**: ưu tiên Domain Events (ngoại lệ có chủ đích: ApprovalEngine, Audit/Notification
  service được gọi trực tiếp).

## API
- Luôn trả envelope qua [ApiResponse](../../app/Support/ApiResponse.php) (`success/data/message/meta`).
- List endpoint luôn phân trang + `meta`.
- HTTP status đúng ngữ nghĩa; lỗi nghiệp vụ ném `WorkflowException` (mang `errorCode` + `httpStatus`).
- Route key = ULID, không lộ auto-increment id.

## Database
- `id` + `ulid` (entity public) + timestamps; `deleted_at` cho entity chính; `created_by/updated_by`
  cho entity cần truy vết.
- FK khai báo action rõ ràng (`constrained()`, `cascadeOnDelete`, `nullOnDelete`).
- Index cho cột lọc/sort thường dùng; JSON cho metadata linh hoạt.

## Audit & Security
- Model cần audit: `implements Auditable` + `use HasAuditLog`; khai báo `auditableFields` &
  `sensitiveFields` (field nhạy cảm bị `[REDACTED]`).
- Dữ liệu nhạy cảm (`salary`, `bank_account_number`) đặt trong `$hidden`.

## Testing
- Feature test cho luồng API (RefreshDatabase, actingAs với role), Unit cho logic thuần (engine,
  diff, scoring). Xem [testing.md](testing.md).

## Forbidden (theo chuẩn dự án)
❌ Eloquent nghiệp vụ trong Controller · ❌ business logic trong Model · ❌ `DB::` raw ngoài Repository
(trừ trường hợp pivot có chủ đích) · ❌ `$request->all()` không map · ❌ inject service chéo module tuỳ
tiện · ❌ magic string cho event/permission (dùng Enum/const).
