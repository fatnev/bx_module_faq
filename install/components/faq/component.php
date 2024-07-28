<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$arParams = array(
    "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
    "IBLOCK_ID" => intval($arParams["IBLOCK_ID"]),
    "CACHE_TIME" => intval($arParams["CACHE_TIME"]),
);

$APPLICATION->IncludeComponent(
    "faq",
    "",
    $arParams,
    false
);
?>