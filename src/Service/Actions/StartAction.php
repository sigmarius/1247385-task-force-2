<?php

namespace Taskforce\Service\Actions;

use Taskforce\Main\Task;
use Taskforce\Main\TaskStatuses;
use Taskforce\Main\TaskActions;

class StartAction extends BaseAction
{

    protected string $actionName = 'Принять отклик';
    protected string $actionCode = TaskActions::ACTION_START;

    public static function checkAccess(Task $task, int $userId): bool
    {
        if ($task->getCurrentStatus() !== TaskStatuses::STATUS_NEW) {
            return false;
        }

        if (!empty($task->getWorkerId())) {
            return false;
        }

        return $userId === $task->getClientId();
    }
}
