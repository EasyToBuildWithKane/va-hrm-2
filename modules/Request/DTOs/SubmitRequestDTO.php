<?php

declare(strict_types=1);

namespace Modules\Request\DTOs;

use Illuminate\Http\Request;

final class SubmitRequestDTO
{
    /**
     * @param  array<string, mixed>  $payload
     */
    public function __construct(
        public readonly string $requestType,
        public readonly int $employeeId,
        public readonly array $payload,
        public readonly ?string $justification = null,
    ) {
    }

    public static function fromRequest(Request $request): self
    {
        return new self(
            requestType: $request->string('request_type')->toString(),
            employeeId: (int) $request->integer('employee_id'),
            payload: (array) $request->input('payload', []),
            justification: $request->string('justification')->toString() ?: null,
        );
    }
}
