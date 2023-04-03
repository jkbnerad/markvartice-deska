<?php

declare(strict_types=1);

namespace jkbnerad\Markvartice\Deska;

final class Record
{
    private function __construct(private readonly string $from, private readonly ?string $to, private readonly string $title, private readonly string $link)
    {
    }

    public static function create(string $from, ?string $to, string $title, string $link): self
    {
        return new self($from, $to, $title, $link);
    }

    public function getFrom(): string
    {
        return $this->from;
    }

    public function getLink(): string
    {
        return $this->link;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getTo(): ?string
    {
        return $this->to;
    }
}
