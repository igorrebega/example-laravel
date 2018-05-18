<?php

namespace App\Domains\Task\Jobs;

use App\Data\ComplicatedQueries\Task\GetNewTasksListForUser;
use App\Data\Models\Task;
use Lucid\Foundation\Job;

class GetNewTasksListJob extends Job
{
    /**
     * @var int
     */
    private $userId;
    /**
     * @var int
     */
    private $radius;
    /**
     * @var float
     */
    private $lat;
    /**
     * @var float
     */
    private $lng;

    /**
     * GetNewTasksList constructor.
     * @param int $userId
     * @param int $radius
     * @param float $lat
     * @param float $lng
     */
    public function __construct($userId, $radius, $lat, $lng)
    {
        $this->userId = $userId;
        $this->radius = $radius;
        $this->lat = $lat;
        $this->lng = $lng;
    }

    /**
     * @return array
     */
    public function handle()
    {
        $query = (new GetNewTasksListForUser($this->userId, $this->radius, $this->lng, $this->lat))->getQuery()->get();

        return $query->map(function (Task $task) {
            return [
                'id'          => (int)$task->id,
                'title'       => (string)$task->title,
                'price'       => (int)$task->price,
                'description' => (string)$task->description,
                'type'        => (int)$task->type,
                'lat'         => (float)$task->lat,
                'lng'         => (float)$task->lng,
                'active_to'   => (string)$task->active_to
            ];
        })->toArray();
    }
}