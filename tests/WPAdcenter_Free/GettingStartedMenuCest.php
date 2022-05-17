<?php
/**
 * Automation test cases for Getting started menu of WPAdcenterFree plugin
 * 
 * @category AutomationTests
 * @package  WordPress_WPAdcenter_Free_Plugin
 * @author   WPEKA <hello@wpeka.com>
 * @license  GPL v3
 * @link     https://club.wpeka.com
 */
use Facebook\WebDriver\WebDriverBy;
use Page\Acceptance\loginpage;
use Page\WPAdcenter_Free\GettingStartedMenu;
 
/**
 * Core class used for Getting started menu testcases of WPAdcenterFree plugin
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
class GettingStartedMenuCest
{
    /**
     * Test to check working of see quick links of getting started menu 
     * 
     * @param $I                  variable of WPAdcenter_FreeTester
     * @param $loginpage          Used to login and logout from the page.
     * @param $gettingStartedMenu consist of selectors.
     * 
     * @return void
     * 
     * @since 1.0
     */
    public function seeQuickLinks(WPAdcenter_FreeTester $I, loginpage $loginpage, GettingStartedMenu $gettingStartedMenu)
    {
        $loginpage->userLogin($I);

        $I->waitForElement($gettingStartedMenu->wpadcenterMenuBtn, 20);
        $I->click($gettingStartedMenu->wpadcenterMenuBtn);
        $I->waitForElementVisible($gettingStartedMenu->gettingStartedPage, 20);
        $I->click($gettingStartedMenu->gettingStartedPage);
        $I->waitForText('Welcome to WP AdCenter!', 20);
        $I->scrollTo($gettingStartedMenu->configureWPADcenterButton);
        $I->click($gettingStartedMenu->configureWPADcenterButton);
        $I->switchToNextTab();
        $I->waitForElement($gettingStartedMenu->rolsesToExcludeFromSelector, 20);
        $I->closeTab();
        $I->switchToPreviousTab();
        $I->waitForElement($gettingStartedMenu->wpquicklinksbutton, 20);
        $I->click($gettingStartedMenu->wpquicklinksbutton);
        $I->click($gettingStartedMenu->supportButton);
        $I->switchToNextTab();
        $I->waitForText('WP AdCenter - Ad Manager & Adsense Ads', 20);
        $I->closeTab();
        $I->switchToPreviousTab();
        $I->waitForElement($gettingStartedMenu->wpquicklinksbutton, 20);
        $I->click($gettingStartedMenu->wpquicklinksbutton);
        $I->click($gettingStartedMenu->faqButton);
        $I->switchToNextTab();
        $I->waitForText('FAQ', 20);
        $I->closeTab();
        $I->switchToPreviousTab();
        $I->waitForElement($gettingStartedMenu->wpquicklinksbutton, 20);
        $I->click($gettingStartedMenu->wpquicklinksbutton);
        $I->wait(2);
        $I->click($gettingStartedMenu->documentationButton);
        $I->switchToNextTab();
        $I->waitForText('Installation', 20);
        $I->closeTab();
        $I->switchToPreviousTab();
        $I->waitForElement($gettingStartedMenu->wpquicklinksbutton, 20);
        $I->click($gettingStartedMenu->wpquicklinksbutton);
        $I->waitForElement($gettingStartedMenu->upgradeToProButton, 20);
        $I->click($gettingStartedMenu->upgradeToProButton);
        $I->switchToNextTab();
        $I->waitForText('WP AdCenter Pro', 20);
        $I->closeTab();
        $I->switchToPreviousTab();

        $loginpage->userLogout($I);
    }
}
