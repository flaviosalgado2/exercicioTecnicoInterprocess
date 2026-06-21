<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Ramsey\Uuid\Uuid;

trait HasUuid7
{
    public function initializeHasUuid7(): void
    {
        $this->incrementing = false;
        $this->keyType = 'string';
    }

    protected function performInsert(Builder $query): bool
    {
        if (empty($this->{$this->getKeyName()})) {
            $this->{$this->getKeyName()} = Uuid::uuid7()->toString();
        }

        return parent::performInsert($query);
    }
}
