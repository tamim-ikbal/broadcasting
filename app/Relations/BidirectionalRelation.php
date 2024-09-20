<?php

namespace App\Relations;

use Closure;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\Concerns\AsPivot;
use Illuminate\Database\Eloquent\Relations\Concerns\InteractsWithDictionary;
use Illuminate\Database\Eloquent\Relations\Concerns\InteractsWithPivotTable;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Grammars\MySqlGrammar;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use InvalidArgumentException;

/**
 * @template TRelatedModel of \Illuminate\Database\Eloquent\Model
 * @template TDeclaringModel of \Illuminate\Database\Eloquent\Model
 *
 * @extends \Illuminate\Database\Eloquent\Relations\Relation<TRelatedModel, TDeclaringModel, \Illuminate\Database\Eloquent\Collection<int, TRelatedModel>>
 */
class BidirectionalRelation extends BelongsToMany
{

    protected function performJoin($query = null)
    {
        $query = $query ?: $this->query;

        // We need to join to the intermediate table on the related model's primary
        // key column with the intermediate table's foreign key for the related
        // model instance. Then we can set the "where" for the parent models.
        $query->join($this->table, function (JoinClause $clause) {
            $clause->on($this->getQualifiedRelatedKeyName(), '=', $this->getQualifiedRelatedPivotKeyName())
                   ->orOn($this->getQualifiedRelatedKeyName(), '=', $this->getQualifiedForeignPivotKeyName());
        });

        return $this;
    }

    /**
     * Set the where clause for the relation query.
     *
     * @return $this
     */
    protected function addWhereConstraints()
    {
        $this->query->where(function (Builder $whereQuery) {
            $whereQuery->where($this->getQualifiedForeignPivotKeyName(), '=', $this->parent->{$this->parentKey})
                       ->orWhere($this->getQualifiedRelatedPivotKeyName(), '=', $this->parent->{$this->parentKey});
        })->where($this->getQualifiedRelatedKeyName(), '!=', $this->parent->{$this->parentKey});

        return $this;
    }

    /** @inheritDoc */
    public function addEagerConstraints(array $models)
    {

        $whereIn = $this->whereInMethod($this->parent, $this->parentKey);
        $this->whereInEager(
            $whereIn,
            $this->getQualifiedForeignPivotKeyName(),
            $this->getKeys($models, $this->parentKey)
        );
    }

    /**
     * Add a whereIn eager constraint for the given set of model keys to be loaded.
     *
     * @param  string  $whereIn
     * @param  string  $key
     * @param  array  $modelKeys
     * @param  \Illuminate\Database\Eloquent\Builder<TRelatedModel>|null  $query
     *
     * @return void
     */
    protected function whereInEager(string $whereIn, string $key, array $modelKeys, ?Builder $query = null)
    {
        ($query ?? $this->query)->where(function (Builder $builder) use ($whereIn, $key, $modelKeys) {
            $builder->{$whereIn}($key, $modelKeys)->{'or'.$whereIn}($this->getQualifiedRelatedPivotKeyName(),
                $modelKeys);
        });
        if ($modelKeys === []) {
            $this->eagerKeysWereEmpty = true;
        }
    }

    /**
     * Build model dictionary keyed by the relation's foreign key.
     *
     * @param  \Illuminate\Database\Eloquent\Collection<int, TRelatedModel>  $results
     *
     * @return array<array<string, TRelatedModel>>
     */
    protected function buildDictionary(Collection $results)
    {
        // First we'll build a dictionary of child models keyed by the foreign key
        // of the relation so that we will easily and quickly match them to the
        // parents without having a possibly slow inner loop for every model.
        $dictionary = [];
        foreach ($results as $result) {

            if ($result->{$this->accessor}->{$this->relatedPivotKey} === $result->id) {
                $value = $this->getDictionaryKey($result->{$this->accessor}->{$this->foreignPivotKey});
            } else {
                $value = $this->getDictionaryKey($result->{$this->accessor}->{$this->relatedPivotKey});
            }

            $dictionary[$value][] = $result;
        }


        return $dictionary;
    }

