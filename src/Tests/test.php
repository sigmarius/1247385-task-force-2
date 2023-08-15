<?php
use Taskforce\Service\Task\TaskService;
use Taskforce\Service\Actions\CancelAction;
use Taskforce\Service\Actions\ReactAction;
use Taskforce\Service\Actions\StartAction;
use Taskforce\Service\Actions\FinishAction;
use Taskforce\Service\Actions\RejectAction;
use Taskforce\Exceptions\TaskException;

$userRole = \Yii::$app->user->can('worker') ? 'worker' : 'client';

echo 'user id: ' . \Yii::$app->user->identity->id . '<br>';
echo 'user role: ' . $userRole . '<br>';

function checkStatusNew($userRole) {
    $taskId = 63;
    $task = new TaskService($taskId);
    $actions = array_column($task->getAvailableActions(), 'code');

    if ($userRole === 'worker') {
        if ([
            (new ReactAction())->getAvailableActions()['code']
            ] !== $actions) {
            echo "Ожидается, что из статуса new для исполнителя доступны только действия ActionReact<br>";
        }
    } else {
        if ([
                (new CancelAction())->getAvailableActions()['code'],
                ( new StartAction())->getAvailableActions()['code']
            ] !== $actions) {
            echo "Ожидается, что из статуса new для заказчика доступны только действия clientCancel и clientStart <br>";
        }
    }
}

function checkStatusUndo($userRole) {
    $taskId = 21;
    $task = new TaskService($taskId);

    $status = $task->setTaskStatus('clientCancel');
    if ($status !== 'undo') {
        echo "Действию clientCancel должен соответствовать статус undo, а не $status";
    }

    $actions = array_column($task->getAvailableActions(), 'code');

    if ($userRole === 'worker') {
        if ([] != $actions) {
            echo "Ожидается, что из статуса undo для исполнителя нет доступных действий";
        }
    } else {
        if ([] !== $actions) {
            echo "Ожидается, что из статуса undo для заказчика нет доступных действий";
        }
    }
}

function checkStatusActive($userRole) {
    $taskId = 10;
    $task = new TaskService($taskId);

    $status = $task->setTaskStatus('clientStart');
    if ($status !== 'active') {
        echo "Действию clientStart должен соответствовать статус active, а не $status";
    }

    $actions = array_column($task->getAvailableActions(), 'code');

    if ($userRole === 'worker') {
        if ([
                (new RejectAction())->getAvailableActions()['code']
            ] !== $actions) {
            echo "Ожидается, что из статуса active для исполнителя доступны только действия ActionReject<br>";
        }
    } else {
        if ([
                (new FinishAction())->getAvailableActions()['code']
            ] !== $actions) {
            echo "Ожидается, что из статуса active для заказчика доступны только действия clientFinish <br>";
        }
    }
}

function checkStatusDone($userRole) {
    $taskId = 2;
    $task = new TaskService($taskId);

    $status = $task->setTaskStatus('clientFinish');
    if ($status !== 'done') {
        echo "Действию clientFinish должен соответствовать статус done, а не $status";
    }

    $actions = array_column($task->getAvailableActions(), 'code');

    if ($userRole === 'worker') {
        if ([] !== $actions) {
            echo "Ожидается, что из статуса done для исполнителя нет доступных действий <br>";
        }
    } else {
        if ([] !== $actions) {
            echo "Ожидается, что из статуса done для заказчика нет доступных действий <br>";
        }
    }
}

function checkStatusFail($userRole) {
    $taskId = 5;
    $task = new TaskService($taskId);

    $status = $task->setTaskStatus('workerReject');
    if ($status !== 'fail') {
        echo "Действию workerReject должен соответствовать статус fail, а не $status";
    }

    $actions = array_column($task->getAvailableActions(), 'code');

    if ($userRole === 'worker') {
        if ([] !== $actions) {
            echo "Ожидается, что из статуса fail для исполнителя нет доступных действий <br>";
        }
    } else {
        if ([] !== $actions) {
            echo "Ожидается, что из статуса fail для заказчика нет доступных действий <br>";
        }
    }
}

function checkStatusException() {
    $taskId = 63;
    $task = new TaskService($taskId);

    try {
        $task->setTaskStatus(['Some undefined action']);
    } catch (TaskException $e) {
        echo 'Неизвестное действие: ' . $e->getMessage() . "\n" . $e->getTraceAsString();
    } catch (TypeError $e) {
        echo 'Неправильно задан тип аргумента: ' . $e->getMessage() . "\n" . $e->getTraceAsString();
    }
}

echo '<pre>';
checkStatusNew($userRole);
checkStatusUndo($userRole);
checkStatusActive($userRole);
checkStatusDone($userRole);
checkStatusFail($userRole);
//checkStatusException();
echo '</pre>';
