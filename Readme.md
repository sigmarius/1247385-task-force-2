# Разработка онлайн площадки для поиска исполнителей на разовые задачи на Yii2 (шаблон приложения basic)

Сайт функционирует как биржа объявлений, где заказчики — физические лица публикуют задания. Исполнители могут откликаться на эти задания, предлагая свои услуги и стоимость работ.

### Использованы технологии: PHP, Yii2  
RBAC, Faker, Static methods, Basic template, Transactions, CRUD, Migrations, Active Record, Table input, Widgets, Routing, Logging, Transactions, Debug panel, Gii, Helpers, Behaviours, Models, Forms, DataProviders, Sessions, Error handling, Scenarios, Aliases, File uploading, Image thumbnails, Formatter, ListView, API Vk OAuth2 (AuthClient)

#### Сайт реализованного проекта: [TaskForce](https://taskforce.sigmarius.ru/ "Перейти на сайт")  

**Тестовый пользователь с ролью Заказчик**   
_email:_ client@test.ru  
_password:_ password_client

**Тестовый пользователь с ролью Исполитель**   
_email:_ worker@test.ru  
_password:_ password_worker

## Что сделано:  
- Проектирование архитектуры приложения, создание структуры базы данных, описание схемы процессов и классов;
- С помощью SplFileObject реализован перевод данных из CSV в SQL формат;
- Интегрирована верстка;
- Созданы Active Record модели, добавлены миграции;
- Выполнено первоначальное заполнение базы данных фикстурами с помощью Faker;
- Реализован механизм разделения пользователей по ролям Исполнитель/Заказчик с помощью RBAC;
- Реализовано добавление, просмотр заданий, и прием откликов исполнителей;
- Реализованы действия с заданиями по сценариям: Старт, Отмена, Отказ, Завершение;
- Реализован роутинг, формы и валидация используемых форм, использованы виджеты Yii2 для работы с данными ListView, ActiveForm, Menu, DateTimePicker;
- Реализована регистрация, авторизация, управление профилем пользователя, авторизация через Vk средствами OAuth2 и AuthClient;
- Реализована географическая привязка через интеграцию с API-сервисами Геокодер, Яндекс Карты, autoComplete.js;
- Проект развернут на поддомене **taskforce.sigmarius.ru**

***
*Учебный проект по курсу HTML Academy [PHP. Архитектура сложных веб-сервисов](https://htmlacademy.ru/)*