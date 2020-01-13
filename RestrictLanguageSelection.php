<?php
/**
 * Piwik - Open source web analytics
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */
namespace Piwik\Plugins\RestrictLanguageSelection;

use Piwik\Plugin;

/**
 *
 */
class RestrictLanguageSelection extends Plugin
{
    public static $disableRestrictions = false;

    /**
     * @see Piwik_Plugin::registerEvents
     */
    public function registerEvents()
    {
        return array(
            'LanguageManager.getAvailableLanguages' => 'modifyAvailableLanguages',
        );
    }

    public function modifyAvailableLanguages(&$languages)
    {
        if (self::$disableRestrictions) {
            return;
        }

        $setting = new SystemSettings();
        $availableLanguages = $setting->availableLanguages->getValue();
        if (!empty($availableLanguages)) {
            $languages = $availableLanguages;
        }
    }
}