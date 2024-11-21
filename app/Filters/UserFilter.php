<?php

namespace App\Filters;

use Essa\APIToolKit\Filters\QueryFilters;

class UserFilter extends QueryFilters
{
    protected array $columnSearch = [
        "first_name",
        "middle_name",
        "last_name",
        "gender",
        "mobile_number",
        "email",
        "username",
        "userType"
    ];
}
