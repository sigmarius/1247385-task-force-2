<?php
require_once 'task.php';

$task = new Task(0, 0);

echo '<pre>';
$actionsNew = $task->getAvailableActions('new');
if (!in_array('workerAccept', $actionsNew) || !in_array('clientCancel', $actionsNew)) {
    echo "Ожидается, что из статуса new можно перейти в статусы workerAccept|clientCancel, а на самом деле " . implode(', ', $actionsNew);
}

$actionsActive = $task->getAvailableActions('active');
if (!in_array('clientApprove', $actionsActive) || !in_array('workerReject', $actionsActive)) {
    echo "Ожидается, что из статуса active можно перейти в статусы clientApprove|workerReject, а на самом деле " . implode(', ', $actionsActive);
}

$statusNew = $task->getCurrentStatus('clientCreate');
if ($statusNew !== 'new') {
    echo "Действию clientCreate должен соответствовать статус new, а не $statusNew";
}

$statusUndo = $task->getCurrentStatus('clientCancel');
if ($statusUndo !== 'undo') {
    echo "Действию clientCreate должен соответствовать статус undo, а не $statusUndo";
}

$statusActive = $task->getCurrentStatus('workerAccept');
if ($statusActive !== 'active') {
    echo "Действию workerAccept должен соответствовать статус active, а не $statusActive";
}

$statusDone = $task->getCurrentStatus('clientApprove');
if ($statusDone !== 'done') {
    echo "Действию clientApprove должен соответствовать статус done, а не $statusDone";
}

$statusFail = $task->getCurrentStatus('workerReject');
if ($statusFail !== 'fail') {
    echo "Действию workerReject должен соответствовать статус fail, а не $statusFail";
}

echo '</pre>';
