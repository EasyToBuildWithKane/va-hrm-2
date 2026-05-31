# Business — Product Vision

> Bối cảnh nghiệp vụ của VA-HRM. Xem thêm [project-overview.md](../project-overview.md),
> [personas-actors.md](personas-actors.md), [business-rules.md](business-rules.md).

## Vấn đề
Quản trị nhân sự trong một tổ chức nhiều phòng ban thường phân mảnh: hồ sơ rời rạc, phê duyệt
qua email/giấy, không truy vết được ai đổi gì, onboarding/offboarding thủ công dễ sót quyền truy cập.

## Giải pháp
Một nền tảng **API-first** hợp nhất:
1. **Employee lifecycle** — hồ sơ, hợp đồng, tài liệu, timeline, onboarding → offboarding.
2. **Organization graph** — mô hình quan hệ tổ chức linh hoạt hơn cây phòng ban (REPORT_TO, MANAGE…).
3. **Unified approval engine** — mọi yêu cầu (nghỉ phép, thiết bị, hoàn ứng, cấp quyền, điều chỉnh
   lương…) đi qua **một** workflow engine cấu hình được, có SLA / delegation / escalation.
4. **Provisioning tự động** — cấp/thu hồi tài khoản, email, license theo vòng đời nhân sự.
5. **Audit bất biến** — mọi thay đổi nhạy cảm được ghi log, có diff, có redaction dữ liệu lương.
6. **Contribution scoring** — định lượng đóng góp & xếp hạng.
7. **RBAC + delegation** — phân quyền theo vai trò × scope, uỷ quyền tạm thời.

## Giá trị cốt lõi
| Nguyên tắc | Hiện thực trong code |
|---|---|
| Audit-by-default | trait [HasAuditLog](../../app/Concerns/HasAuditLog.php) + `Auditable` |
| Workflow-centric | mọi request → [ApprovalEngine](../../modules/Approval/Engine/ApprovalEngine.php) |
| Event-driven cross-module | [EventServiceProvider](../../app/Providers/EventServiceProvider.php) |
| API envelope nhất quán | [ApiResponse](../../app/Support/ApiResponse.php) |
| Public id an toàn | ULID làm route key, ẩn auto-increment |
| Bảo mật dữ liệu nhạy cảm | `salary`,`bank_account_number` hidden + redacted |

## Phạm vi hiện tại vs dự kiến
- **Đã hiện thực** (backend): toàn bộ 12 module với Controller/Service/Model/route + schema + RBAC.
- **Một phần / dự kiến** (đánh dấu trong từng module): một số Engine (escalation cron, scoring
  recalculation), channel notification (Slack), và **toàn bộ tầng UI** (chưa có frontend — xem
  [docs/uiux/](../uiux/design-system.md), [docs/wireframe/](../wireframe/) ở dạng **PROPOSED**).
