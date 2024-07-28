# Готовый модуль FAQ (Вопрос-ответ) для Битрикс

Реализует простой функционал Вопрос-Ответ.
После установки модуля, создается новый тип инфоблоков FAQ и компонент с двумя шаблонами на выбор.

## Установка модуля

Чтобы установить модуль, необходимо предварительно его скопировать в /local/modules.
Получится /local/modules/faq.

Затем переходим в Панель администрирования -> Настройки -> Модули.
В списке модулей будет находится модуль FAQ Module - Модуль Часто задаваемые вопросы.
Необходимо нажать кнопку Установить.

После установки модуля, создается новый тип инфоблоков FAQ

После установки модуля, в разделе Панель администрирования -> Настройки -> Настройки модулей появится пункт FAQ Module.

### Как добавлять контент

Переходим в Панель администрирования -> Контент -> FAQ. Добавляем элемент.
Поля для элемента - это Название и в тексте анонса, это будет ответ.
Через настройки модуля можно посмотреть список всех созданных элементов и отредактировать. Также можно все редактировать стандартным способом через Панель администрирования -> Контент -> FAQ.

### Вывод контента с использованием шаблонов компонента FAQ

Переходим на фронтальную часть, создаем раздел, например Вопрос-ответ.
В визуальном редакторе, в списке компонентов нажимаем обновить - появится категория Вопрос-Ответ с компонентом FAQ.
Переносим компонент в рабочую область, затем в окне настроек выбираем тип инфоблока FAQ и инфблок FAQ.

На выбор в параметрах компонента представлены 2 шаблона: 
.default - просто выводит Вопрос - Ответ в статичном виде;
mini - выводит анимированный раскрывающийся список при клике на вопрос.

Данные шаблоны можно скопировать в свое пространство и кастомизировать на свое усмотрение.

