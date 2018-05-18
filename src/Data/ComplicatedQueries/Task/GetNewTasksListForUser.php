<?php

namespace App\Data\ComplicatedQueries\Task;

use App\Data\Models\Task;
use DB;
use Illuminate\Database\Query\Builder;

/**
 * Will return query with tasks list for user with $userId in selected radius and with center in $lat and $lng
 *
 * @author i.rebega <i.rebega@bvblogic.com>
 */
class GetNewTasksListForUser
{
    /**
     * @var \Illuminate\Database\Eloquent\Builder
     */
    private $query;
    /**
     * @var int
     */
    private $userId;
    /**
     * @var float
     */
    private $radius;
    /**
     * @var float
     */
    private $lng;
    /**
     * @var float
     */
    private $lat;

    /**
     * GetWeeksForUser constructor.
     * @param int $userId
     * @param float $radius
     * @param float $lng
     * @param float $lat
     * @internal param $dayId
     */
    public function __construct($userId, $radius, $lng, $lat)
    {
        $this->query = Task::query();

        $this->userId = $userId;
        $this->radius = $radius;
        $this->lng = $lng;
        $this->lat = $lat;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getQuery()
    {
        return $this
            ->addSelects()
            ->onlyNewTasks()
            ->onlyInSelectedRadius()
            ->addOrder()
            ->getBuilder();
    }

    /**
     * @return $this
     */
    protected function addSelects()
    {
        $this->query->select(
            'tasks.*'
        );

        return $this;
    }

    /**
     * Include only those tasks that not present in user_task_progress table
     */
    protected function onlyNewTasks()
    {
        $this->query->whereNotExists(function (Builder $query) {
            $query->select(DB::raw(1))
                ->from('user_task_progress')
                ->whereRaw('user_task_progress.task_id = tasks.id')
                ->where('user_id', $this->userId);
        });
        return $this;
    }

    /**
     * Show tasks only in circle by selected radius and center
     * @return $this
     */
    protected function onlyInSelectedRadius()
    {
        $radiusQuery = '(6371 *
        acos(
            cos( radians( ? ) ) *
            cos( radians(lat) ) *
            cos(
                radians(lng) - radians( ? )
            ) +
            sin(radians( ? )) *
            sin(radians(lat))
        )) as distance';

        $this->query
            ->addSelect(DB::raw($radiusQuery))
            ->addBinding([$this->lat, $this->lng, $this->lat], 'select')
            ->having('distance', '<', $this->radius);

        return $this;
    }

    /**
     * Start from min number
     * @return $this
     */
    protected function addOrder()
    {
        $this->query->orderBy('tasks.created_at', 'desc');

        return $this;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function getBuilder()
    {
        return $this->query;
    }


}