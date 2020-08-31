<?php
/**
 * Matomo - Open source web analytics
 *
 * @link https://matomo.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */
namespace Piwik\Plugins\RestrictLanguageSelection;

use Piwik\Piwik;
use Piwik\Plugins\LanguagesManager\API;
use Piwik\Settings\Setting;
use Piwik\Settings\FieldConfig;

/**
 * Defines Settings for RestrictLanguageSelection.
 */
class SystemSettings extends \Piwik\Settings\Plugin\SystemSettings
{
    /** @var Setting */
    public $availableLanguages;

    /** @var Setting */
    public $useRestriction;

    protected function init()
    {
        $this->useRestriction = $this->makeSetting('useLanguageRestriction', false, FieldConfig::TYPE_BOOL, function (FieldConfig $field) {
            $field->title = Piwik::translate('RestrictLanguageSelection_UseRestriction');
            $field->uiControl = FieldConfig::UI_CONTROL_CHECKBOX;
        });
        $this->availableLanguages = $this->createAvailableLanguagesSetting();
    }

    private function createAvailableLanguagesSetting()
    {
        return $this->makeSetting('availableLanguages', $default = [], FieldConfig::TYPE_ARRAY, function (FieldConfig $field) {

            $languages = array();

            RestrictLanguageSelection::$disableRestrictions = true;
            $api = new API(); // not using getInstance as that would hold a precached, already restricted language list
            $languageInfos = $api->getAvailableLanguagesInfo();
            RestrictLanguageSelection::$disableRestrictions = false;

            foreach ($languageInfos as $languageInfo) {
                $languages[$languageInfo['code']] = $languageInfo['name'] . ' (' . $languageInfo['english_name'] . ')';
            }

            $field->title = Piwik::translate('RestrictLanguageSelection_RestrictLanguages');
            $field->uiControl = FieldConfig::UI_CONTROL_MULTI_SELECT;
            $field->availableValues = $languages;
            $field->condition = 'useLanguageRestriction==1';
        });
    }
}
