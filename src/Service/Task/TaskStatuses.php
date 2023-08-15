<?php

namespace Taskforce\Service\Task;

class TaskStatuses
{
    const STATUS_UNDO = 'undo';
    const STATUS_ACTIVE = 'active';
    const STATUS_DONE = 'done';
    const STATUS_FAIL = 'fail';
    const STATUS_NEW = 'new';

    public static function getStatusesMap(): array
    {
        return [
            self::STATUS_NEW => 'Новое',
            self::STATUS_UNDO => 'Отменено',
            self::STATUS_ACTIVE => 'В работе',
            self::STATUS_DONE => 'Выполнено',
            self::STATUS_FAIL => 'Провалено'
        ];
    }
}
