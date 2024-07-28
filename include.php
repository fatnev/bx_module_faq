<?php

// Подключение файла настроек модуля
IncludeModuleLangFile(__FILE__);

$arModuleClasses = array(
    // Ваши классы модуля тут, если есть
);

CModule::AddAutoloadClasses("faq", $arModuleClasses);

if (IsModuleInstalled("faq")) {
    global $APPLICATION;
    $APPLICATION->AddPanelButton(
        array(
            "ID" => "faq_module_button",
            "TEXT" => "Управление FAQ",
            "MAIN_SORT" => 10000,
            "SORT" => 10,
            "HREF" => "/bitrix/admin/settings.php?mid=faq&lang=".LANGUAGE_ID,
            "ICON" => "bx-panel-site-speed",
            "TITLE" => "Управление настройки модуля FAQ",
        )
    );
}