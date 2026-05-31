# Developer Guide — Getting Started

> Cài đặt & chạy VA-HRM (Laravel 10 API). Nguồn: [composer.json](../../composer.json),
> [.env](../../.env), [README.md](../../README.md).

## Yêu cầu
- PHP ^8.1, Composer
- MySQL 8 (mặc định DB `laravel`, user `root`, không mật khẩu — xem `.env`)
- Redis (cho queue/cache/session ở staging/prod; dev đang dùng sync/file)
- (tuỳ chọn) Node + npm cho Vite assets

## Cài đặt
```bash
composer install
cp .env.example .env        # nếu chưa có .env
php artisan key:generate
# cấu hình DB_* trong .env
php artisan migrate --seed  # tạo schema + seed role/permission/department/leave_type/workflow/admin
php artisan serve           # http://localhost:8000
```

## Seeders chạy bởi `--seed` (DatabaseSeeder)
PermissionSeeder, RoleSeeder, DepartmentSeeder, LeaveTypeSeeder, ScoringRuleSeeder,
WorkflowConfigurationSeeder, AdminUserSeeder. → tạo sẵn roles/permissions và 1 admin để login.

## Đăng nhập & gọi API
```bash
# 1) login lấy token
curl -X POST http://localhost:8000/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"<admin email>","password":"<password>"}'
# 2) dùng token
curl http://localhost:8000/api/v1/employees -H "Authorization: Bearer <token>"
```
Base path `/api/v1`. Xem [docs/api/README.md](../api/README.md).

## Queue & Schedule
- Queue: `php artisan queue:work` (hoặc Horizon `php artisan horizon` khi dùng Redis).
- Cron (đăng ký trong [Console/Kernel](../../app/Console/Kernel.php)): cần 1 dòng crontab
  `* * * * * php artisan schedule:run`. Các job: escalate overdue approvals (hourly),
  sync contribution scores (daily), sync org graph (15'), archive audit logs (monthly).

## Công cụ chất lượng
```bash
composer test      # phpunit
composer analyse   # phpstan / larastan
composer format    # pint
```

> Lưu ý môi trường dev: `.env` đang `QUEUE_CONNECTION=sync`, `CACHE_DRIVER=file`. Production nên đổi
> sang Redis + Horizon (**TODO: Need Human Validation**).
