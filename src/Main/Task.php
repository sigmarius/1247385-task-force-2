<?php
namespace Taskforce\Main;

use Taskforce\Exceptions\TaskException;
use Taskforce\Service\Actions\CancelAction;
use Taskforce\Service\Actions\FinishAction;
use Taskforce\Service\Actions\ReactAction;
use Taskforce\Service\Actions\RejectAction;
use Taskforce\Service\Actions\StartAction;
use Taskforce\Main\TaskStatuses;
use Taskforce\Main\TaskActions;

class Task
{
    protected int $clientId;
    protected ?int $workerId;

    protected string $currentStatus;

    public function __construct(int $clientId, int $workerId = null, string $currentStatus = TaskStatuses::STATUS_NEW)
    {
        $this->clientId = $clientId;
        $this->workerId = $workerId;
        $this->currentStatus = $currentStatus;
    }

    public function getClientId(): int
    {
        return $this->clientId;
    }

    public function getWorkerId(): ?int
    {
        return $this->workerId;
    }

    public function getCurrentStatus(): string
    {
        return $this->currentStatus;
    }

    public function getAvailableActions(int $userId): array
    {
        $availableActions = [];

        if (CancelAction::checkAccess($this, $userId)) {
            $availableActions[] = new CancelAction();
        }

        if (StartAction::checkAccess($this, $userId)) {
            $availableActions[] = new StartAction();
        }

        if (FinishAction::checkAccess($this, $userId)) {
            $availableActions[] = new FinishAction();
        }

        if (RejectAction::checkAccess($this, $userId)) {
            $availableActions[] = new RejectAction();
        }

        if (ReactAction::checkAccess($this, $userId)) {
            $availableActions[] = new ReactAction();
        }

        return $availableActions;
    }

    public function setCurrentStatus(string $action): string
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

        return $this->currentStatus = array_keys($statuses)[$actionKey];
    }
}
