<?php
namespace Taskforce\Service\Actions;

use Taskforce\Logic\Task;

abstract class Action
{
    protected string $actionName;
    protected string $actionCode;

    public function getActionName() : string
    {
        return $this->actionName;
    }

    public function getActionCode() : string
    {
        return $this->actionCode;
    }

    abstract public static function checkAccess(Task $task, int $userId) : bool;
}
