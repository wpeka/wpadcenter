<?php
/**
 * Automation test cases for WPAdcenter Adblock and widget of WPAdcenterFree plugin
 * 
 * @category AutomationTests
 * @package  WordPress_WPAdcenter_Free_Plugin
 * @author   WPEKA <hello@wpeka.com>
 * @license  GPL v3
 * @link     https://club.wpeka.com
 */
use Facebook\WebDriver\WebDriverBy;
use Page\Acceptance\Loginpage;
use Page\WPAdcenter_Free\AdBlockAndWidget;

/**
 * Core class used for the testcases of WPAdcenter Adblock and widget of WPAdcenterFree plugin
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
class AdBlockAndWidgetCest
{
    /**
     * Test to check working of gutenberg block 'WPAdCenter Ad block' for single Ad and AdGroup
     * 
     * @param $I                variable of WPAdcenter_FreeTester 
     * @param $loginpage        Used to login and logout from the page.
     * @param $adBlockAndWidget consist of selectors.
     * 
     * @return void
     * 
     * @since 1.0
     */
    public function wpAdcenterAdBlock(WPAdcenter_FreeTester $I, Loginpage $loginpage, AdBlockAndWidget $adBlockAndWidget)
    {
        $loginpage->userLogin($I);

        $I->waitForElement($adBlockAndWidget->wpadcenterMenuBtn, 20);
        $I->click($adBlockAndWidget->wpadcenterMenuBtn);
        $adBlockAndWidget->createNewAdGroup($I);
        $I->waitForElementVisible($adBlockAndWidget->createAdSubMenu, 20);
        $I->click($adBlockAndWidget->createAdSubMenu);
        $I->waitForElement($adBlockAndWidget->adTitleField, 20);
        $I->fillField($adBlockAndWidget->adTitleField, $adBlockAndWidget->adTitleValue);
        $I->click($adBlockAndWidget->adtypeExternalImageLink);
        $I->click($adBlockAndWidget->adsizesmallsquare);
        $I->fillField($adBlockAndWidget->externalImageField, $adBlockAndWidget->externalImageLinkValue);
        $I->fillField($adBlockAndWidget->linkUrlField, $adBlockAndWidget->linkUrlValue);
        $I->click($adBlockAndWidget->adGroupSelectCheckbox);
        $I->scrollTo($adBlockAndWidget->createadtext);
        $I->waitForElementVisible($adBlockAndWidget->publishBtn, 20);
        $I->wait(2);
        $I->click($adBlockAndWidget->publishBtn);
        $I->waitForText('Post published.', 20);
        $I->see('Post published.');
        $I->click($adBlockAndWidget->pages_MainMenuLink);
        $I->click($adBlockAndWidget->addNewPage_SubmenuLink);
        $I->wait(2);
        $I->click($adBlockAndWidget->welcomeToBlockEditorPopUpCloseBtn);
        $I->waitForElement($adBlockAndWidget->pageTitleField, 20);
        $I->fillField($adBlockAndWidget->pageTitleField, $adBlockAndWidget->pageTitleValue);
        $I->click($adBlockAndWidget->addBlockBtn);
        $I->fillField($adBlockAndWidget->wpadcenterSearchBlockField, $adBlockAndWidget->wpadcenterSearchBlockValue);
        $I->click($adBlockAndWidget->wpadcenterBlockSelect);
        $I->wait(2);
        $I->click($adBlockAndWidget->selectAdType);
        $I->wait(2);
        $I->click($adBlockAndWidget->adTypeValue);
        $I->wait(2);
        $I->click($adBlockAndWidget->selectAdDropdownBtn);
        $I->wait(2);
        $I->click($adBlockAndWidget->selectAd);
        $I->wait(3);
        $I->click($adBlockAndWidget->selectAlignment);
        $I->click($adBlockAndWidget->enableMaxWidth);
        $I->click($adBlockAndWidget->publishPageBtn1);
        $I->click($adBlockAndWidget->publishPageBtn2);
        $I->wait(3);
        $I->click($adBlockAndWidget->viewPageLink);
        $I->wait(5);
        $adBlockAndWidget->movePageToTrash($I);
        $I->click($adBlockAndWidget->pages_MainMenuLink);
        $I->click($adBlockAndWidget->addNewPage_SubmenuLink);
        $I->wait(2);
        $I->waitForElement($adBlockAndWidget->pageTitleField, 20);
        $I->fillField($adBlockAndWidget->pageTitleField, $adBlockAndWidget->pageTitleValue);
        $I->click($adBlockAndWidget->addBlockBtn);
        $I->fillField($adBlockAndWidget->wpadcenterSearchBlockField, $adBlockAndWidget->wpadcenterSearchBlockValue);
        $I->click($adBlockAndWidget->wpadcenterBlockSelect);
        $I->wait(2);
        $I->click($adBlockAndWidget->selectAdType);
        $I->wait(2);
        $I->click($adBlockAndWidget->adTypeValueAsAdgroup);
        $I->wait(2);
        $I->click($adBlockAndWidget->selectAdGroupDropdownBtn);
        $I->wait(2);
        $I->click($adBlockAndWidget->selectAdGroup);
        $I->wait(3);
        $I->click($adBlockAndWidget->selectAlignmentForAdGroup);
        $I->click($adBlockAndWidget->enableMaxWidthForAdgroup);
        $I->click($adBlockAndWidget->publishPageBtn1);
        $I->click($adBlockAndWidget->publishPageBtn2);
        $I->wait(3);
        $I->click($adBlockAndWidget->viewPageLink);
        $I->wait(5);
        $adBlockAndWidget->movePageToTrash($I);
        $adBlockAndWidget->moveAdToTrash($I);
        $adBlockAndWidget->moveAdGroupToTrash($I);

        $loginpage->userLogout($I);
    }

    /**
     * Test to check working of gutenberg block 'WPAdCenter Ad block' for Random Ad
     * 
     * @param $I                variable of WPAdcenter_FreeTester 
     * @param $loginpage        Used to login and logout from the page.
     * @param $adBlockAndWidget consist of selectors.
     * 
     * @return void
     * 
     * @since 1.0
     */
    public function wpAdcenterAdBlockForRandomAd(WPAdcenter_FreeTester $I, Loginpage $loginpage, AdBlockAndWidget $adBlockAndWidget)
    {
        $loginpage->userLogin($I);

        $I->waitForElement($adBlockAndWidget->wpadcenterMenuBtn, 20);
        $I->click($adBlockAndWidget->wpadcenterMenuBtn);
        $adBlockAndWidget->createNewAdGroup($I);
        $I->waitForElementVisible($adBlockAndWidget->createAdSubMenu, 20);
        $I->click($adBlockAndWidget->createAdSubMenu);
        $I->waitForElement($adBlockAndWidget->adTitleField, 20);
        $I->fillField($adBlockAndWidget->adTitleField, $adBlockAndWidget->adTitleValue);
        $I->click($adBlockAndWidget->adtypeExternalImageLink);
        $I->click($adBlockAndWidget->adsizesmallsquare);
        $I->fillField($adBlockAndWidget->externalImageField, $adBlockAndWidget->externalImageLinkValue);
        $I->fillField($adBlockAndWidget->linkUrlField, $adBlockAndWidget->linkUrlValue);
        $I->click($adBlockAndWidget->adGroupSelectCheckbox);
        $I->scrollTo($adBlockAndWidget->createadtext);
        $I->waitForElementVisible($adBlockAndWidget->publishBtn, 20);
        $I->wait(2);
        $I->click($adBlockAndWidget->publishBtn);
        $I->waitForText('Post published.', 20);
        $I->see('Post published.');
        $I->waitForElementVisible($adBlockAndWidget->createAdSubMenu, 20);
        $I->click($adBlockAndWidget->createAdSubMenu);
        $I->waitForElement($adBlockAndWidget->adTitleField, 20);
        $I->fillField($adBlockAndWidget->adTitleField, $adBlockAndWidget->adTitleValue2);
        $I->click($adBlockAndWidget->adtypeBannerImage);
        $I->click($adBlockAndWidget->adsizesmallsquare);
        $I->click($adBlockAndWidget->setAdImage);
        $I->wait(5);
        $I->click($adBlockAndWidget->mediaLibrary);
        $I->waitForElement($adBlockAndWidget->selectImage, 20);
        $I->click($adBlockAndWidget->selectImage);
        $I->waitForElement($adBlockAndWidget->setAdImageBtn, 20);
        $I->click($adBlockAndWidget->setAdImageBtn);
        $I->fillField($adBlockAndWidget->linkUrlField, $adBlockAndWidget->linkUrlValue);
        $I->click($adBlockAndWidget->adGroupSelectCheckbox);
        $I->scrollTo($adBlockAndWidget->createadtext);
        $I->waitForElementVisible($adBlockAndWidget->publishBtn, 20);
        $I->wait(2);
        $I->click($adBlockAndWidget->publishBtn);
        $I->waitForText('Post published.', 20);
        $I->see('Post published.');
        $I->click($adBlockAndWidget->pages_MainMenuLink);
        $I->click($adBlockAndWidget->addNewPage_SubmenuLink);
        $I->wait(2);
        $I->click($adBlockAndWidget->welcomeToBlockEditorPopUpCloseBtn);
        $I->waitForElement($adBlockAndWidget->pageTitleField, 20);
        $I->fillField($adBlockAndWidget->pageTitleField, $adBlockAndWidget->pageTitleValue);
        $I->click($adBlockAndWidget->addBlockBtn);
        $I->fillField($adBlockAndWidget->wpadcenterSearchBlockField, $adBlockAndWidget->wpadcenterSearchBlockValue);
        $I->click($adBlockAndWidget->wpadcenterBlockSelect);
        $I->wait(2);
        $I->click($adBlockAndWidget->selectAdType);
        $I->waitForElement($adBlockAndWidget->adTypeValueAsRandomAd, 20);
        $I->click($adBlockAndWidget->adTypeValueAsRandomAd);
        $I->waitForElement($adBlockAndWidget->selectAdGroupDropdownBtn, 20);
        $I->click($adBlockAndWidget->selectAdGroupDropdownBtn);
        $I->wait(2);
        $I->click($adBlockAndWidget->selectAdGroup);
        $I->wait(2);
        $I->click($adBlockAndWidget->selectAlignmentForRandomAd);
        $I->click($adBlockAndWidget->enableMaxWidthForRandomAd);
        $I->click($adBlockAndWidget->publishPageBtn1);
        $I->click($adBlockAndWidget->publishPageBtn2);
        $I->wait(3);
        $I->click($adBlockAndWidget->viewPageLink);
        $I->wait(5);
        $I->reloadPage();
        $I->wait(5);
        $I->reloadPage();
        $I->wait(5);
        $adBlockAndWidget->movePageToTrash($I);
        $adBlockAndWidget->moveAdToTrash($I);
        $adBlockAndWidget->moveAdToTrash($I);
        $adBlockAndWidget->moveAdGroupToTrash($I);

        $loginpage->userLogout($I);
    }

    /**
     * Test to check working of elementor block 'WPAdCenter Ad Widget' for single Ad and AdGroup
     * 
     * @param $I                variable of WPAdcenter_FreeTester 
     * @param $loginpage        Used to login and logout from the page.
     * @param $adBlockAndWidget consist of selectors.
     * 
     * @return void
     * 
     * @since 1.0
     */
    public function wpAdcenterAdWidget(WPAdcenter_FreeTester $I, Loginpage $loginpage, AdBlockAndWidget $adBlockAndWidget)
    {
        $loginpage->userLogin($I);

        $I->waitForElement($adBlockAndWidget->wpadcenterMenuBtn, 20);
        $I->click($adBlockAndWidget->wpadcenterMenuBtn);
        $adBlockAndWidget->createNewAdGroup($I);
        $I->waitForElementVisible($adBlockAndWidget->createAdSubMenu, 20);
        $I->click($adBlockAndWidget->createAdSubMenu);
        $I->waitForElement($adBlockAndWidget->adTitleField, 20);
        $I->fillField($adBlockAndWidget->adTitleField, $adBlockAndWidget->adTitleValue);
        $I->click($adBlockAndWidget->adtypeExternalImageLink);
        $I->click($adBlockAndWidget->adsizesmallsquare);
        $I->fillField($adBlockAndWidget->externalImageField, $adBlockAndWidget->externalImageLinkValue);
        $I->fillField($adBlockAndWidget->linkUrlField, $adBlockAndWidget->linkUrlValue);
        $I->click($adBlockAndWidget->adGroupSelectCheckbox);
        $I->scrollTo($adBlockAndWidget->createadtext);
        $I->waitForElementVisible($adBlockAndWidget->publishBtn, 20);
        $I->wait(2);
        $I->click($adBlockAndWidget->publishBtn);
        $I->waitForText('Post published.', 20);
        $I->see('Post published.');
        $adBlockAndWidget->importPages($I, 'WpAdcenter.xml');
        $I->wait(3);
        $I->amOnPage('/WpAdcenter');
        $I->wait(5);
        $I->click($adBlockAndWidget->editWithElementorBtn);
        $I->wait(10);
        $adBlockAndWidget->clickWiget($I);
        $I->wait(3);
        $I->click($adBlockAndWidget->selectAdTypeForElementor);
        $I->waitForElement($adBlockAndWidget->selectAdTypeValueForElementor, 20);
        $I->click($adBlockAndWidget->selectAdTypeValueForElementor);
        $I->waitForElement($adBlockAndWidget->selectAdForElementor, 20);
        $I->click($adBlockAndWidget->selectAdForElementor);
        $I->click($adBlockAndWidget->selectAdValueForElementor);
        $I->waitForElement($adBlockAndWidget->alignment1, 20);
        $I->click($adBlockAndWidget->alignment1);
        $I->click($adBlockAndWidget->maxWidth);
        $I->click($adBlockAndWidget->updateBtn);
        $I->wait(5);
        $I->amOnPage('/WpAdcenter');
        $I->wait(2);
        $I->click($adBlockAndWidget->wordPressBtn);
        $I->wait(3);
        $adBlockAndWidget->importPages($I, 'WpAdcenter.xml');
        $I->wait(3);
        $I->amOnPage('/WpAdcenter');
        $I->wait(5);
        $I->click($adBlockAndWidget->editWithElementorBtn);
        $I->wait(10);
        $adBlockAndWidget->clickWiget($I);
        $I->wait(3);
        $I->click($adBlockAndWidget->selectAdTypeForElementor);
        $I->waitForElement($adBlockAndWidget->selectAdTypeValueForElementor2, 20);
        $I->click($adBlockAndWidget->selectAdTypeValueForElementor2);
        $I->waitForElement($adBlockAndWidget->selectAdgroupForElementor, 20);
        $I->click($adBlockAndWidget->selectAdgroupForElementor);
        $I->click($adBlockAndWidget->selectAdgroupValueForElementor);
        $I->waitForElement($adBlockAndWidget->alignment2, 20);
        $I->click($adBlockAndWidget->alignment2);
        $I->click($adBlockAndWidget->maxWidth);
        $I->click($adBlockAndWidget->updateBtn);
        $I->wait(5);
        $I->amOnPage('/WpAdcenter');
        $I->wait(2);
        $I->click($adBlockAndWidget->wordPressBtn);
        $I->wait(3);
        $adBlockAndWidget->moveAdToTrash($I);
        $adBlockAndWidget->moveAdGroupToTrash($I);

        $loginpage->userLogout($I);
    }

    /**
     * Test to check working of elementor block 'WPAdCenter Ad Widget' for Random Ad
     * 
     * @param $I                variable of WPAdcenter_FreeTester 
     * @param $loginpage        Used to login and logout from the page.
     * @param $adBlockAndWidget consist of selectors.
     * 
     * @return void
     * 
     * @since 1.0
     */
    public function wpAdcenterAdWidgetForRandomAd(WPAdcenter_FreeTester $I, Loginpage $loginpage, AdBlockAndWidget $adBlockAndWidget)
    {
        $loginpage->userLogin($I);

        $I->waitForElement($adBlockAndWidget->wpadcenterMenuBtn, 20);
        $I->click($adBlockAndWidget->wpadcenterMenuBtn);
        $adBlockAndWidget->createNewAdGroup($I);
        $I->waitForElementVisible($adBlockAndWidget->createAdSubMenu, 20);
        $I->click($adBlockAndWidget->createAdSubMenu);
        $I->waitForElement($adBlockAndWidget->adTitleField, 20);
        $I->fillField($adBlockAndWidget->adTitleField, $adBlockAndWidget->adTitleValue);
        $I->click($adBlockAndWidget->adtypeExternalImageLink);
        $I->click($adBlockAndWidget->adsizesmallsquare);
        $I->fillField($adBlockAndWidget->externalImageField, $adBlockAndWidget->externalImageLinkValue);
        $I->fillField($adBlockAndWidget->linkUrlField, $adBlockAndWidget->linkUrlValue);
        $I->click($adBlockAndWidget->adGroupSelectCheckbox);
        $I->scrollTo($adBlockAndWidget->createadtext);
        $I->waitForElementVisible($adBlockAndWidget->publishBtn, 20);
        $I->wait(2);
        $I->click($adBlockAndWidget->publishBtn);
        $I->waitForText('Post published.', 20);
        $I->see('Post published.');
        $I->waitForElementVisible($adBlockAndWidget->createAdSubMenu, 20);
        $I->click($adBlockAndWidget->createAdSubMenu);
        $I->waitForElement($adBlockAndWidget->adTitleField, 20);
        $I->fillField($adBlockAndWidget->adTitleField, $adBlockAndWidget->adTitleValue2);
        $I->click($adBlockAndWidget->adtypeBannerImage);
        $I->click($adBlockAndWidget->adsizesmallsquare);
        $I->click($adBlockAndWidget->setAdImage);
        $I->wait(5);
        $I->click($adBlockAndWidget->mediaLibrary);
        $I->waitForElement($adBlockAndWidget->selectImage, 20);
        $I->click($adBlockAndWidget->selectImage);
        $I->waitForElement($adBlockAndWidget->setAdImageBtn, 20);
        $I->click($adBlockAndWidget->setAdImageBtn);
        $I->fillField($adBlockAndWidget->linkUrlField, $adBlockAndWidget->linkUrlValue);
        $I->click($adBlockAndWidget->adGroupSelectCheckbox);
        $I->scrollTo($adBlockAndWidget->createadtext);
        $I->waitForElementVisible($adBlockAndWidget->publishBtn, 20);
        $I->wait(2);
        $I->click($adBlockAndWidget->publishBtn);
        $I->waitForText('Post published.', 20);
        $I->see('Post published.');
        $adBlockAndWidget->importPages($I, 'WpAdcenter.xml');
        $I->wait(3);
        $I->amOnPage('/WpAdcenter');
        $I->wait(5);
        $I->click($adBlockAndWidget->editWithElementorBtn);
        $I->wait(10);
        $adBlockAndWidget->clickWiget($I);
        $I->wait(3);
        $I->click($adBlockAndWidget->selectAdTypeForElementor);
        $I->waitForElement($adBlockAndWidget->selectAdTypeValueForElementor3, 20);
        $I->click($adBlockAndWidget->selectAdTypeValueForElementor3);
        $I->waitForElement($adBlockAndWidget->selectAdgroupForElementor, 20);
        $I->click($adBlockAndWidget->selectAdgroupForElementor);
        $I->click($adBlockAndWidget->selectAdgroupValueForElementor);
        $I->waitForElement($adBlockAndWidget->alignment3, 20);
        $I->click($adBlockAndWidget->alignment3);
        $I->click($adBlockAndWidget->maxWidth);
        $I->click($adBlockAndWidget->updateBtn);
        $I->wait(5);
        $I->amOnPage('/WpAdcenter');
        $I->wait(5);
        $I->reloadPage();
        $I->wait(5);
        $I->reloadPage();
        $I->wait(5);
        $I->click($adBlockAndWidget->wordPressBtn);
        $I->wait(3);
        $adBlockAndWidget->moveAdToTrash($I);
        $adBlockAndWidget->moveAdToTrash($I);
        $adBlockAndWidget->moveAdGroupToTrash($I);

        $loginpage->userLogout($I);
    }
}
