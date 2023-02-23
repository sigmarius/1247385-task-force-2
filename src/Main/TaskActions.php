<?php

namespace Taskforce\Main;

class TaskActions
{
    const ACTION_CANCEL = 'clientCancel';
    const ACTION_START = 'clientStart';
    const ACTION_FINISH = 'clientFinish';
    const ACTION_REJECT = 'workerReject';
    const ACTION_REACT = 'workerReact';

    public static function getActionsMap(): array
    {
        return [
            self::ACTION_CANCEL => 'Заказчик отменил задание',
            self::ACTION_START => 'Заказчик выбрал исполнителя для задания',
            self::ACTION_FINISH => 'Заказчик отметил задание как выполненное',
            self::ACTION_REJECT => 'Исполнитель отказался от выполнения задания',
            self::ACTION_REACT => 'Исполнитель откликнулся на задание'
        ];
    }
}