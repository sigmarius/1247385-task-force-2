<?php
use Taskforce\Main\Task;
use Taskforce\Service\Actions\CancelAction;
use Taskforce\Service\Actions\ReactAction;
use Taskforce\Service\Actions\StartAction;
use Taskforce\Service\Actions\FinishAction;
use Taskforce\Service\Actions\RejectAction;
use Taskforce\Exceptions\TaskException;

function checkStatusNew() {
    // проверка со стороны клиента
    $userId = 1;
    $clientId = 1;
    $task = new Task($clientId); // создали задачу со статусом new и workerId = 0 по дефолту

    $actions = $task->getAvailableActions($userId);
    if ([new CancelAction(), new StartAction()] != $actions) {
        echo "Ожидается, что из статуса new для заказчика доступны только действия clientCancel и clientStart";
    }

    // проверка со стороны исполнителя
    $userId = 2;
    $workerId = 2;
    $task = new Task($clientId, $workerId); // создали задачу со статусом new по дефолту

    $actions = $task->getAvailableActions($userId);
    if ([new ReactAction()] != $actions) {
        echo "Ожидается, что из статуса new для исполнителя доступны только действия ActionReact";
    }
}

function checkStatusUndo() {
    // проверка со стороны клиента
    $userId = 1;
    $clientId = 1;
    $task = new Task($clientId); // создали задачу со статусом new и workerId = 0 по дефолту

    $status = $task->setCurrentStatus('clientCancel');
    if ($status !== 'undo') {
        echo "Действию clientCancel должен соответствовать статус undo, а не $status";
    }

    $actions = $task->getAvailableActions($userId);
    if ([] != $actions) {
        echo "Ожидается, что из статуса undo для заказчика нет доступных действий";
    }

    // проверка со стороны исполнителя
    $userId = 2;
    $workerId = 2;
    $task = new Task($clientId, $workerId); // создали задачу со статусом new по дефолту

    $status = $task->setCurrentStatus('clientCancel');
    if ($status !== 'undo') {
        echo "Действию clientCancel должен соответствовать статус undo, а не $status";
    }

    $actions = $task->getAvailableActions($userId);
    if ([] != $actions) {
        echo "Ожидается, что из статуса undo для исполнителя нет доступных действий";
    }
}

function checkStatusActive() {
    // проверка со стороны клиента
    $userId = 1;
    $clientId = 1;
    $task = new Task($clientId); // создали задачу со статусом new и workerId = 0 по дефолту

    $status = $task->setCurrentStatus('clientStart');
    if ($status !== 'active') {
        echo "Действию clientStart должен соответствовать статус active, а не $status";
    }

    $actions = $task->getAvailableActions($userId);
    if ([new FinishAction()] != $actions) {
        echo "Ожидается, что из статуса active для заказчика доступны только действия clientFinish";
    }

    // проверка со стороны исполнителя
    $userId = 2;
    $workerId = 2;
    $task = new Task($clientId, $workerId); // создали задачу со статусом new по дефолту

    $status = $task->setCurrentStatus('clientStart');
    if ($status !== 'active') {
        echo "Действию clientStart должен соответствовать статус active, а не $status";
    }

    $actions = $task->getAvailableActions($userId);
    if ([new RejectAction()] != $actions) {
        echo "Ожидается, что из статуса active для исполнителя доступны только действия ActionReject";
    }
}

function checkStatusDone() {
    // проверка со стороны клиента
    $userId = 1;
    $clientId = 1;
    $task = new Task($clientId); // создали задачу со статусом new и workerId = 0 по дефолту

    $status = $task->setCurrentStatus('clientFinish');
    if ($status !== 'done') {
        echo "Действию clientFinish должен соответствовать статус done, а не $status";
    }

    $actions = $task->getAvailableActions($userId);
    if ([] != $actions) {
        echo "Ожидается, что из статуса done для заказчика нет доступных действий";
    }

    // проверка со стороны исполнителя
    $userId = 2;
    $workerId = 2;
    $task = new Task($clientId, $workerId); // создали задачу со статусом new по дефолту

    $status = $task->setCurrentStatus('clientFinish');
    if ($status !== 'done') {
        echo "Действию clientFinish должен соответствовать статус undo, а не $status";
    }

    $actions = $task->getAvailableActions($userId);
    if ([] != $actions) {
        echo "Ожидается, что из статуса done для исполнителя нет доступных действий";
    }
}

function checkStatusFail() {
    // проверка со стороны клиента
    $userId = 1;
    $clientId = 1;
    $task = new Task($clientId); // создали задачу со статусом new и workerId = 0 по дефолту

    $status = $task->setCurrentStatus('workerReject');
    if ($status !== 'fail') {
        echo "Действию workerReject должен соответствовать статус fail, а не $status";
    }

    $actions = $task->getAvailableActions($userId);
    if ([] != $actions) {
        echo "Ожидается, что из статуса fail для заказчика нет доступных действий";
    }

    // проверка со стороны исполнителя
    $userId = 2;
    $workerId = 2;
    $task = new Task($clientId, $workerId); // создали задачу со статусом new по дефолту

    $status = $task->setCurrentStatus('workerReject');
    if ($status !== 'fail') {
        echo "Действию workerReject должен соответствовать статус fail, а не $status";
    }

    $actions = $task->getAvailableActions($userId);
    if ([] != $actions) {
        echo "Ожидается, что из статуса fail для исполнителя нет доступных действий";
    }
}

function checkStatusException() {
    $clientId = 1;
    $task = new Task($clientId); // создали задачу со статусом new и workerId = 0 по дефолту

    try {
        $task->setCurrentStatus(['Some undefined action']);
    } catch (TaskException $e) {
        error_log('Неизвестное действие: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
    } catch (TypeError $e) {
        error_log('Неправильно задан тип аргумента: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
    }
}

echo '<pre>';
checkStatusNew();
checkStatusUndo();
checkStatusActive();
checkStatusDone();
checkStatusFail();
checkStatusException();
echo '</pre>';
