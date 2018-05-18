<?php

namespace App\Services\Api\Exceptions;

class ApiException extends \Exception
{
    /**
     * @var int
     */
    private $httpStatusCode;

    /**
     * @var string
     */
    private $errorType;

    /**
     * @var null|string
     */
    private $hint;

    /**
     * @var array
     */
    private $additionalRows = [];

    /**
     * Throw a new exception.
     *
     * @param string $message Error message
     * @param int $code Error code
     * @param string $errorType Error type
     * @param int $httpStatusCode HTTP status code to send (default = 400)
     * @param null|string $hint A helper hint
     * @param array $additionalRows
     */
    public function __construct($message, $code, $errorType, $httpStatusCode = 400, $hint = null, $additionalRows = [])
    {
        parent::__construct($message, $code);
        $this->httpStatusCode = $httpStatusCode;
        $this->errorType = $errorType;
        $this->hint = $hint;
        $this->additionalRows = $additionalRows;
    }

    public static function cannotRegisterUser()
    {
        $errorMessage = 'Can`t register new user';
        $hint = 'Check with backend';

        return new static($errorMessage, 1, 'cannot_register_user', 400, $hint);
    }

    /**
     * @return static
     */
    public static function wrongTaskType()
    {
        $errorMessage = 'Task type is wrong';
        $hint = 'Try to use other endpoint';

        return new static($errorMessage, 9, 'task_already_taken_by_user', 400, $hint);
    }

    /**
     * @return static
     */
    public static function currentUserDontWorkWithTaskTask()
    {
        $errorMessage = 'Current user don`t work with this task';
        $hint = 'Check task id';

        return new static($errorMessage, 10, 'current_user_dont_work_with_task', 400, $hint);
    }

    /**
     * @return static
     */
    public static function taskExecutionTimeIsOut()
    {
        $errorMessage = 'Task execution time is out';

        return new static($errorMessage, 11, 'task_execution_time_is_out', 400);
    }

    /**
     * @return static
     */
    public static function userDontMakeAnyActionsWithThisTask()
    {
        $errorMessage = 'User don\'t make any actions with this task';

        return new static($errorMessage, 12, 'user_dont_make_any_actions_with_task', 400);
    }

    /**
     * @return string
     */
    public function getErrorType()
    {
        return $this->errorType;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function generateHttpResponse()
    {
        $headers = $this->getHttpHeaders();

        $payload = [
            'error'   => $this->getErrorType(),
            'message' => $this->getMessage(),
        ];

        if ($this->hint !== null) {
            $payload['hint'] = $this->hint;
        }
        $payload = array_merge($payload, $this->additionalRows);

        return response(json_encode($payload), $this->getHttpStatusCode(), $headers);
    }

    /**
     * Get all headers that have to be send with the error response.
     *
     * @return array Array with header values
     */
    public function getHttpHeaders()
    {
        return [
            'Content-type' => 'application/json',
        ];
    }

    /**
     * Returns the HTTP status code to send when the exceptions is output.
     *
     * @return int
     */
    public function getHttpStatusCode()
    {
        return $this->httpStatusCode;
    }

    /**
     * @return null|string
     */
    public function getHint()
    {
        return $this->hint;
    }
}
