<?php

namespace App\Domains\Task\Jobs;

use App\Data\ComplicatedQueries\Task\GetTasksInProgressListForUser;
use App\Data\Models\Task;
use App\Data\Models\User;
use App\Data\Models\UserTaskProgress;
use Lucid\Foundation\Job;

class GetTasksListForUserJob extends Job
{
    /**
     * @var int
     */
    private $user;

    /**
     * GetNewTasksList constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return array
     */
    public function handle()
    {
        $query = (new GetTasksInProgressListForUser($this->user->id))->getQuery()->get();

        return $query->map(function (Task $task) {
            $taskData = [
                'id'          => (int)$task->id,
                'title'       => (string)$task->title,
                'price'       => (int)$task->price,
                'description' => (string)$task->description,
                'type'        => (int)$task->type,
                'lat'         => (float)$task->lat,
                'lng'         => (float)$task->lng,
                'active_to'   => (string)$task->active_to,
                'status'      => (int)$task->status,
                'start_from'  => ''
            ];

            if ($task->status === UserTaskProgress::STATUS_WORK) {
                $taskData['start_from'] = (string)$task->updated_at;
            }

            return $taskData;
        })->toArray();
    }
}