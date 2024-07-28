<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

class FaqComponent extends CBitrixComponent
{
    public function executeComponent()
    {
        if ($this->startResultCache()) {
            if (!CModule::IncludeModule("iblock")) {
                $this->abortResultCache();
                ShowError(GetMessage("Модуль FAQ не установлен!"));
                return;
            }

            $arFilter = array(
                "IBLOCK_ID" => $this->arParams["IBLOCK_ID"],
                "ACTIVE" => "Y"
            );

            $arSelect = array(
                "ID",
                "NAME",
                "PREVIEW_TEXT"
            );

            $res = CIBlockElement::GetList(array(), $arFilter, false, array(), $arSelect);

            while ($arItem = $res->GetNext()) {
                $this->arResult["ITEMS"][] = $arItem;
            }

            $this->includeComponentTemplate();
        }
    }
}
?>