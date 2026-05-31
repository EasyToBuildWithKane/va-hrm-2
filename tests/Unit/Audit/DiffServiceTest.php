<?php

declare(strict_types=1);

namespace Tests\Unit\Audit;

use Modules\Audit\Services\DiffService;
use PHPUnit\Framework\TestCase;

class DiffServiceTest extends TestCase
{
    public function test_detects_changed_fields(): void
    {
        $before = ['name' => 'John', 'age' => 30];
        $after = ['name' => 'Jane', 'age' => 30];

        $diff = DiffService::compute($before, $after);

        $this->assertArrayHasKey('name', $diff);
        $this->assertSame('John', $diff['name']['before']);
        $this->assertSame('Jane', $diff['name']['after']);
        $this->assertSame('changed', $diff['name']['type']);
        $this->assertArrayNotHasKey('age', $diff);
    }

    public function test_classifies_added_and_removed(): void
    {
        $diff = DiffService::compute(['a' => 1], ['b' => 2]);

        $this->assertSame('removed', $diff['a']['type']);
        $this->assertSame('added', $diff['b']['type']);
    }
}
