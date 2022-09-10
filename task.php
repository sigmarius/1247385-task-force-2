<?php
class Task
{
    protected $clientId;
    protected $workerId;

    protected $currentStatus;

    private const STATUSES = [
        'new' => 'Новое',
        'undo' => 'Отменено',
        'active' => 'В работе',
        'done' => 'Выполнено',
        'fail' => 'Провалено'
    ];

    private const ACTIONS = [
        'clientCreate' => 'Задание опубликовано, исполнитель ещё не найден',
        'clientCancel' => 'Заказчик отменил задание', 
        'workerAccept' => 'Заказчик выбрал исполнителя для задания', 
        'clientApprove' => 'Заказчик отметил задание как выполненное', 
        'workerReject' => 'Исполнитель отказался от выполнения задания'
    ];

    public function __construct(int $customerId, int $workerId)
    {
        $this->customerId = $customerId;
        $this->workerId = $workerId;
    }

    public function getAvailableActions(string $status)
    {
        $availableActions = [];

        switch ($status) {
            case 'new':
                $availableActions = [
                    implode(array_keys(self::ACTIONS, self::ACTIONS['clientCancel'])), 
                    implode(array_keys(self::ACTIONS, self::ACTIONS['workerAccept']))
                ];
                break;
            case 'active':
                $availableActions = [
                    implode(array_keys(self::ACTIONS, self::ACTIONS['clientApprove'])), 
                    implode(array_keys(self::ACTIONS, self::ACTIONS['workerReject']))
                ];
                break;
            default:
            return 'Для данного статуса доступных действий не предусмотрено';
        }

        return $availableActions;
    }

    public function getCurrentStatus(string $action)
    {
        if (!array_key_exists($action, self::ACTIONS)) {
            return 'Действие не предусмотрено в системе';
        }

        $actionKey = array_search($action, array_keys(self::ACTIONS));

        $this->currentStatus = array_keys(self::STATUSES)[$actionKey];

        return $this->currentStatus;
    }

    public function getStatusesMap()
    {
        return self::STATUSES;
    }

    public function getActionsMap()
    {
        return self::ACTIONS;
    }
}
