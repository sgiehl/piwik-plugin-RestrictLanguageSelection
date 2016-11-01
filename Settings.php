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
use Piwik\Settings\SystemSetting;

/**
 * Defines Settings for RestrictLanguageSelection.
 */
class Settings extends \Piwik\Plugin\Settings
{
    /** @var Setting */
    public $availableLanguages;

    protected function init()
    {
        $this->createAvailableLanguagesSetting();
    }

    private function createAvailableLanguagesSetting()
    {
        $languages = array();

        RestrictLanguageSelection::$disableRestrictions = true;
        $api = new API(); // not using getInstance as that would hold a precached, already resticted language list
        $languageInfos = $api->getAvailableLanguagesInfo();
        RestrictLanguageSelection::$disableRestrictions = false;

        foreach ($languageInfos as $languageInfo) {
            $languages[$languageInfo['code']] = $languageInfo['name'] . ' (' . $languageInfo['english_name'] . ')';
        }

        $this->availableLanguages = new SystemSetting('availableLanguages', Piwik::translate('RestrictLanguageSelection_RestrictLanguages'));
        $this->availableLanguages->readableByCurrentUser = true;
        $this->availableLanguages->type = static::TYPE_ARRAY;
        $this->availableLanguages->uiControlType = static::CONTROL_MULTI_SELECT;
        $this->availableLanguages->defaultValue  = false;
        $this->availableLanguages->availableValues = $languages;

        $this->addSetting($this->availableLanguages);
    }
}
