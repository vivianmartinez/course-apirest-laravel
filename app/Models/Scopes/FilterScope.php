<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class FilterScope implements Scope
{

    protected array $allowedOperators = ['=', '!=', '>', '<', '>=', '<=', 'like'];
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        //
        $filters = request('filters');
        if(empty($filters)) return;

        foreach($filters as $column => $filter){
            $operator = $filter['operator'] ?? '=';
            $value = $filter['value'] ?? null;
            if(!in_array($operator,$this->allowedOperators)) continue;
            if($operator == 'like') $value = "%{$value}%";
            $builder->where($column,$operator,$value);
        }
    }
}
