<?php

namespace Taskforce\Service\Actions;

use Taskforce\Main\Task;
use Taskforce\Main\TaskStatuses;
use Taskforce\Main\TaskActions;

class ReactAction extends BaseAction
{

    protected string $actionName = 'Откликнуться на задание';
    protected string $actionCode = TaskActions::ACTION_REACT;

    public static function checkAccess(Task $task, int $userId): bool
    {
        if ($task->getCurrentStatus() !== TaskStatuses::STATUS_NEW) {
            return false;
        }

        return $userId === $task->getWorkerId();
    }
}
