<?php

namespace EscolaLms\Tasks\Dtos;

use Illuminate\Support\Carbon;
use EscolaLms\Core\Dtos\Contracts\DtoContract;
use EscolaLms\Core\Dtos\Contracts\InstantiateFromRequest;
use Illuminate\Http\Request;

class CreateTaskDto implements DtoContract, InstantiateFromRequest
{

    protected string $title;

    protected int $userId;

    protected int $createdById;

    protected ?Carbon $dueDate;

    protected ?string $relatedType;

    protected ?int $relatedId;

    public function __construct(string $title, ?Carbon $dueDate, ?string $relatedType, ?int $relatedId)
    {
        $this->title = $title;
        $this->userId = auth()->id();
        $this->createdById = auth()->id();
        $this->dueDate = $dueDate;
        $this->relatedType = $relatedType;
        $this->relatedId = $relatedId;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getCreatedById(): int
    {
        return $this->createdById;
    }

    public function getDueDate(): ?Carbon
    {
        return $this->dueDate;
    }

    public function getRelatedType(): ?string
    {
        return $this->relatedType;
    }

    public function getRelatedId(): ?int
    {
        return $this->relatedId;
    }

    public function toArray(): array
    {
        return [
            'title' => $this->getTitle(),
            'user_id' => $this->getUserId(),
            'created_by_id' => $this->getCreatedById(),
            'due_date' => $this->getDueDate(),
            'related_type' => $this->getRelatedType(),
            'related_id' => $this->getRelatedId(),
        ];
    }

    public static function instantiateFromRequest(Request $request): self
    {
        return new static(
            $request->input('title'),
            Carbon::parse($request->input('due_date')),
            $request->input('related_type'),
            $request->input('related_id'),
        );
    }
}

