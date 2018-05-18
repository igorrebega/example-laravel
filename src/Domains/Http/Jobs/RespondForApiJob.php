<?php

namespace App\Domains\Http\Jobs;

use Illuminate\Routing\ResponseFactory;
use Lucid\Foundation\Job;

class RespondForApiJob extends Job
{
    protected $status;
    protected $content;
    protected $headers;
    protected $options;

    /**
     * RespondWithJsonJob constructor.
     * @param $content
     * @param int $status
     * @param array $headers
     * @param int $options
     */
    public function __construct($content, $status = 200, array $headers = [], $options = 0)
    {
        $this->content = $content;
        $this->status = $status;
        $this->headers = $headers;
        $this->options = $options;
    }

    /**
     * @param ResponseFactory $factory
     * @return \Illuminate\Http\JsonResponse
     */
    public function handle(ResponseFactory $factory)
    {
        return $factory->json($this->content, $this->status, $this->headers, $this->options);
    }
}
