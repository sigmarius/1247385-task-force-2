<?php
class Task
{
    protected $clientId;
    protected $workerId;

    protected $currentStatus;

    const STATUS_NEW = 'new';
    const STATUS_UNDO = 'undo';
    const STATUS_ACTIVE = 'active';
    const STATUS_DONE = 'done';
    const STATUS_FAIL = 'fail';

    const ACTION_CREATE = 'clientCreate';
    const ACTION_CANCEL = 'clientCancel';
    const ACTION_ACCEPT = 'workerAccept';
    const ACTION_APPROVE = 'clientApprove';
    const ACTION_REJECT = 'workerReject';

    public function __construct(int $customerId, int $workerId)
    {
        $this->customerId = $customerId;
        $this->workerId = $workerId;
    }

    public function getStatusesMap()
    {
        return [
            self::STATUS_NEW => 'Новое',
            self::STATUS_UNDO => 'Отменено',
            self::STATUS_ACTIVE => 'В работе',
            self::STATUS_DONE => 'Выполнено',
            self::STATUS_FAIL => 'Провалено'
        ];
    }

    public function getActionsMap()
    {
        return [
            self::ACTION_CREATE => 'Задание опубликовано, исполнитель ещё не найден',
            self::ACTION_CANCEL => 'Заказчик отменил задание', 
            self::ACTION_ACCEPT => 'Заказчик выбрал исполнителя для задания', 
            self::ACTION_APPROVE => 'Заказчик отметил задание как выполненное', 
            self::ACTION_REJECT => 'Исполнитель отказался от выполнения задания'
        ];
    }

    public function getAvailableActions(string $status)
    {
        switch ($status) {
            case 'new':
                return [self::ACTION_CANCEL, self::ACTION_ACCEPT];
            case 'active':
                return [self::ACTION_APPROVE, self::ACTION_REJECT];
            default:
                throw new Exception('Для данного статуса доступных действий не предусмотрено');
        }
    }

    public function getCurrentStatus(string $action)
    {
        $statuses = $this->getStatusesMap();
        $actions = $this->getActionsMap();

        if (!array_key_exists($action, $actions)) {
            throw new Exception('Действие не предусмотрено в системе');
        }

        $actionKey = array_search($action, array_keys($actions));

        return $this->currentStatus = array_keys($statuses)[$actionKey]; 
    }
}
