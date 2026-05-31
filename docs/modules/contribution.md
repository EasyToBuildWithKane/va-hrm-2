# Module: Contribution

## Business Purpose
Định lượng **đóng góp** của nhân viên qua hệ thống điểm (scoring) dựa trên sự kiện + quy tắc, và xếp
hạng (overall/department).

## Actors
Employee (xem điểm), Manager (xem ranking), HR (`contribution.rules.manage`,
`contribution.score.adjust`).

## Features
- Quy tắc tính điểm theo `event_type` (base_points × multiplier + conditions).
- Sự kiện ghi điểm (`contribution_events`) tham chiếu polymorphic nguồn.
- Điểm tổng hợp 1 dòng/nhân viên (total/monthly/quarterly + rank).
- Đề nghị điều chỉnh điểm (`pending`, cần duyệt).
- Dashboard + ranking (lọc theo phòng).

## Screens (PROPOSED)
Contribution dashboard · Ranking board · Employee history · Score adjustment.

## APIs → [docs/api/contribution.md](../api/contribution.md)
`/dashboard`, `/ranking`, `/employees/{employee}`, `/employees/{employee}/history`, `/adjustments`,
`/rules` (CRUD).

## Database Tables → [table-dictionary](../database/table-dictionary.md#contribution_scores)
`scoring_rules`, `contribution_events`, `contribution_scores`, `score_adjustment_requests`.

## Code chính
[ContributionController](../../modules/Contribution/Controllers/ContributionController.php),
[ContributionService](../../modules/Contribution/Services/ContributionService.php),
Engine: ContributionEngine, ScoreCalculator, ScoringRuleEvaluator, ContributionRepository.

## Business rules → [business-rules](../business/business-rules.md#contribution)
R-CT-1..3. Config trọng số/caps/decay: [contribution.php](../../config/contribution.php).

## Dependencies
Depends: Employee, Approval (duyệt adjustment). Cron `contribution:sync-scores` (hàng ngày) tính lại
điểm/hạng.

## Trạng thái hiện thực
✅ Dashboard, ranking, adjustment request, rules CRUD.
TODO: Need Human Validation — ContributionEngine/ScoreCalculator/ScoringRuleEvaluator (cách tính điểm
& recalculation thực tế); cron `SyncContributionScores` logic.

## Future Improvements
- Áp decay half-life 180 ngày; gắn tự động sự kiện từ module khác (task done, approval efficiency...);
  badge/level.

## Liên kết chéo
API: [contribution](../api/contribution.md) · DB: [contribution_scores](../database/table-dictionary.md#contribution_scores).