    /** @inheritDoc */
    public function getRelationExistenceQuery(Builder $query, Builder $parentQuery, $columns = ['*'])
    {

        if ($parentQuery->getQuery()->from == $query->getQuery()->from) {
            return $this->getRelationExistenceQueryForSelfJoin($query, $parentQuery, $columns);
        }

        $this->performJoin($query);

        return parent::getRelationExistenceQuery($query, $parentQuery, $columns);
    }

    /**
     * Add the constraints for a relationship query on the same table.
     *
     * @param  \Illuminate\Database\Eloquent\Builder<TRelatedModel>  $query
     * @param  \Illuminate\Database\Eloquent\Builder<TDeclaringModel>  $parentQuery
     * @param  array|mixed  $columns
     *
     * @return \Illuminate\Database\Eloquent\Builder<TRelatedModel>
     */
    public function getRelationExistenceQueryForSelfJoin(Builder $query, Builder $parentQuery, $columns = ['*'])
    {

        $query->select($columns);
        $query->from($this->related->getTable().' as '.$hash = $this->getRelationCountHash());

        $this->related->setTable($hash);

        $this->performJoin($query);


        $query->select($columns)->where(function ($row) {
            $row->whereColumn(
                $this->getQualifiedParentKeyName(), '=', $this->getExistenceCompareKey()
            )->orWhereColumn(
                $this->getQualifiedParentKeyName(), '=', $this->getQualifiedRelatedPivotKeyName()
            );
        });

        return $query;

    }

    /**
     * Detach models from the relationship.
     *
     * @param  mixed  $ids
     * @param  bool  $touch
     *
     * @return int
     */
    public function detach($ids = null, $touch = true)
    {
        if ($this->using &&
            ! empty($ids) &&
            empty($this->pivotWheres) &&
            empty($this->pivotWhereIns) &&
            empty($this->pivotWhereNulls)) {
            $results = $this->detachUsingCustomClass($ids);
        } else {
//            $query = $this->newPivotQuery();
            $query = $this->newPivotStatement();
            // If associated IDs were passed to the method we will only delete those
            // associations, otherwise all of the association ties will be broken.
            // We'll return the numbers of affected rows when we do the deletes.
            if ( ! is_null($ids)) {
                $ids = $this->parseIds($ids);

                if (empty($ids)) {
                    return 0;
                }

                $query->where(function ($builder) use ($ids) {
                    $builder->where($this->getQualifiedRelatedPivotKeyName(), $this->parent->{$this->relatedKey})
                            ->whereIn($this->getQualifiedForeignPivotKeyName(), (array) $ids);
                })->orWhere(function ($builder) use ($ids) {
                    $builder->where($this->getQualifiedForeignPivotKeyName(), $this->parent->{$this->parentKey})
                            ->whereIn($this->getQualifiedRelatedPivotKeyName(), (array) $ids);
                });
            }
            //dd($query->toSql());

            // Once we have all of the conditions set on the statement, we are ready
            // to run the delete on the pivot table. Then, if the touch parameter
            // is true, we will go ahead and touch all related models to sync.
            $results = $query->delete();
        }

        if ($touch) {
            $this->touchIfTouching();
        }

        return $results;
    }

    public function newPivotQuery()
    {
        $query = $this->newPivotStatement();

        foreach ($this->pivotWheres as $arguments) {
            $query->where(...$arguments);
        }

        foreach ($this->pivotWhereIns as $arguments) {
            $query->whereIn(...$arguments);
        }

        foreach ($this->pivotWhereNulls as $arguments) {
            $query->whereNull(...$arguments);
        }

        return $query->where(function ($builder) {
            $builder->where($this->getQualifiedForeignPivotKeyName(), $this->parent->{$this->parentKey})
                    ->orWhere($this->getQualifiedRelatedPivotKeyName(), $this->parent->{$this->relatedKey});
        });
    }

}
