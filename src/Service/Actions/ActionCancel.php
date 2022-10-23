<?php

namespace Taskforce\Service\Actions;

use Taskforce\Logic\Task;

class ActionCancel extends Action
{

    protected string $actionName = 'Отменить задание';
    protected string $actionCode = Task::ACTION_CANCEL;

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
