<?php

namespace App\Services\Api\Http\Controllers;

use App\Services\Api\Features\Task\ExecutePhotoCheckTaskFeature;
use App\Services\Api\Features\Task\GetMyTasksListFeature;
use App\Services\Api\Features\Task\GetNewTasksListFeature;
use App\Services\Api\Features\Task\TakeTaskFeature;
use Lucid\Foundation\Http\Controller;

class TaskController extends Controller
{
    /**
     * @SWG\Get(
     *      path="/task/new-tasks-list",
     *      summary="List with new tasks",
     *      description="List with new tasks in radius",
     *      consumes={"application/x-www-form-urlencoded"},
     *      tags={"Tasks"},
     *      security={{"passport" : {}}},
     *      @SWG\Parameter(
     *          name="lat",
     *          in="query",
     *          required=true,
     *          description="current latitude",
     *          type="number"
     *      ),
     *      @SWG\Parameter(
     *          name="lng",
     *          in="query",
     *          required=true,
     *          description="current longitude",
     *          type="number"
     *      ),
     *      @SWG\Parameter(
     *          name="radius",
     *          in="query",
     *          description="Radius in km",
     *          required=true,
     *          type="integer"
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              @SWG\Items(
     *                  @SWG\Property(
     *                      type="integer",
     *                      property="id"
     *                  ),
     *                  @SWG\Property(
     *                      type="string",
     *                      property="title"
     *                  ),
     *                  @SWG\Property(
     *                      type="string",
     *                      property="description"
     *                  ),
     *                  @SWG\Property(
     *                      type="number",
     *                      property="lat"
     *                  ),
     *                  @SWG\Property(
     *                      type="number",
     *                      property="lng"
     *                  ),
     *                  @SWG\Property(
     *                      type="integer",
     *                      property="type"
     *                  ),
     *                  @SWG\Property(
     *                      type="string",
     *                      property="active_to",
     *                      description="Date in Y-m-d H:i:s format"
     *                  )
     *              )
     *          )
     *      )
     *  )
     *
     * @return mixed
     */
    public function newTasksList()
    {
        return $this->serve(GetNewTasksListFeature::class);
    }

    /**
     * @SWG\Get(
     *      path="/task/my-tasks-list",
     *      summary="List with tasks that in work, or in queue for current user",
     *      consumes={"application/x-www-form-urlencoded"},
     *      tags={"Tasks"},
     *      security={{"passport" : {}}},
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              @SWG\Items(
     *                  @SWG\Property(
     *                      type="integer",
     *                      property="id"
     *                  ),
     *                  @SWG\Property(
     *                      type="string",
     *                      property="title"
     *                  ),
     *                  @SWG\Property(
     *                      type="string",
     *                      property="description"
     *                  ),
     *                  @SWG\Property(
     *                      type="number",
     *                      property="lat"
     *                  ),
     *                  @SWG\Property(
     *                      type="number",
     *                      property="lng"
     *                  ),
     *                  @SWG\Property(
     *                      type="integer",
     *                      property="type"
     *                  ),
     *                  @SWG\Property(
     *                      type="string",
     *                      property="active_to",
     *                      description="Date in Y-m-d H:i:s format"
     *                  ),
     *                  @SWG\Property(
     *                      type="integer",
     *                      property="status",
     *                      description="1 - in work, 2 - in queue"
     *                  ),
     *                  @SWG\Property(
     *                      type="string",
     *                      property="start_from",
     *                      description="Date in Y-m-d H:i:s format when status = 1, or empty string where status = 2"
     *                  )
     *              )
     *          )
     *      )
     *  )
     *
     * @return mixed
     */
    public function myTasksList()
    {
        return $this->serve(GetMyTasksListFeature::class);
    }
}