<?php

namespace App\Services\Api\Features\Task;

use App\Domains\Task\Jobs\GetTasksListForUserJob;
use App\Domains\User\Jobs\GetCurrentUserJob;
use Lucid\Foundation\Feature;

class GetMyTasksListFeature extends Feature
{
    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handle()
    {
        $user = $this->run(GetCurrentUserJob::class);
        return $this->run(new GetTasksListForUserJob($user));
    }
}
