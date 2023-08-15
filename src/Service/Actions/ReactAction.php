<?php

namespace Taskforce\Service\Actions;

use app\models\Reactions;
use app\models\Tasks;
use Taskforce\Service\Task\TaskActions;
use Taskforce\Service\Task\TaskStatuses;

class ReactAction extends BaseAction
{

    protected string $actionName = 'Откликнуться на задание';
    protected string $actionCode = TaskActions::ACTION_REACT;

    public static function checkAccess(Tasks $task, int $userId): bool
    {
        if (!\Yii::$app->user->can('worker')) {
            return false;
        }

        if ($task->current_status !== TaskStatuses::STATUS_NEW) {
            return false;
        }

        $reactionExists = Reactions::find()
            ->where(['worker_id' => $userId])
            ->andWhere(['task_id' => $task->id])
            ->exists();

        return !$reactionExists;
    }


    public function getAvailableActions(): array
    {
        return [
            'code' => self::getActionCode(),
            'name' => self::getActionName()
        ];
    }
}
