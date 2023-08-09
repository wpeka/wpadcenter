<?php
/**
 * Automation test cases for GoPro Menu of WPAdcenterFree plugin
 * 
 * @category AutomationTests
 * @package  WordPress_WPAdcenter_Free_Plugin
 * @author   WPEKA <hello@wpeka.com>
 * @license  GPL v3
 * @link     https://club.wpeka.com
 */
use Facebook\WebDriver\WebDriverBy;
use Page\Acceptance\loginpage;
use Page\WPAdcenter_Free\GoProMenu;
 
/**
 * Core class used for GoPro Menu testcases of WPAdcenterFree plugin
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
class GoProMenuCest
{
    /**
     * Test to check working of GoPro link
     * 
     * @param $I         variable of WPAdcenter_FreeTester
     * @param $loginpage Used to login and logout from the page.
     * @param $goProMenu consist of selectors.
     * 
     * @return void
     * 
     * @since 1.0
     */
    public function goProLinkWorkingTest(WPAdcenter_FreeTester $I, loginpage $loginpage, GoProMenu $goProMenu)
    {
        $loginpage->userLogin($I);

        $I->waitForElement($goProMenu->wpadcenterMenuBtn, 20);
        $I->click($goProMenu->wpadcenterMenuBtn);
        $I->waitForElementVisible($goProMenu->goPro, 20);
        $I->click($goProMenu->goPro);
        $I->waitForText('WP AdCenter Pro', 20);
        $I->moveBack();

        $loginpage->userLogout($I);
    }
}
