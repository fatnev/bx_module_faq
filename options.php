<?php
use Bitrix\Main\Loader;
use Bitrix\Main\Config\Option;

// Проверка прав администратора
if (!$USER->IsAdmin()) {
    return;
}

// Подключаем языковой файл и модуль инфоблоков
IncludeModuleLangFile(__FILE__);
Loader::includeModule('iblock');

// Получаем типы инфоблоков
$arIBlockTypes = CIBlockParameters::GetIBlockTypes();
$arIBlocks = array();

foreach ($arIBlockTypes as $iblockTypeCode => $iblockTypeName) {
    $rsIBlocks = CIBlock::GetList(
        array("SORT" => "ASC"),
        array("TYPE" => $iblockTypeCode, "ACTIVE" => "Y")
    );
    while ($arIBlock = $rsIBlocks->Fetch()) {
        $arIBlocks[$iblockTypeCode][$arIBlock["ID"]] = $arIBlock["NAME"];
    }
}

// Определяем вкладки для формы
$aTabs = array(
    array("DIV" => "edit1", "TAB" => GetMessage('TAB_SETTINGS'), "ICON" => "", "TITLE" => GetMessage('TAB_TITLE_SETTINGS')),
    array("DIV" => "edit2", "TAB" => GetMessage('TAB_ELEMENTS'), "ICON" => "", "TITLE" => GetMessage('TAB_TITLE_ELEMENTS')),
);

$tabControl = new CAdminTabControl("tabControl", $aTabs);

// Обработка сохранения данных при отправке формы
if ($_SERVER["REQUEST_METHOD"] == "POST" && check_bitrix_sessid()) {
    if (isset($_POST["save"]) && $_POST["save"] == "Y") {
        // Сохраняем параметры в настройках модуля
        COption::SetOptionString("faq", "IBLOCK_TYPE", $_POST["IBLOCK_TYPE"]);
        COption::SetOptionInt("faq", "IBLOCK_ID", $_POST["IBLOCK_ID"]);
    }
}

// Получаем текущие значения опций для отображения в форме
$iblockType = COption::GetOptionString("faq", "IBLOCK_TYPE", "");
$iblockId = COption::GetOptionInt("faq", "IBLOCK_ID", 0);

// Получение элементов инфоблока
$arElements = array();
if ($iblockId > 0) {
    $rsElements = CIBlockElement::GetList(
        array("SORT" => "ASC"),
        array("IBLOCK_ID" => $iblockId, "ACTIVE" => "Y"),
        false,
        false,
        array("ID", "NAME")
    );
    while ($arElement = $rsElements->Fetch()) {
        $arElements[] = $arElement;
    }
}

// Начинаем вывод HTML
?>
<form method="post" action="<?php echo $APPLICATION->GetCurPage()?>?mid=<?=htmlspecialcharsbx($mid)?>&amp;lang=<?=LANGUAGE_ID?>">
    <?php
    echo bitrix_sessid_post();
    $tabControl->Begin();
    
    // Первая вкладка - выбор инфоблока
    $tabControl->BeginNextTab();
    ?>
    <tr>
        <td width="40%"><?php echo GetMessage("IBLOCK_TYPE"); ?>:</td>
        <td width="60%">
            <select name="IBLOCK_TYPE" onchange="this.form.submit()">
                <option value=""><?php echo GetMessage("SELECT_IBLOCK_TYPE"); ?></option>
                <?php foreach ($arIBlockTypes as $typeCode => $typeName) { ?>
                    <option value="<?php echo $typeCode; ?>" <?php echo ($typeCode == htmlspecialcharsbx($iblockType)) ? 'selected' : ''; ?>><?php echo htmlspecialcharsbx($typeName); ?></option>
                <?php } ?>
            </select>
        </td>
    </tr>
    <tr>
        <td width="40%"><?php echo GetMessage("IBLOCK_ID"); ?>:</td>
        <td width="60%">
            <select name="IBLOCK_ID" onchange="this.form.submit()">
                <option value=""><?php echo GetMessage("SELECT_IBLOCK_ID"); ?></option>
                <?php if (isset($arIBlocks[$iblockType])) { ?>
                    <?php foreach ($arIBlocks[$iblockType] as $iblockIdOption => $iblockName) { ?>
                        <option value="<?php echo $iblockIdOption; ?>" <?php echo ($iblockIdOption == htmlspecialcharsbx($iblockId)) ? 'selected' : ''; ?>><?php echo htmlspecialcharsbx($iblockName); ?></option>
                    <?php } ?>
                <?php } ?>
            </select>
        </td>
    </tr>
    <?php

    // Вторая вкладка - элементы инфоблока
    $tabControl->BeginNextTab();
    ?>
    <tr>
        <td colspan="2">
            <table class="adm-list-table">
                <thead>
                <tr class="adm-list-table-header">
                    <td class="adm-list-table-cell"><div class="adm-list-table-cell-inner"><?php echo GetMessage("ELEMENT_ID"); ?></div></td>
                    <td class="adm-list-table-cell"><div class="adm-list-table-cell-inner"><?php echo GetMessage("ELEMENT_NAME"); ?></div></td>
                </tr>
                </thead>
                <tbody>
                <?php if ($iblockId > 0 && !empty($arElements)) { ?>
                    <?php foreach ($arElements as $element) { ?>
                        <tr>
                            <td class="adm-list-table-cell"><?php echo htmlspecialcharsbx($element["ID"]); ?></td>
                            <td class="adm-list-table-cell"><a href="/bitrix/admin/iblock_element_edit.php?IBLOCK_ID=<?php echo $iblockId; ?>&type=<?php echo $iblockType; ?>&ID=<?php echo $element["ID"]; ?>&lang=<?php echo LANGUAGE_ID; ?>&find_section_section=0"><?php echo htmlspecialcharsbx($element["NAME"]); ?></a></td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td class="adm-list-table-cell" colspan="2"><?php echo GetMessage("NO_ELEMENTS_FOUND"); ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </td>
    </tr>
    <?php

    $tabControl->Buttons();
    ?>
    <input type="submit" name="save" value="<?php echo GetMessage("SAVE_OPTIONS"); ?>" class="adm-btn-save">
    <input type="hidden" name="save" value="Y">
    <?php
    $tabControl->End();
    ?>
</form>