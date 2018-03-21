<?php

namespace Grafite\Cms\Repositories;

use Grafite\Cms\Repositories\TranslationRepository;
use Illuminate\Support\Facades\Schema;

class GrafiteRepository
{
    public $translationRepo;

    public $model;

    public $table;

    public function __construct(TranslationRepository $translationRepo)
    {
        $this->translationRepo = $translationRepo;
    }

    /**
     * Returns all Widgets.
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function all()
    {
        return $this->model->orderBy('created_at', 'desc')->all();
    }

    /**
     * Returns all paginated EventS.
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function paginated()
    {
        $model = $this->model;

        if (isset(request()->dir) && isset(request()->field)) {
            $model = $model->orderBy(request()->field, request()->dir);
        } else {
            $model = $model->orderBy('created_at', 'desc');
        }

        return $model->paginate(config('cms.pagination', 25));
    }

    /**
     * Search the columns of a given table
     *
     * @param  array $input
     *
     * @return array
     */
    public function search($input)
    {
        $query = $this->model->orderBy('created_at', 'desc');
        $query->where('id', 'LIKE', '%'.$input['term'].'%');

        $columns = Schema::getColumnListing($this->table);

        foreach ($columns as $attribute) {
            $query->orWhere($attribute, 'LIKE', '%'.$input['term'].'%');
        }

        return [$query, $input['term'], $query->paginate(25)->render()];
    }

    /**
     * Stores Widgets into database.
     *
     * @param array $input
     *
     * @return Widgets
     */
    public function store($input)
    {
        return $this->model->create($input);
    }

    /**
     * Find Widgets by given id.
     *
     * @param int $id
     *
     * @return \Illuminate\Support\Collection|null|static|Widgets
     */
    public function find($id)
    {
        return $this->model->find($id);
    }

    /**
     * Find items by slug.
     *
     * @param int $slug
     *
     * @return \Illuminate\Support\Collection|null|static|Model
     */
    public static function getBySlug($slug)
    {
        return $this->model->where('slug', $slug)->first();
    }

    /**
     * Updates items into database.
     *
     * @param Model $model
     * @param array $payload
     *
     * @return Model
     */
    public function update($model, $payload)
    {
        return $model->update($payload);
    }
}
