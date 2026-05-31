# Developer Guide — Testing

> Nguồn: [phpunit.xml](../../phpunit.xml), thư mục `tests/`. Chạy: `composer test`.

## Cấu trúc
```txt
tests/
  Feature/   # test luồng API end-to-end (HTTP + DB)
  Unit/      # test logic thuần (engine, diff, scoring calculator)
  TestCase.php
```

## Chạy
```bash
composer test                 # toàn bộ
php artisan test --filter=Leave   # lọc theo tên
composer analyse              # phpstan/larastan (static)
composer format               # pint (style)
```

## Feature test (mẫu khuyến nghị)
- `use RefreshDatabase;`
- Seed role/permission cần thiet; `actingAs($user)` với role phù hợp (Sanctum).
- Gọi `postJson('/api/v1/...')`, assert status + `assertJsonPath('data...')`.
- Kiểm tra side-effects: `assertDatabaseHas('audit_logs', [...])`, `Event::assertDispatched(...)`.

```php
public function test_employee_submits_leave_request(): void
{
    $user = User::factory()->create();   // gắn employee + role Employee
    $this->actingAs($user)
        ->postJson('/api/v1/leave/requests', [
            'leave_type_id' => 1, 'start_date' => '2026-06-01', 'end_date' => '2026-06-03',
        ])
        ->assertStatus(201)
        ->assertJsonPath('success', true);

    $this->assertDatabaseHas('leave_requests', ['status' => 'pending']);
}
```

## Ưu tiên test
1. Approval engine (approve/reject/last-step/authorize) — lõi nghiệp vụ.
2. Leave (date validation, quota deduct/refund).
3. Attendance (check-in trùng, late/overtime).
4. Permission/delegation (effective permissions, middleware).

> **TODO: Need Human Validation** — số lượng test hiện có trong `tests/` cần rà soát; bổ sung coverage
> cho các engine còn stub trước khi production.
