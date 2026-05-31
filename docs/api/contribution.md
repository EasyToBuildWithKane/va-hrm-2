# API — Contribution

Prefix `/api/v1/contribution` · Controller:
[ContributionController](../../modules/Contribution/Controllers/ContributionController.php) ·
Module: [contribution.md](../modules/contribution.md) ·
Bảng: [contribution_scores](../database/table-dictionary.md#contribution_scores),
[scoring_rules](../database/table-dictionary.md#scoring_rules).

| Method | Path | Purpose | Permission |
|---|---|---|---|
| GET | `/dashboard` | `{total_employees_scored, avg_total_points, top_score}` | — |
| GET | `/ranking` | Xếp hạng (query `department_id?,per_page`) | — |
| GET | `/employees/{employee}` | Điểm của 1 nhân viên | — |
| GET | `/employees/{employee}/history` | Lịch sử sự kiện điểm | — |
| POST | `/adjustments` | Đề nghị điều chỉnh điểm (`employee_id,adjustment_points,reason`) | `permission:contribution.score.adjust` |
| GET | `/rules` | DS quy tắc tính điểm | `permission:contribution.rules.manage` |
| POST | `/rules` | Tạo quy tắc | `permission:contribution.rules.manage` |
| PUT | `/rules/{rule}` | Sửa quy tắc | `permission:contribution.rules.manage` |

## POST `/adjustments`
```json
{ "employee_id": 12, "adjustment_points": 5.0, "reason": "Extra project" }
```
→ tạo `score_adjustment_request` trạng thái `pending` (cần duyệt).

## POST `/rules`
```json
{ "name": "Task done", "event_type": "task_completed", "base_points": 1.0,
  "multiplier": 1.0, "conditions": {}, "is_active": true }
```
Trọng số/caps/decay tham khảo [config/contribution.php](../../config/contribution.php).
