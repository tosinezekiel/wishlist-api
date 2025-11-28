<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait Filterable
{
    protected function applySearch(Builder $query, ?string $search, array $fields): Builder
    {
        if (!$search) {
            return $query;
        }

        return $query->where(function ($q) use ($search, $fields) {
            foreach ($fields as $field) {
                $q->orWhere($field, 'like', "%{$search}%");
            }
        });
    }

    protected function applySorting(Builder $query, ?string $sortBy, string $direction, array $allowedSorts): Builder
    {
        if ($sortBy && in_array($sortBy, $allowedSorts, true)) {
            return $query->orderBy($sortBy, $direction);
        }

        return $query->orderBy('created_at', 'desc');
    }
}
