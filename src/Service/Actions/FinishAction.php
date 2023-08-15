<?php

namespace Taskforce\Service\Actions;

use app\models\Tasks;
use Taskforce\Service\Task\TaskActions;
use Taskforce\Service\Task\TaskStatuses;

class FinishAction extends BaseAction
{
    protected string $actionName = 'Завершить задание';
    protected string $actionCode = TaskActions::ACTION_FINISH;

    public static function checkAccess(Tasks $task, int $userId): bool
    {
        if ($task->current_status !== TaskStatuses::STATUS_ACTIVE) {
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
