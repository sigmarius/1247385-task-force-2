<?php

namespace Taskforce\Service\Actions;

use Taskforce\Main\Task;
use Taskforce\Main\TaskStatuses;
use Taskforce\Main\TaskActions;

class FinishAction extends BaseAction
{
    protected string $actionName = 'Завершить задание';
    protected string $actionCode = TaskActions::ACTION_FINISH;

    public static function checkAccess(Task $task, int $userId): bool
    {
        if ($task->getCurrentStatus() !== TaskStatuses::STATUS_ACTIVE) {
            return false;
        }

        return $userId === $task->getClientId();
    }
}
