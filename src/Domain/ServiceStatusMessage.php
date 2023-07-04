<?php

namespace PriNikApp\FrontTest\Domain;

enum ServiceStatusMessage: string
{
    case SerialExists = 'Такой серийный номер уже существует.';
    case SerialNotSaved = 'Ошибка. Серийный номер не сохранен.';
    case SerialAdded = 'Серийный номер успешно добавлен.';
    case SerialDeleted = 'Серийный номер успешно удален.';
    case SerialNotDeleted = 'Серийный номер не удалось удалить, попробуйте ещё раз.';
    case UserNotSelected = 'Пользователь не выбран.';
    case AccessNotUpdated = 'Права не были обновлены.';
    case AccessUpdated = 'Права успешно обновлены.';
    case NeedConnection = 'Требуется подключение к монитору';
}
