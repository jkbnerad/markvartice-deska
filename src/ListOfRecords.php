<?php

declare(strict_types=1);

namespace jkbnerad\Markvartice\Deska;

class ListOfRecords
{
    /**
     * @var Record[]
     */
    private array $records = [];
    private int $count = 0;

    /**
     * @param Record[] $records
     */
    public static function create(array $records): self
    {
        $list = new self();
        foreach ($records as $record) {
            $list->add($record);
        }

        return $list;
    }

    public function add(Record $record): void
    {
        $this->count++;
        $this->records[] = $record;
    }

    public function getCount(): int
    {
        return $this->count;
    }

    /**
     * @return Record[]
     */
    public function getRecords(): array
    {
        return $this->records;
    }
}
