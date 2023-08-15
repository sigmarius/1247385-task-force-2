<?php
namespace Taskforce\Service\Actions;

use app\models\Tasks;
use Taskforce\Main\TaskService;

abstract class BaseAction
{
    protected string $actionName;
    protected string $actionCode;

    public function getActionName() : string
    {
        return $this->actionName;
    }

    public function getActionCode() : string
    {
        return $this->actionCode;
    }

    abstract public static function checkAccess(Tasks $task, int $userId) : bool;
}
