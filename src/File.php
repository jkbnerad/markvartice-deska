<?php

declare(strict_types=1);

namespace jkbnerad\Markvartice\Deska;

final class File
{
    private function __construct(private readonly string $name, private readonly string $link)
    {
    }

    public static function create(string $name, string $link): self
    {
        return new self($name, $link);
    }

    public function getLink(): string
    {
        return $this->link;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
