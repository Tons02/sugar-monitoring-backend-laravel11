<?php

namespace App\Filters;

use Essa\APIToolKit\Filters\QueryFilters;

class DailySugarFilter extends QueryFilters
{
    protected array $columnSearch = [
        'user_id',
        'mgdl',
        'description',
        'date',
    ];

    public function user($user)
    {
        if ($user !== null && is_string($user) && $user !== '') {
            // Convert the comma-separated string into an array
            $userArray = explode(',', $user);
            
            // Use whereIn to filter results based on the array
            $this->builder->whereIn('user_id', $userArray);
        }
    
        return $this; 
    }
}
