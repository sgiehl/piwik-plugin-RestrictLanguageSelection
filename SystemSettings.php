<?php
/**
 * Piwik - Open source web analytics
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */
namespace Piwik\Plugins\RestrictLanguageSelection;

use Piwik\Piwik;
use Piwik\Plugins\LanguagesManager\API;
use Piwik\Settings\Setting;
use Piwik\Settings\FieldConfig;

/**
 * Defines Settings for ActivityLog.
 */
class SystemSettings extends \Piwik\Settings\Plugin\SystemSettings
{
    /** @var Setting */
    public $availableLanguages;

    protected function init()
    {
        $this->availableLanguages = $this->createAvailableLanguagesSetting();
    }

    private function createAvailableLanguagesSetting()
    {
        return $this->makeSetting('availableLanguages', $default = false, FieldConfig::TYPE_ARRAY, function (FieldConfig $field) {

            $languages = array();

            RestrictLanguageSelection::$disableRestrictions = true;
            $api = new API(); // not using getInstance as that would hold a precached, already resticted language list
            $languageInfos = $api->getAvailableLanguagesInfo();
            RestrictLanguageSelection::$disableRestrictions = false;

            foreach ($languageInfos as $languageInfo) {
                $languages[$languageInfo['code']] = $languageInfo['name'] . ' (' . $languageInfo['english_name'] . ')';
            }

            $field->title = Piwik::translate('RestrictLanguageSelection_RestrictLanguages');
            $field->uiControl = FieldConfig::UI_CONTROL_MULTI_SELECT;
            $field->availableValues = $languages;
        });
    }
}
