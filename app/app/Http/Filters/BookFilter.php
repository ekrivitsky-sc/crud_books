<?php

namespace App\Http\Filters;


use Illuminate\Database\Eloquent\Builder;

class BookFilter extends AbstractFilter
{
    public const NAME = 'name';
    public const YEAR = 'year';
    public const AUTHOR_LNAME = 'author_lname';
    public const AUTHOR_FNAME = 'author_fname';
    public const TAG_NAME = 'tag_name';
    public const AUTHOR_ID = 'author_id';


    protected function getCallbacks(): array
    {
        return [
            self::NAME => [$this, 'name'],
            self::YEAR => [$this, 'year'],
            self::AUTHOR_LNAME => [$this, 'authorLname'],
            self::AUTHOR_FNAME => [$this, 'authorFname'],
            self::TAG_NAME => [$this, 'tagName'],
            self::AUTHOR_ID => [$this, 'authorId'],
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

    public function authorId(Builder $builder, $value)
    {
        $builder->whereHas('authors', static function ($builder) use ($value) {
            $builder->where('authors.id', '=', $value);
        });
    }

    public function authorLname(Builder $builder, $value)
    {
        $builder->whereHas('authors', static function ($builder) use ($value) {
            $builder->where('authors.lname', 'like', "%$value%");
        });
    }

    public function authorFname(Builder $builder, $value)
    {
        $builder->whereHas('authors', static function ($builder) use ($value) {
            $builder->where('authors.fname', 'like', "%$value%");
        });
    }

    public function tagName(Builder $builder, $value)
    {
        $builder->whereHas('tags', static function ($builder) use ($value) {
            $builder->where('tags.name', 'like', "%$value%");
        });
    }
}
