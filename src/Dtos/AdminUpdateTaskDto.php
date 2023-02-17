<?php

namespace EscolaLms\Tasks\Dtos;

use Carbon\Carbon;
use EscolaLms\Core\Dtos\Contracts\DtoContract;
use EscolaLms\Core\Dtos\Contracts\InstantiateFromRequest;
use Illuminate\Http\Request;

class AdminUpdateTaskDto extends UpdateTaskDto implements DtoContract, InstantiateFromRequest
{
    public function __construct(int $id, string $title, ?int $userId, ?Carbon $dueDate, ?string $relatedType, ?int $relatedId)
    {
        parent::__construct($id, $title, $dueDate, $relatedType, $relatedId);

        $this->userId = $userId ?? auth()->id();
    }

    public static function instantiateFromRequest(Request $request): self
    {
        return new static(
            $request->route('id'),
            $request->input('title'),
            $request->input('user_id'),
            Carbon::parse($request->input('due_date')),
            $request->input('related_type'),
            $request->input('related_id'),
        );
    }
}

