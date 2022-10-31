<?php

namespace Taskforce\Service\Actions;

use Taskforce\Logic\Task;

class FinishAction extends BaseAction
{
    protected string $actionName = 'Завершить задание';
    protected string $actionCode = Task::ACTION_FINISH;

    public static function checkAccess(Task $task, int $userId): bool
    {
        if ($task->getCurrentStatus() !== $task::STATUS_ACTIVE) {
            return false;
        }

        return $userId === $task->getClientId();
    }
}
