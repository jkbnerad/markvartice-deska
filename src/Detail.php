<?php

declare(strict_types=1);

namespace jkbnerad\Markvartice\Deska;

final class Detail
{
    /**
     * @param File[] $files
     */
    private function __construct(
        private readonly string $title,
        private readonly string $description,
        private readonly string $from,
        private readonly ?string $to,
        private readonly array $files
    ) {
    }

    /**
     * @param File[] $files
     */
    public static function create(string $title, string $description, string $from, ?string $to, array $files): self
    {
        return new self($title, $description, $from, $to, $files);
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return File[]
     */
    public function getFiles(): array
    {
        return $this->files;
    }

    public function getFrom(): string
    {
        return $this->from;
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
