<?php
namespace Taskforce\Logic;

use Taskforce\Service\Actions\CancelAction;
use Taskforce\Service\Actions\FinishAction;
use Taskforce\Service\Actions\ReactAction;
use Taskforce\Service\Actions\RejectAction;
use Taskforce\Service\Actions\StartAction;

class Task
{
    protected int $clientId;
    protected int $workerId;

    protected string $currentStatus;

    const STATUS_UNDO = 'undo';
    const STATUS_ACTIVE = 'active';
    const STATUS_DONE = 'done';
    const STATUS_FAIL = 'fail';
    const STATUS_NEW = 'new';

    const ACTION_CANCEL = 'clientCancel';
    const ACTION_START = 'clientStart';
    const ACTION_FINISH = 'clientFinish';
    const ACTION_REJECT = 'workerReject';
    const ACTION_REACT = 'workerReact';

    public function __construct(int $clientId, int $workerId = 0, string $currentStatus = self::STATUS_NEW)
    {
        $this->clientId = $clientId;
        $this->workerId = $workerId;
        $this->currentStatus = $currentStatus;
    }

    public function getClientId(): int
    {
        return $this->clientId;
    }

    public function getWorkerId(): int
    {
        return $this->workerId;
    }

    public function getCurrentStatus(): string
    {
        return $this->currentStatus;
    }

    public function getStatusesMap(): array
    {
        return [
            self::STATUS_NEW => 'Новое',
            self::STATUS_UNDO => 'Отменено',
            self::STATUS_ACTIVE => 'В работе',
            self::STATUS_DONE => 'Выполнено',
            self::STATUS_FAIL => 'Провалено'
        ];
    }

    public function getActionsMap(): array
    {
        return [
            self::ACTION_CANCEL => 'Заказчик отменил задание',
            self::ACTION_START => 'Заказчик выбрал исполнителя для задания',
            self::ACTION_FINISH => 'Заказчик отметил задание как выполненное',
            self::ACTION_REJECT => 'Исполнитель отказался от выполнения задания',
            self::ACTION_REACT => 'Исполнитель откликнулся на задание'
        ];
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
        $statuses = $this->getStatusesMap();
        $actions = $this->getActionsMap();

        // задача инициализируется со статусом Новая
        unset($statuses[$this::STATUS_NEW]);

        // у задачи не предусмотрена смена статуса, когда исполнитель создает отклик
        unset($actions[$this::ACTION_REACT]);

        if (!array_key_exists($action, $actions)) {
            throw new \Exception('Действие не предусмотрено в системе');
        }

        $actionKey = array_search($action, array_keys($actions));

        return $this->currentStatus = array_keys($statuses)[$actionKey];
    }
}
