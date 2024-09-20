<?php

namespace App\Builders;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Str;

class BidirectionalBuilder extends Builder
{
    public function __construct($query)
    {
        parent::__construct($query);
    }

    public function whereDoesntHaveBidirectional($relation)
    {
        //$query = $this->has($relation);

        return $this->whereHas('friends');
    }

}
