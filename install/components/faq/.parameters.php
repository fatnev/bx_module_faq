<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

// Подключение модуля Информационных блоков
if (!CModule::IncludeModule("iblock")) {
    return;
}

// Получение списка информационных блоков
$arIBlock = array();
$dbIBlock = CIBlock::GetList(Array("SORT" => "ASC"), Array("TYPE" => ($arCurrentValues["IBLOCK_TYPE"] != "-" ? $arCurrentValues["IBLOCK_TYPE"] : "")));
while ($arRes = $dbIBlock->Fetch())
{
    $arIBlock[$arRes["ID"]] = "[" . $arRes["ID"] . "] " . $arRes["NAME"];
}

$arComponentParameters = array(
    "PARAMETERS" => array(
        "IBLOCK_TYPE" => array(
            "PARENT" => "BASE",
            "NAME" => GetMessage("FAQ_IBLOCK_TYPE"),
            "TYPE" => "LIST",
            "VALUES" => CIBlockParameters::GetIBlockTypes(),
            "DEFAULT" => "content",
            "REFRESH" => "Y",
        ),
        "IBLOCK_ID" => array(
            "PARENT" => "BASE",
            "NAME" => GetMessage("FAQ_IBLOCK_ID"),
            "TYPE" => "LIST",
            "VALUES" => $arIBlock, 
            "DEFAULT" => '',
            "ADDITIONAL_VALUES" => "Y",
        ),
        "CACHE_TIME"  =>  array("DEFAULT" => 3600),
    ),
);
