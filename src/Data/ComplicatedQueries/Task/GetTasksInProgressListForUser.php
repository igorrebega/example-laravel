<?php

namespace App\Data\ComplicatedQueries\Task;

use App\Data\Models\Task;
use App\Data\Models\UserTaskProgress;

/**
 * Will return query with tasks list for user with $userId that is in progress
 *
 * @author i.rebega <i.rebega@bvblogic.com>
 */
class GetTasksInProgressListForUser
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
     * GetWeeksForUser constructor.
     * @param int $userId
     */
    public function __construct($userId)
    {
        $this->query = Task::query();

        $this->userId = $userId;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getQuery()
    {
        return $this
            ->addSelects()
            ->joinTaskProgress()
            ->onlyTasksInProgress()
            ->addOrder()
            ->getBuilder();
    }

    /**
     * @return $this
     */
    private function addSelects()
    {
        $this->query->select(
            'tasks.*',
            'user_task_progress.status',
            'user_task_progress.updated_at',
            'user_task_progress.price as price',
            'user_task_progress.task_type as type'
        );

        return $this;
    }

    /**
     * @return $this
     */
    private function joinTaskProgress()
    {
        $this->query->join('user_task_progress', 'user_task_progress.task_id', '=', 'tasks.id');
        return $this;
    }

    /**
     * Include only those tasks that not present in user_task_progress table
     */
    private function onlyTasksInProgress()
    {
        $this->query->where('user_task_progress.user_id', $this->userId);
        $this->query->whereIn(
            'user_task_progress.status',
            [UserTaskProgress::STATUS_WORK, UserTaskProgress::STATUS_QUEUE]
        );

        return $this;
    }

    /**
     * Start from min number
     * @return $this
     */
    private function addOrder()
    {
        $this->query->orderBy('user_task_progress.updated_at', 'desc');

        return $this;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function getBuilder()
    {
        return $this->query;
    }
}