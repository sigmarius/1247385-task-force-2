<?php

return [
    'createTask' => [
        'type' => 2,
        'description' => 'Создание задачи',
    ],
    'client' => [
        'type' => 1,
        'description' => 'Заказчик',
        'children' => [
            'createTask',
        ],
    ],
    'worker' => [
        'type' => 1,
        'description' => 'Исполнитель',
    ],
    'admin' => [
        'type' => 1,
        'description' => 'Администратор',
        'children' => [
            'client',
            'worker',
        ],
    ],
];
