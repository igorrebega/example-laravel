<?php

namespace App\Domains\Task\Tests;

use App\Data\Models\Task;
use App\Data\Models\User;
use App\Data\Models\UserTaskProgress;
use App\Domains\Task\Jobs\GetTasksListForUserJob;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class GetTasksListForUserJobTest extends TestCase
{
    use DatabaseMigrations;

    public function test_job_must_return_list_only_for_work_or_queue_task_for_current_user()
    {
        $user = factory(User::class)->create([
            'role' => User::ROLE_USER,
        ]);

        $otherUser = factory(User::class)->create([
            'role' => User::ROLE_USER,
        ]);

        $task1 = factory(Task::class)->create();
        $task2 = factory(Task::class)->create();
        $task3 = factory(Task::class)->create();
        $task4 = factory(Task::class)->create();

        factory(UserTaskProgress::class)->create([
            'task_id' => $task1->id,
            'user_id' => $user->id,
            'status'  => UserTaskProgress::STATUS_WORK,
            'price'   => 2
        ]);

        factory(UserTaskProgress::class)->create([
            'task_id' => $task2->id,
            'user_id' => $otherUser->id,
            'status'  => UserTaskProgress::STATUS_WORK
        ]);

        factory(UserTaskProgress::class)->create([
            'task_id' => $task3->id,
            'user_id' => $user->id,
            'status'  => UserTaskProgress::STATUS_QUEUE,
            'price'   => 3
        ]);

        factory(UserTaskProgress::class)->create([
            'task_id' => $task4->id,
            'user_id' => $user->id,
            'status'  => UserTaskProgress::STATUS_CANCELED
        ]);

        $items = dispatch_now(new GetTasksListForUserJob($user));

        $this->assertCount(2, $items);

        $this->assertEquals([
            [
                'id'          => $task1->id,
                'title'       => $task1->title,
                'price'       => 2,
                'description' => $task1->description,
                'type'        => $task1->type,
                'lat'         => $task1->lat,
                'lng'         => $task1->lng,
                'active_to'   => (string)$task1->active_to,
                'status'      => UserTaskProgress::STATUS_WORK,
                'start_from'  => (string)$task1->updated_at,
            ],
            [
                'id'          => $task3->id,
                'title'       => $task3->title,
                'price'       => 3,
                'description' => $task3->description,
                'type'        => $task3->type,
                'lat'         => $task3->lat,
                'lng'         => $task3->lng,
                'active_to'   => (string)$task3->active_to,
                'status'      => UserTaskProgress::STATUS_QUEUE,
                'start_from'  => '',
            ],
        ], $items);
    }
}
