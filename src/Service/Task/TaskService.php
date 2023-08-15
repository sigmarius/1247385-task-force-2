<?php
namespace Taskforce\Service\Task;

use app\models\Feedbacks;
use app\models\Tasks;
use Taskforce\Exceptions\TaskException;
use Taskforce\Service\Actions\CancelAction;
use Taskforce\Service\Actions\FinishAction;
use Taskforce\Service\Actions\ReactAction;
use Taskforce\Service\Actions\RejectAction;
use Taskforce\Service\Actions\StartAction;
use Taskforce\Service\Task\TaskActions;
use Taskforce\Service\Task\TaskStatuses;

class TaskService
{
    protected int $userId;
    protected Tasks $task;

    public function __construct(int $taskId)
    {
        $this->userId = \Yii::$app->user->identity->id;
        $this->task = Tasks::getTaskByPrimary($taskId);
    }

    public function getAvailableActions(): array
    {
        $availableActions = [];

        if (CancelAction::checkAccess($this->task, $this->userId)) {
            $availableActions[] = (new CancelAction())->getAvailableActions();
        }

        if (StartAction::checkAccess($this->task, $this->userId)) {
            $availableActions[] = (new StartAction())->getAvailableActions();
        }

        if (FinishAction::checkAccess($this->task, $this->userId)) {
            $availableActions[] = (new FinishAction())->getAvailableActions();
        }

        if (RejectAction::checkAccess($this->task, $this->userId)) {
            $availableActions[] = (new RejectAction())->getAvailableActions();
        }

        if (ReactAction::checkAccess($this->task, $this->userId)) {
            $availableActions[] = (new ReactAction())->getAvailableActions();
        }

        return $availableActions;
    }

    public function setTaskStatus(string $action): string
    {
        $statuses = TaskStatuses::getStatusesMap();
        $actions = TaskActions::getActionsMap();

        // задача инициализируется со статусом Новая
        unset($statuses[TaskStatuses::STATUS_NEW]);

        // у задачи не предусмотрена смена статуса, когда исполнитель создает отклик
        unset($actions[TaskActions::ACTION_REACT]);

        if (!array_key_exists($action, $actions)) {
            throw new TaskException('Действие не предусмотрено в системе');
        }

        $actionKey = array_search($action, array_keys($actions));

        return array_keys($statuses)[$actionKey];
    }

    public function startTaskAction(int $workerId): bool
    {
        $this->task->current_status = $this->setTaskStatus(TaskActions::ACTION_START);
        $this->task->worker_id = $workerId;

        return $this->task->save();
    }

    public function finishTaskAction(object $params): bool
    {
        $feedback = new Feedbacks();

        $feedback->client_id = $this->task->client_id;
        $feedback->task_id = $this->task->id;
        $feedback->comment = $params->comment;
        $feedback->rating = $params->rating;
        $feedback->date_created = (new \DateTime('now', new \DateTimeZone('Europe/Moscow')))->format('Y-m-d H:i:s');
        $feedback->worker_id = $this->task->worker_id;

        $feedback->save(false);

        $this->task->current_status = $this->setTaskStatus(TaskActions::ACTION_FINISH);

        return $this->task->save();
    }

    public function cancelTaskAction(): bool
    {
        $this->task->current_status = $this->setTaskStatus(TaskActions::ACTION_CANCEL);

        return $this->task->save(false);
    }

    public function rejectTaskAction(): bool
    {
        $this->task->current_status = $this->setTaskStatus(TaskActions::ACTION_REJECT);

        return $this->task->save(false);
    }
}
