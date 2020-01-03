<?php

return [
    'tables' => [
        'posts' => [
            'fields' => [
                'slug' => 'Символьный код',
                'title' => 'Название статьи',
                'description' => 'Краткое описание',
                'body' => 'Текст статьи',
                'published' => 'Опубликована',
            ],
            'name' => 'Статьи',
        ],
        'informations' => [
            'name' => 'Новости',
        ],
        'tags' => [
            'name' => 'Тэги',
        ],
        'comments' => [
            'name' => 'Комментарии',
        ],
        'users' => [
            'name' => 'Пользователи',
        ],
    ],
];