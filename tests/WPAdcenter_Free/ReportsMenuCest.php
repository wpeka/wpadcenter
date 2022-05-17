<?php
/**
 * Automation test cases for reports menu of WPAdcenterFree plugin
 * 
 * @category AutomationTests
 * @package  WordPress_WPAdcenter_Free_Plugin
 * @author   WPEKA <hello@wpeka.com>
 * @license  GPL v3
 * @link     https://club.wpeka.com
 */
use Facebook\WebDriver\WebDriverBy;
use Page\Acceptance\loginpage;
use Page\WPAdcenter_Free\ReportsMenu;
 
/**
 * Core class used for reports menu testcases of WPAdcenterFree plugin
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
class ReportsMenuCest
{
    /**
     * Test to check total views of ad in reports 
     * 
     * @param $I           variable of WPAdcenter_FreeTester
     * @param $loginpage   Used to login and logout from the page.
     * @param $reportsMenu consist of selectors.
     * 
     * @return void
     * 
     * @since 1.0
     */
    public function viewsInReportsWorkingTestForAd(WPAdcenter_FreeTester $I, loginpage $loginpage, ReportsMenu $reportsMenu)
    {
        $loginpage->userLogin($I);

        $I->waitForElement($reportsMenu->wpadcenterMenuBtn, 20);
        $I->click($reportsMenu->wpadcenterMenuBtn);
        $reportsMenu->createNewAdGroup($I);
        $I->waitForElementVisible($reportsMenu->createAdSubMenu, 20);
        $I->click($reportsMenu->createAdSubMenu);
        $I->waitForElement($reportsMenu->adTitleField, 20);
        $I->fillField($reportsMenu->adTitleField, $reportsMenu->adTitleValue);
        $I->click($reportsMenu->adtypeExternalImageLink);
        $I->click($reportsMenu->adsizesmallsquare);
        $I->fillField($reportsMenu->externalImageField, $reportsMenu->externalImageLinkValue);
        $I->fillField($reportsMenu->linkUrlField, $reportsMenu->linkUrlValue);
        $I->click($reportsMenu->adGroupSelectCheckbox);
        $I->scrollTo($reportsMenu->createadtext);
        $I->waitForElementVisible($reportsMenu->publishBtn, 20);
        $I->wait(2);
        $I->click($reportsMenu->publishBtn);
        $I->waitForText('Post published.', 20);
        $I->see('Post published.');
        $reportsMenu->createPageByAddingWPAdCenterSingleAdBlock($I);
        $reportsMenu->movePageToTrash($I);
        $I->waitForElement($reportsMenu->wpadcenterMenuBtn, 20);
        $I->click($reportsMenu->wpadcenterMenuBtn);
        $I->waitForElementVisible($reportsMenu->reportsSubMenu, 20);
        $I->click($reportsMenu->reportsSubMenu);
        $I->waitForElement($reportsMenu->totalViewsSelector, 20);
        $viewAfterAdVisit=$I->grabTextFrom($reportsMenu->totalViewsSelector);
        echo $viewAfterAdVisit;
        $I->assertEquals('1', $viewAfterAdVisit);
        $reportsMenu->moveAdToTrash($I);
        $reportsMenu->moveAdGroupToTrash($I);

        $loginpage->userLogout($I);
    }

    /**
     * Test to check total clicks of ad in reports 
     * 
     * @param $I           variable of WPAdcenter_FreeTester
     * @param $loginpage   Used to login and logout from the page.
     * @param $reportsMenu consist of selectors.
     * 
     * @return void
     * 
     * @since 1.0
     */
    public function clicksInReportsWorkingTestForAd(WPAdcenter_FreeTester $I, loginpage $loginpage, ReportsMenu $reportsMenu)
    {
        $loginpage->userLogin($I);

        $I->waitForElement($reportsMenu->wpadcenterMenuBtn, 20);
        $I->click($reportsMenu->wpadcenterMenuBtn);
        $reportsMenu->createNewAdGroup($I);
        $I->waitForElementVisible($reportsMenu->createAdSubMenu, 20);
        $I->click($reportsMenu->createAdSubMenu);
        $I->waitForElement($reportsMenu->adTitleField, 20);
        $I->fillField($reportsMenu->adTitleField, $reportsMenu->adTitleValue);
        $I->click($reportsMenu->adtypeExternalImageLink);
        $I->click($reportsMenu->adsizesmallsquare);
        $I->fillField($reportsMenu->externalImageField, $reportsMenu->externalImageLinkValue);
        $I->fillField($reportsMenu->linkUrlField, $reportsMenu->linkUrlValue);
        $I->click($reportsMenu->adGroupSelectCheckbox);
        $I->scrollTo($reportsMenu->createadtext);
        $I->waitForElementVisible($reportsMenu->publishBtn, 20);
        $I->wait(2);
        $I->click($reportsMenu->publishBtn);
        $I->waitForText('Post published.', 20);
        $I->see('Post published.');
        $reportsMenu->createPageByAddingWPAdCenterSingleAdBlock($I);
        $reportsMenu->movePageToTrash($I);
        $I->waitForElement($reportsMenu->wpadcenterMenuBtn, 20);
        $I->click($reportsMenu->wpadcenterMenuBtn);
        $I->waitForElementVisible($reportsMenu->reportsSubMenu, 20);
        $I->click($reportsMenu->reportsSubMenu);
        $I->waitForElement($reportsMenu->totalClicksSelector, 20);
        $clickAfterAdVisit=$I->grabTextFrom($reportsMenu->totalClicksSelector);
        echo $clickAfterAdVisit;
        $I->assertEquals('1', $clickAfterAdVisit);
        $reportsMenu->moveAdToTrash($I);
        $reportsMenu->moveAdGroupToTrash($I);

        $loginpage->userLogout($I);
    }

    /**
     * Test to create custom reports 
     * 
     * @param $I           variable of WPAdcenter_FreeTester
     * @param $loginpage   Used to login and logout from the page.
     * @param $reportsMenu consist of selectors.
     * 
     * @return void
     * 
     * @since 1.0
     */
    public function customReportsTabTest(WPAdcenter_FreeTester $I, loginpage $loginpage, ReportsMenu $reportsMenu)
    {
        $loginpage->userLogin($I);

        $I->waitForElement($reportsMenu->wpadcenterMenuBtn, 20);
        $I->click($reportsMenu->wpadcenterMenuBtn);
        $reportsMenu->createNewAdGroup($I);
        $I->waitForElementVisible($reportsMenu->createAdSubMenu, 20);
        $I->click($reportsMenu->createAdSubMenu);
        $I->waitForElement($reportsMenu->adTitleField, 20);
        $I->fillField($reportsMenu->adTitleField, $reportsMenu->adTitleValue);
        $I->click($reportsMenu->adtypeExternalImageLink);
        $I->click($reportsMenu->adsizesmallsquare);
        $I->fillField($reportsMenu->externalImageField, $reportsMenu->externalImageLinkValue);
        $I->fillField($reportsMenu->linkUrlField, $reportsMenu->linkUrlValue);
        $I->click($reportsMenu->adGroupSelectCheckbox);
        $I->scrollTo($reportsMenu->createadtext);
        $I->waitForElementVisible($reportsMenu->publishBtn, 20);
        $I->wait(2);
        $I->click($reportsMenu->publishBtn);
        $I->waitForText('Post published.', 20);
        $I->see('Post published.');
        $reportsMenu->createPageByAddingWPAdCenterSingleAdBlock($I);
        $reportsMenu->movePageToTrash($I);

        $I->waitForElement($reportsMenu->wpadcenterMenuBtn, 20);
        $I->click($reportsMenu->wpadcenterMenuBtn);
        $I->waitForElementVisible($reportsMenu->reportsSubMenu, 20);
        $I->click($reportsMenu->reportsSubMenu);
        $I->waitForElement($reportsMenu->totalViewsSelector, 20);
        $I->waitForElement($reportsMenu->customReportsTab, 20);
        $I->click($reportsMenu->customReportsTab);
        $I->waitForElementVisible($reportsMenu->selectAdSelector, 20);
        $I->wait(2);
        $I->click($reportsMenu->selectAdSelector);
        $I->wait(2);
        $I->click($reportsMenu->adSelectedSelector);
        $I->wait(2);
        $I->scrollTo($reportsMenu->detailedReportsTextSelector);
        $I->wait(2);
        $I->waitForElementVisible($reportsMenu->adDetailsTableSelector, 20);
        $reportsMenu->moveAdToTrash($I);
        $reportsMenu->moveAdGroupToTrash($I);

        $loginpage->userLogout($I);
    }
}
