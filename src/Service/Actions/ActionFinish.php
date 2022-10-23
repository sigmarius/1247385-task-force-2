<?php

namespace Taskforce\Service\Actions;

use Taskforce\Logic\Task;

class ActionFinish extends Action
{
    protected string $actionName = 'Завершить задание';
    protected string $actionCode = Task::ACTION_FINISH;

    public static function checkAccess(Task $task, int $userId): bool
    {
        if ($task->getCurrentStatus() !== $task::STATUS_ACTIVE) {
            return false;
        }

        if ($userId === $task->getWorkerId()) {
            return false;
        }

        return $userId === $task->getClientId();
    }
}
