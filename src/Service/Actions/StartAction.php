<?php

namespace Taskforce\Service\Actions;

use Taskforce\Logic\Task;

class ActionStart extends BaseAction
{

    protected string $actionName = 'Принять отклик';
    protected string $actionCode = Task::ACTION_START;

    public static function checkAccess(Task $task, int $userId): bool
    {
        if ($task->getCurrentStatus() !== $task::STATUS_NEW) {
            return false;
        }

        if ($task->getWorkerId() !== 0) {
            return false;
        }

        return $userId === $task->getClientId();
    }
}
