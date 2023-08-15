<?php

namespace Taskforce\Service\Actions;

use app\models\Tasks;

use Taskforce\Service\Task\TaskActions;
use Taskforce\Service\Task\TaskStatuses;

class CancelAction extends BaseAction
{

    protected string $actionName = 'Отменить задание';
    protected string $actionCode = TaskActions::ACTION_CANCEL;

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
