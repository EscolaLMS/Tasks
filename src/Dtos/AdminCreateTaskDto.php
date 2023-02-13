<?php

namespace EscolaLms\Tasks\Dtos;

use Carbon\Carbon;
use EscolaLms\Core\Dtos\Contracts\DtoContract;
use EscolaLms\Core\Dtos\Contracts\InstantiateFromRequest;
use Illuminate\Http\Request;

class AdminCreateTaskDto extends CreateTaskDto implements DtoContract, InstantiateFromRequest
{

    private ?string $note;

    public function __construct(string $title, ?string $note, ?int $userId, ?Carbon $dueDate, ?string $relatedType, ?int $relatedId)
    {
        parent::__construct($title, $dueDate, $relatedType, $relatedId);

        $this->note = $note;
        $this->userId = $userId ?? auth()->id();
        $this->createdById = auth()->id();
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function toArray(): array
    {
        return parent::toArray() + [
            'note' => $this->getNote(),
        ];
    }

    public static function instantiateFromRequest(Request $request): self
    {
        return new static(
            $request->input('title'),
            $request->input('note'),
            $request->input('user_id'),
            Carbon::parse($request->input('due_date')),
            $request->input('related_type'),
            $request->input('related_id'),
        );
    }
}

