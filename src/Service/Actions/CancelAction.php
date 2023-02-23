<?php

namespace Taskforce\Service\Actions;

use Taskforce\Main\Task;
use Taskforce\Main\TaskActions;
use Taskforce\Main\TaskStatuses;

class CancelAction extends BaseAction
{

    protected string $actionName = 'Отменить задание';
    protected string $actionCode = TaskActions::ACTION_CANCEL;

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
