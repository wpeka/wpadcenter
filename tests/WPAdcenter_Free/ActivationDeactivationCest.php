<?php
/**
 * Automation test cases for Activation and Deactivation page of WPAdcenterFree plugin
 * 
 * @category AutomationTests
 * @package  WordPress_WPAdcenter_Free_Plugin
 * @author   WPEKA <hello@wpeka.com>
 * @license  GPL v3
 * @link     https://club.wpeka.com
 */
use Facebook\WebDriver\WebDriverBy;
use Page\Acceptance\loginpage;
use Page\WPAdcenter_Free\ActivationDeactivation;
 
/**
 * Core class used for testcases of Activation and Deactivation page of WPAdcenterFree plugin
 * 
 * @category  AutomationTests
 * @package   WordPress_WPAdcenter_Free_Plugin
 * @author    WPEKA <hello@wpeka.com>
 * @copyright 2022 WPEKA
 * @license   GPL v3
 * @link      https://club.wpeka.com
 * 
 * @since 1.0
 */
class ActivationDeactivationCest
{
    /**
     * Test to check whether HelpUsImproveWpadcenterBanner is visible to user 
     * 
     * @param $I                      variable of WPAdcenter_FreeTester
     * @param $loginpage              Used to login and logout from the page.
     * @param $activationDeactivation consist of selectors.
     * 
     * @return void
     * 
     * @since 1.0
     */
    public function helpUsImproveWpadcenterBannerVisibilityTest(WPAdcenter_FreeTester $I, loginpage $loginpage, ActivationDeactivation $activationDeactivation)
    {
        $loginpage->userLogin($I);

        $I->waitForElement($activationDeactivation->pluginsMenu, 20);
        $I->click($activationDeactivation->pluginsMenu);
        $I->waitForElement($activationDeactivation->searchInstalledPluginField, 20);
        $I->fillField($activationDeactivation->searchInstalledPluginField, $activationDeactivation->searchInstalledPluginValue);
        $I->wait(3);
        $I->click($activationDeactivation->activateButton);
        $I->wait(130);
        $I->reloadPage();
        $I->waitForText('Help us improve WPAdCenter', 20);
        $I->see('Help us improve WPAdCenter');
        $I->waitForElement($activationDeactivation->helpUsImproveBannerCloseBtn);
        $I->click($activationDeactivation->helpUsImproveBannerCloseBtn);
        $I->wait(2);

        $loginpage->userLogout($I);
    }

     /**
      * Test to check working of upgradetopro and wpekaclublink
      *  
      * @param $I                      variable of WPAdcenter_FreeTester
      * @param $loginpage              Used to login and logout from the page.
      * @param $activationDeactivation consist of selectors.
      * 
      * @return void
      * 
      * @since 1.0
      */
    public function clubEkaLinksTest(WPAdcenter_FreeTester $I, loginpage $loginpage, ActivationDeactivation $activationDeactivation)
    {
        $loginpage->userLogin($I);

        $I->waitForElement($activationDeactivation->pluginsMenu, 20);
        $I->click($activationDeactivation->pluginsMenu);
        $I->waitForElement($activationDeactivation->searchInstalledPluginField, 20);
        $I->fillField($activationDeactivation->searchInstalledPluginField, $activationDeactivation->searchInstalledPluginValue);
        $I->wait(3);
        $I->waitForElement($activationDeactivation->upgradeToProLink);
        $I->click($activationDeactivation->upgradeToProLink);
        $I->switchToNextTab();
        $I->waitForText('WP AdCenter Pro', 20);
        $I->closeTab();
        $I->switchToPreviousTab();
        $I->waitForElement($activationDeactivation->wpekaClubLink, 20);
        $I->click($activationDeactivation->wpekaClubLink);
        $I->waitForText('PREMIUM WORDPRESS PLUGIN CLUB', 20);
        $I->moveBack();

        $loginpage->userLogout($I);
    }

