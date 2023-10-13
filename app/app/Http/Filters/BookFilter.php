<?php

namespace App\Http\Filters;


use Illuminate\Database\Eloquent\Builder;

class BookFilter extends AbstractFilter
{
    public const NAME = 'name';
    public const YEAR = 'year';
    public const TAGS = 'tags';
    public const AUTHORS = 'authors';


    protected function getCallbacks(): array
    {
        return [
            self::NAME => [$this, 'name'],
            self::YEAR => [$this, 'year'],
            self::TAGS => [$this, 'tags'],
            self::AUTHORS => [$this, 'authors'],
        ];
    }

    public function name(Builder $builder, $value)
    {
        $builder->where('name', 'like', "%$value%");
    }

    public function year(Builder $builder, $value)
    {
        $builder->where('year', '=', $value);
    }

    public function authors(Builder $builder, $value)
    {
        $builder->whereHas('authors', static function ($builder) use ($value) {
            $builder->whereIn('authors.id', $value);
        });
    }

    public function tags(Builder $builder, $value)
    {
        $builder->whereHas('tags', static function ($builder) use ($value) {
            $builder->whereIn('tags.id', $value);
        });
    }
}
