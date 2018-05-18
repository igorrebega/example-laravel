<?php

namespace App\Domains\Task\Tests;

use App\Data\Models\Task;
use App\Data\Models\User;
use App\Data\Models\UserTaskProgress;
use App\Domains\Task\Jobs\GetNewTasksListJob;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class GetNewTasksListJobTest extends TestCase
{
    use DatabaseMigrations;

    public function test_job_must_return_list_of_only_new_tasks_for_user_in_selected_radius()
    {
        $user = factory(User::class)->create([
            'role' => User::ROLE_USER,
        ]);

        $otherUser = factory(User::class)->create([
            'role' => User::ROLE_USER,
        ]);

        $lat = 48.919996;
        $lng = 24.715297;
        $radius = 5;

        //Task within 5km
        $task1 = factory(Task::class)->create([
            'lat' => 48.911217,
            'lng' => 24.767154
        ]);

        //Completed task
        $task2 = factory(Task::class)->create();

        //Task without 5km
        $task3 = factory(Task::class)->create([
            'lat' => 49.911217,
            'lng' => 24.767154
        ]);

        factory(UserTaskProgress::class)->create([
            'task_id' => $task2->id,
            'user_id' => $user->id
        ]);

        factory(UserTaskProgress::class)->create([
            'task_id' => $task1->id,
            'user_id' => $otherUser->id
        ]);

        $items = dispatch_now(new GetNewTasksListJob($user->id, $lat, $lng, $radius));

        $this->assertCount(1, $items);

        $this->assertEquals([
            0 => [
                'id'          => $task1->id,
                'price'       => $task1->price,
                'description' => $task1->description,
                'title'       => $task1->title,
                'lat'         => $task1->lat,
                'lng'         => $task1->lng,
                'active_to'   => $task1->active_to,
                'type'        => $task1->type
            ]
        ], $items);
    }
}
