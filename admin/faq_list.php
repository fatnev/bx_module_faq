<?php

require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_before.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/local/modules/faq/include.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/local/modules/faq/prolog.php");

$APPLICATION->SetTitle("Список FAQ");

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");

// Получение настройки инфоблока
$iblockId = COption::GetOptionString("faq", "iblock_id");
if (!$iblockId) {
    ShowError("ID инфоблока не задан в настройках модуля.");
    require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php");
    die();
}

// Подключаем модуль информационных блоков
if (!CModule::IncludeModule("iblock")) {
    ShowError("Модуль инфоблоков не подключен.");
    require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php");
    die();
}

// Получаем список элементов инфоблока
$arSelect = Array("ID", "NAME", "DATE_ACTIVE_FROM");
$arFilter = Array("IBLOCK_ID" => $iblockId, "ACTIVE" => "Y");
$res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize" => 50), $arSelect);

?>

<table class="adm-list-table">
    <thead>
    <tr class="adm-list-table-header">
        <th class="adm-list-table-cell"><div class="adm-list-table-cell-inner">ID</div></th>
        <th class="adm-list-table-cell"><div class="adm-list-table-cell-inner">Название</div></th>
        <th class="adm-list-table-cell"><div class="adm-list-table-cell-inner">Дата активности</div></th>
    </tr>
    </thead>
    <tbody>
    <?php while ($ob = $res->GetNextElement()): ?>
        <?php $arFields = $ob->GetFields(); ?>
        <tr class="adm-list-table-row">
            <td class="adm-list-table-cell"><?=$arFields["ID"]?></td>
            <td class="adm-list-table-cell"><?=$arFields["NAME"]?></td>
            <td class="adm-list-table-cell"><?=$arFields["DATE_ACTIVE_FROM"]?></td>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>

<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php");
?>