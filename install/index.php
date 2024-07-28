<?php

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Loader;
use Bitrix\Main\ModuleManager;
use Bitrix\Iblock;
use Bitrix\Main\Application;
use Bitrix\Main\IO\Directory;

Loc::loadMessages(__FILE__);

class faq extends CModule
{
    public $MODULE_ID = 'faq';
    public $MODULE_VERSION;
    public $MODULE_VERSION_DATE;
    public $MODULE_NAME;
    public $MODULE_DESCRIPTION;

    public function __construct()
    {
        include(__DIR__ . '/version.php');
        $this->MODULE_VERSION = $arModuleVersion['VERSION'];
        $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        $this->MODULE_NAME = "FAQ Module";
        $this->MODULE_DESCRIPTION = "Модуль для управления часто задаваемыми вопросами";
        $this->PARTNER_NAME = "Георгий";
        $this->PARTNER_URI = "https://gnvs.ru";
    }

    public function DoInstall()
    {
        global $APPLICATION;

        ModuleManager::registerModule($this->MODULE_ID);

        $this->installIblockType();
        $this->installIblock();
        $this->InstallFiles();
        
        // Вывод сообщения после установки модуля
        $APPLICATION->IncludeAdminFile(
            'Установка модуля ' . $this->MODULE_NAME,
            __DIR__ . '/step.php'
        );     
    }

    public function DoUninstall()
    {
        global $APPLICATION;

        ModuleManager::unRegisterModule($this->MODULE_ID);

        $this->uninstallIblock();
        $this->uninstallIblockType();
        $this->UnInstallFiles();

        $APPLICATION->IncludeAdminFile(
            'Удаление модуля ' . $this->MODULE_NAME,
            __DIR__ . '/unstep.php'
        ); 
    }

    public function InstallFiles()
    {
        $sourceDir = __DIR__ . "/components";
        $destDir = Application::getDocumentRoot() . "/local/components/";

        if (Directory::isDirectoryExists($sourceDir)) {
            // Папка назначения может не существовать, создадим её
            if (!Directory::isDirectoryExists($destDir)) {
                Directory::createDirectory($destDir);
            }
            
            $result = CopyDirFiles(
                $sourceDir,
                $destDir,
                true,
                true
            );

            if (!$result) {
                echo Loc::getMessage("FAQ_INSTALL_FILES_ERROR");
            }
        } else {
            echo Loc::getMessage("FAQ_INSTALL_NO_SOURCE_DIR");
        }
    }


    private function installIblockType()
    {
        if (!Loader::includeModule('iblock')) {
            return false;
        }

        $arFields = [
            'ID' => 'faq_iblock_type',
            'SECTIONS' => 'Y',
            'IN_RSS' => 'N',
            'SORT' => 100,
            'LANG' => [
                'ru' => [
                    'NAME' => 'FAQ',
                    'SECTION_NAME' => 'Разделы',
                    'ELEMENT_NAME' => 'Вопрос'
                ],
                'en' => [
                    'NAME' => 'FAQ',
                    'SECTION_NAME' => 'Sections',
                    'ELEMENT_NAME' => 'Question'
                ],
            ],
        ];

        $obBlockType = new CIBlockType;
        $DB = Application::getConnection();

        $result = $DB->query(sprintf(
            "SELECT * FROM b_iblock_type WHERE ID='%s'",
            $arFields['ID']
        ))->fetch();

        if (!$result) {
            $DB->startTransaction();

            if (!$obBlockType->Add($arFields)) {
                $DB->rollbackTransaction();
                global $APPLICATION;
                $APPLICATION->ThrowException($obBlockType->LAST_ERROR);
                return false;
            } else {
                $DB->commitTransaction();
            }
        }

        return true;
    }

    private function installIblock()
    {
        if (!Loader::includeModule('iblock')) {
            return false;
        }

        $ibType = 'faq_iblock_type';
        $ibCode = 'faq_iblock';
        $ibName = 'FAQ';

        $arFields = [
            'ACTIVE' => 'Y',
            'IBLOCK_TYPE_ID' => $ibType,
            'NAME' => $ibName,
            'CODE' => $ibCode,
            'SITE_ID' => ['s1'],
            'SORT' => 500,
            'GROUP_ID' => ['2' => 'R'],
        ];

        $iblock = new CIBlock;
        $DB = Application::getConnection();

        $result = $DB->query(sprintf(
            "SELECT * FROM b_iblock WHERE IBLOCK_TYPE_ID='%s' AND CODE='%s'",
            $ibType, $ibCode
        ))->fetch();

        if (!$result) {
            $DB->startTransaction();

            $iblockID = $iblock->Add($arFields);

            if (!$iblockID) {
                $DB->rollbackTransaction();
                global $APPLICATION;
                $APPLICATION->ThrowException($iblock->LAST_ERROR);
                return false;
            } else {
                $DB->commitTransaction();
            }
        }

        return true;
    }

    private function uninstallIblockType()
    {
        if (!Loader::includeModule('iblock')) {
            return false;
        }

        $typeID = 'faq_iblock_type';
        $DB = Application::getConnection();

        $DB->startTransaction();

        if (!CIBlockType::Delete($typeID)) {
            $DB->rollbackTransaction();
            global $APPLICATION;
            $APPLICATION->ThrowException("Ошибка: невозможно удалить тип инфоблока.");
            return false;
        } else {
            $DB->commitTransaction();
        }

        return true;
    }

    public function UnInstallFiles()
    {
        Directory::deleteDirectory(Application::getDocumentRoot() . "/local/components/");
    }

    private function uninstallIblock()
    {
        if (!Loader::includeModule('iblock')) {
            return false;
        }

        $ibType = 'faq_iblock_type';
        $ibCode = 'faq_iblock';

        $res = CIBlock::GetList(
            [],
            ['=TYPE' => $ibType, '=CODE' => $ibCode]
        );

        while ($arIBlock = $res->Fetch()) {
            CIBlock::Delete($arIBlock['ID']);
        }

        return true;
    }
}
