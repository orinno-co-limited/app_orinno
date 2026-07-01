<?php

namespace App\Traits;


trait ExcludableTrait
{
    private function getTableColumns()
    {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }

    public function scopeExclude($query, $columns)
    {
        return $query->select(array_diff($this->getTableColumns(), (array) $columns));
    }
}
