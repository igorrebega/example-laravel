<?php

namespace App\Services\Api\Features\Task;

use App\Domains\Task\Jobs\GetNewTasksListJob;
use App\Services\Api\Http\Requests\Task\NewTasksListRequest;
use Lucid\Foundation\Feature;

class GetNewTasksListFeature extends Feature
{
    /**
     * @param NewTasksListRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handle(NewTasksListRequest $request)
    {
        $radius = $request->get('radius');
        $lng = $request->get('lng');
        $lat = $request->get('lat');
        return $this->run(new GetNewTasksListJob(auth()->user()->id, $radius, $lat, $lng));
    }
}
