<?php
require_once 'task.php';

$task = new Task(0, 0);

echo '<pre>';

var_dump(
    'Test Current Status',
    $task->getCurrentStatus('workerAccept'),
    $task->getCurrentStatus(123),
    "\n\nTest Available Actions",
    $task->getAvailableActions('new'),
    $task->getAvailableActions('done'),
    $task->getAvailableActions(123),
    "\n\nTest Maps",
    $task->getStatusesMap(),
    $task->getActionsMap(),
);

echo '</pre>';
