<?php

namespace Taskforce\Service\Actions;

use Taskforce\Logic\Task;

class ActionReact extends Action
{

    protected string $actionName = 'Откликнуться на задание';
    protected string $actionCode = Task::ACTION_REACT;

    public static function checkAccess(Task $task, int $userId): bool
    {
        if ($task->getCurrentStatus() !== $task::STATUS_NEW) {
            return false;
        }

        return $userId === $task->getWorkerId();
    }
}
