<?php

namespace App\Services;

use App\Models\Feature;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Schema;

class FeatureService
{
    /**
     * @var Feature
     */
    private $model;

    /**
     * @var int
     */
    private $paginate;

    public function __construct(Feature $model)
    {
        $this->model = $model;
        $this->paginate = 25;
    }

    /**
     * All features
     *
     * @param $key
     * @return Feature
     */
    public function getByKey($key)
    {
        return $this->model->where('key', $key)->first();
    }

    /**
     * All features
     *
     * @return  boolean
     */
    public function isActive($key)
    {
        if ($this->model->where('key', $key)->first()) {
            return $this->model->where('key', $key)->first()->is_active;
        }

        return false;
    }

    /**
     * Paginated features
     *
     * @return  LengthAwarePaginator
     */
    public function paginated()
    {
        return $this->model->orderBy('key', 'desc')->paginate($this->paginate);
    }

    /**
     * Search features
     *
     * @param  string $input
     * @param  integer $id
     * @return  LengthAwarePaginator
     */
    public function search($input, $id)
    {
        $query = $this->model->orderBy('key', 'desc');
        $query->where('id', 'LIKE', '%'.$input.'%');

        $columns = Schema::getColumnListing('features');

        foreach ($columns as $attribute) {
            if (is_null($id)) {
                $query->orWhere($attribute, 'LIKE', '%'.$input.'%');
            } else {
                $query->orWhere($attribute, 'LIKE', '%'.$input.'%')->where('user_id', $id);
            }
        };

        return $query->paginate($this->paginate);
    }

    /**
     * Create a notification
     *
     * @param $payload
     * @return  Feature
     * @throws \Exception
     */
    public function create($payload)
    {
        try {
            if (isset($payload['is_active'])) {
                $payload['is_active'] = true;
            } else {
                $payload['is_active'] = false;
            }

            return $this->model->create($payload);
        } catch (\Exception $e) {
            throw new \Exception("Could save your feature, please try agian.", 1);
        }
    }

    /**
     * Find a notification
     *
     * @param  integer $id
     * @return  Feature
     */
    public function find($id)
    {
        return $this->model->find($id);
    }

    /**
     * Update a notification
     *
     * @param  integer $id
     * @param  array $payload
     * @return  Feature
     */
    public function update($id, $payload)
    {
        $feature = $this->model->find($id);

        if (isset($payload['is_active'])) {
            $payload['is_active'] = true;
        } else {
            $payload['is_active'] = false;
        }

        $feature->update($payload);

        return $feature;
    }

    /**
     * Destroy a Feature
     *
     * @param  integer $id
     * @return  boolean
     */
    public function destroy($id)
    {
        return $this->model->find($id)->delete();
    }
}
