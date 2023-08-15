<?php

namespace Taskforce\Service\Actions;

use app\models\Tasks;

use Taskforce\Service\Task\TaskActions;
use Taskforce\Service\Task\TaskStatuses;

class RejectAction extends BaseAction
{

    protected string $actionName = 'Отказаться от задания';
    protected string $actionCode = TaskActions::ACTION_REJECT;

    public static function checkAccess(Tasks $task, int $userId): bool
    {
        if ($task->current_status !== TaskStatuses::STATUS_ACTIVE) {
            return false;
        }

        return $userId === $task->worker_id;
    }


    public function getAvailableActions(): array
    {
        return [
            'code' => self::getActionCode(),
            'name' => self::getActionName()
        ];
    }
}