    /**
     * Test to check five star rating banner 
     *  
     * @param $I                      variable of WPAdcenter_FreeTester
     * @param $loginpage              Used to login and logout from the page.
     * @param $activationDeactivation consist of selectors.
     * 
     * @return void
     * 
     * @since 1.0
     */
    public function fiveStarRatingBannerTest(WPAdcenter_FreeTester $I, loginpage $loginpage, ActivationDeactivation $activationDeactivation)
    {
        $loginpage->userLogin($I);

        $I->waitForElement($activationDeactivation->pluginsMenu, 20);
        $I->click($activationDeactivation->pluginsMenu);
        $I->waitForElement($activationDeactivation->searchInstalledPluginField, 20);
        $I->fillField($activationDeactivation->searchInstalledPluginField, $activationDeactivation->searchTransientManagerPluginValue);
        $I->wait(3);
        $I->click($activationDeactivation->activateTransientsManager);
        $I->click($activationDeactivation->toolsMenu);
        $I->waitForElement($activationDeactivation->transientsSubMenu, 20);
        $I->click($activationDeactivation->transientsSubMenu);

        $I->waitForElement($activationDeactivation->searchTransientField, 20);
        $I->fillField($activationDeactivation->searchTransientField, $activationDeactivation->searchTransientValue);
        $I->click($activationDeactivation->searchTransientButton);
        $I->waitForElement($activationDeactivation->wplegalpages_ask_for_review_Selector, 20);
        $I->moveMouseOver($activationDeactivation->wplegalpages_ask_for_review_Selector);
        $I->wait(1);
        $I->click($activationDeactivation->deleteTransientBtn);
        $I->waitForText('Thanks for using the WPAdCenter. Can you please do us a favor and give us a 5-star rating?', 20);

        $I->waitForElement($activationDeactivation->pluginsMenu, 20);
        $I->click($activationDeactivation->pluginsMenu);
        $I->waitForElement($activationDeactivation->searchInstalledPluginField, 20);
        $I->fillField($activationDeactivation->searchInstalledPluginField, $activationDeactivation->searchTransientManagerPluginValue);
        $I->wait(3);
        $I->click($activationDeactivation->deactivateButton);
        $I->wait(2);

        $loginpage->userLogout($I);
    }

    /**
     * Test to check whether user can deactivate the plugin
     *  
     * @param $I                      variable of WPAdcenter_FreeTester
     * @param $loginpage              Used to login and logout from the page.
     * @param $activationDeactivation consist of selectors.
     * 
     * @return void
     * 
     * @since 1.0
     */
    public function deactivationTest(WPAdcenter_FreeTester $I, loginpage $loginpage, ActivationDeactivation $activationDeactivation)
    {
        $loginpage->userLogin($I);

        $I->waitForElement($activationDeactivation->pluginsMenu, 20);
        $I->click($activationDeactivation->pluginsMenu);
        $I->waitForElement($activationDeactivation->searchInstalledPluginField, 20);
        $I->fillField($activationDeactivation->searchInstalledPluginField, $activationDeactivation->searchInstalledPluginValue);
        $I->wait(3);
        $I->click($activationDeactivation->deactivateButton);
        $I->wait(5);
        $I->click($activationDeactivation->activateButton);
        $I->wait(5);
        $I->click($activationDeactivation->deactivateButton);
        $I->waitForText('If you have a moment, please let us know why you are deactivating', 20);
        $I->see('If you have a moment, please let us know why you are deactivating:');
        $I->waitForText("I couldn't understand how to make it work", 20);
        $I->waitForText("The plugin is great, but I need specific feature that you don't support", 20);
        $I->waitForText("The plugin is not working", 20);
        $I->waitForText("It's not what I was looking for", 20);
        $I->waitForText("The plugin didn't work as expected", 20);
        $I->waitForText("I found a better plugin", 20);
        $I->waitForText("It's a temporary plugin switch. I'm just debugging an issue.");
        $I->see("I couldn't understand how to make it work");
        $I->see("The plugin is great, but I need specific feature that you don't support");
        $I->see("The plugin is not working");
        $I->see("It's not what I was looking for");
        $I->see("The plugin didn't work as expected");
        $I->see("I found a better plugin");
        $I->see("It's a temporary plugin switch. I'm just debugging an issue.");
        $I->click('#reasons-list > li:nth-child(1) > label > span:nth-child(1) > input[type=radio]');
        $I->waitForText('Send contact details for help', 20);
        $I->see('Send contact details for help');
        $I->click($activationDeactivation->cancelFeebackButton);

        $loginpage->userLogout($I);
    }
}
