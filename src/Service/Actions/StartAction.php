<?php

namespace Taskforce\Service\Actions;

use app\models\Tasks;

use Taskforce\Service\Task\TaskActions;
use Taskforce\Service\Task\TaskStatuses;

class StartAction extends BaseAction
{

    protected string $actionName = 'Принять отклик';
    protected string $actionCode = TaskActions::ACTION_START;

    public static function checkAccess(Tasks $task, int $userId): bool
    {
        if ($task->current_status !== TaskStatuses::STATUS_NEW) {
            return false;
        }

        if (!empty($task->worker_id)) {
            return false;
        }

        return $userId === $task->client_id;
    }

    public function getAvailableActions(): array
    {
        return [
            'code' => self::getActionCode(),
            'name' => self::getActionName()
        ];
    }
}
