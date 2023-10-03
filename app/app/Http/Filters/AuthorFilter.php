<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;

class AuthorFilter extends AbstractFilter
{
    public const LNAME = 'lname';
    public const FNAME = 'fname';


    protected function getCallbacks(): array
    {
        return [
            self::FNAME => [$this, 'firstName'],
            self::LNAME => [$this, 'lastName'],
        ];
    }

    public function firstName(Builder $builder, $value)
    {
        $builder->where('fname', 'like', "%$value%");
    }

    public function lastName(Builder $builder, $value)
    {
        $builder->where('lname', 'like', "%$value%");
    }
}
