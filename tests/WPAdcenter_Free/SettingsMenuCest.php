<?php
/**
 * Automation test cases for settings menu of WPAdcenterFree plugin
 * 
 * @category AutomationTests
 * @package  WordPress_WPAdcenter_Free_Plugin
 * @author   WPEKA <hello@wpeka.com>
 * @license  GPL v3
 * @link     https://club.wpeka.com
 */
use Facebook\WebDriver\WebDriverBy;
use Page\Acceptance\loginpage;
use Page\WPAdcenter_Free\SettingsMenu;
 
/**
 * Core class used for settings menu testcases of WPAdcenterFree plugin
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

class SettingsMenuCest
{
     /**
      * Test to check whether selected roles are exluded from tracking test
      * 
      * @param $I            variable of WPAdcenter_FreeTester
      * @param $loginpage    Used to login and logout from the page.
      * @param $settingsMenu consist of selectors.
      * 
      * @return void
      * 
      * @since 1.0
      */
    public function selectedRolesShouldBeExludedFromTrackingTest(WPAdcenter_FreeTester $I, loginpage $loginpage, SettingsMenu $settingsMenu)
    {
        $loginpage->userLogin($I);

        $I->waitForElement($settingsMenu->wpadcenterMenuBtn, 20);
        $I->click($settingsMenu->wpadcenterMenuBtn);
        $I->waitForElementVisible($settingsMenu->settingsSubMenu, 20);
        $I->click($settingsMenu->settingsSubMenu);
        $I->waitForElement($settingsMenu->rolsesToExcludeFromSelector, 20);
        $I->click($settingsMenu->rolsesToExcludeFromSelector);
        $I->wait(2);
        $I->click($settingsMenu->administratorSelected);
        $I->scrollTo($settingsMenu->saveChangesButton);
        $I->wait(2);
        $I->click($settingsMenu->saveChangesButton);
        $settingsMenu->createNewAdGroup($I);
        $I->waitForElementVisible($settingsMenu->createAdSubMenu, 20);
        $I->click($settingsMenu->createAdSubMenu);
        $I->waitForElement($settingsMenu->adTitleField, 20);
        $I->fillField($settingsMenu->adTitleField, $settingsMenu->adTitleValue);
        $I->click($settingsMenu->adtypeExternalImageLink);
        $I->click($settingsMenu->adsizesmallsquare);
        $I->fillField($settingsMenu->externalImageField, $settingsMenu->externalImageLinkValue);
        $I->fillField($settingsMenu->linkUrlField, $settingsMenu->linkUrlValue);
        $I->click($settingsMenu->adGroupSelectCheckbox);
        $I->scrollTo($settingsMenu->createadtext);
        $I->waitForElementVisible($settingsMenu->publishBtn, 20);
        $I->wait(2);
        $I->click($settingsMenu->publishBtn);
        $I->waitForText('Post published.', 20);
        $I->see('Post published.');
        $settingsMenu->createPageByAddingWPAdCenterSingleAdBlock($I);
        $settingsMenu->movePageToTrash($I);
        $I->waitForElement($settingsMenu->wpadcenterMenuBtn, 20);
        $I->click($settingsMenu->wpadcenterMenuBtn);
        $I->waitForElementVisible($settingsMenu->reportsSubMenu, 20);
        $I->click($settingsMenu->reportsSubMenu);
        $I->waitForElement($settingsMenu->totalViewsSelector, 20);
        $viewAfterAdVisit=$I->grabTextFrom($settingsMenu->totalViewsSelector);
        echo $viewAfterAdVisit;
        $I->assertEquals('0', $viewAfterAdVisit);
        $I->waitForElementVisible($settingsMenu->settingsSubMenu, 20);
        $I->click($settingsMenu->settingsSubMenu);
        $I->waitForElement($settingsMenu->rolsesToExcludeFromSelector, 20);
        $I->click($settingsMenu->rolsesToExcludeFromSelector);
        $I->wait(2);
        $I->click($settingsMenu->administratorDeselected);
        $I->scrollTo($settingsMenu->saveChangesButton);
        $I->wait(2);
        $I->click($settingsMenu->saveChangesButton);
        $settingsMenu->moveAdToTrash($I);
        $settingsMenu->moveAdGroupToTrash($I);

        $loginpage->userLogout($I);
    }

     /**
      * Test to check whether user is able to enable/disable script tests
      * 
      * @param $I            variable of WPAdcenter_FreeTester
      * @param $loginpage    Used to login and logout from the page.
      * @param $settingsMenu consist of selectors.
      * 
      * @return void
      * 
      * @since 1.0
      */
    public function enableAndDisableScriptsTest(WPAdcenter_FreeTester $I, loginpage $loginpage, SettingsMenu $settingsMenu)
    {
        $loginpage->userLogin($I);

        $I->waitForElement($settingsMenu->wpadcenterMenuBtn, 20);
        $I->click($settingsMenu->wpadcenterMenuBtn);
        $I->waitForElementVisible($settingsMenu->settingsSubMenu, 20);
        $I->click($settingsMenu->settingsSubMenu);
        $I->waitForElement($settingsMenu->rolsesToExcludeFromSelector, 20);
        $I->click($settingsMenu->scriptsTab);
        $I->waitForElement($settingsMenu->enableScriptsButton, 20);
        $I->click($settingsMenu->enableScriptsButton);
        $I->waitForElementVisible($settingsMenu->headerScriptsField, 20);
        $I->fillField($settingsMenu->headerScriptsField, $settingsMenu->headerScriptsValue);
        $I->scrollTo($settingsMenu->saveChangesButton);
        $I->wait(2);
        $I->click($settingsMenu->saveChangesButton);
        $I->wait(3);
        $I->click($settingsMenu->wordPressBtn);
        $I->seeInPopup('Demo AdCode!');
        $I->acceptPopup();
        $I->wait(2);
        $I->click($settingsMenu->wordPressBtn);
        $I->waitForElement($settingsMenu->wpadcenterMenuBtn, 20);
        $I->click($settingsMenu->wpadcenterMenuBtn);
        $I->waitForElementVisible($settingsMenu->settingsSubMenu, 20);
        $I->click($settingsMenu->settingsSubMenu);
        $I->waitForElement($settingsMenu->rolsesToExcludeFromSelector, 20);
        $I->click($settingsMenu->scriptsTab);
        $I->waitForElement($settingsMenu->enableScriptsButton, 20);
        $I->click($settingsMenu->enableScriptsButton);
        $I->wait(2);
        $I->dontSeeElement($settingsMenu->headerScriptsField);
        $I->scrollTo($settingsMenu->saveChangesButton);
        $I->wait(2);
        $I->click($settingsMenu->saveChangesButton);

        $loginpage->userLogout($I);
    }

    /**
     * Test to check the working of link options
     * 
     * @param $I            variable of WPAdcenter_FreeTester
     * @param $loginpage    Used to login and logout from the page.
     * @param $settingsMenu consist of selectors.
     * 
     * @return void
     * 
     * @since 1.0
     */
    public function linkOptionsWorkingTest(WPAdcenter_FreeTester $I, loginpage $loginpage, SettingsMenu $settingsMenu)
    {
        $loginpage->userLogin($I);

        $I->waitForElement($settingsMenu->wpadcenterMenuBtn, 20);
        $I->click($settingsMenu->wpadcenterMenuBtn);
        $I->waitForElementVisible($settingsMenu->settingsSubMenu, 20);
        $I->click($settingsMenu->settingsSubMenu);
        $I->waitForElement($settingsMenu->rolsesToExcludeFromSelector, 20);
        $I->scrollTo($settingsMenu->linkOptionTextSelector);
        $I->wait(2);
        $I->click($settingsMenu->openInNextTabButton);
        $I->click($settingsMenu->noFollowOnLinkButton);
        $I->click($settingsMenu->additionalRelAttributesButton);
        $I->click($settingsMenu->sponsoredSelected);
        $I->click($settingsMenu->additionalRelAttributesButton);
        $I->click($settingsMenu->ugcSelected);
        $I->fillField($settingsMenu->additionalCSSClassField, $settingsMenu->additionalCSSClassValue);
        $I->scrollTo($settingsMenu->saveChangesButton);
        $I->click($settingsMenu->saveChangesButton);
        $I->wait(3);
        $settingsMenu->createNewAdGroup($I);
        $I->waitForElementVisible($settingsMenu->createAdSubMenu, 20);
        $I->click($settingsMenu->createAdSubMenu);
        $I->waitForElement($settingsMenu->adTitleField, 20);
        $I->fillField($settingsMenu->adTitleField, $settingsMenu->adTitleValue);
        $I->click($settingsMenu->adtypeExternalImageLink);
        $I->click($settingsMenu->adsizesmallsquare);
        $I->fillField($settingsMenu->externalImageField, $settingsMenu->externalImageLinkValue);
        $I->fillField($settingsMenu->linkUrlField, $settingsMenu->linkUrlValue);
        $I->click($settingsMenu->adGroupSelectCheckbox);
        $I->scrollTo($settingsMenu->createadtext);
        $I->waitForElementVisible($settingsMenu->publishBtn, 20);
        $I->wait(2);
        $I->click($settingsMenu->publishBtn);
        $I->waitForText('Post published.', 20);
        $I->see('Post published.');
        $I->click($settingsMenu->pages_MainMenuLink);
        $I->click($settingsMenu->addNewPage_SubmenuLink);
        $I->wait(2);
        $I->click($settingsMenu->welcomeToBlockEditorPopUpCloseBtn);
        $I->waitForElement($settingsMenu->pageTitleField, 20);
        $I->fillField($settingsMenu->pageTitleField, $settingsMenu->pageTitleValue);
        $I->click($settingsMenu->addBlockBtn);
        $I->fillField($settingsMenu->wpadcenterSearchBlockField, $settingsMenu->wpadcenterSearchBlockValue);
        $I->click($settingsMenu->wpadcenterBlockSelect);
        $I->wait(2);
        $I->click($settingsMenu->selectAdDropdownBtn);
        $I->wait(2);
        $I->click($settingsMenu->selectAd);
        $I->wait(3);
        $I->click($settingsMenu->publishPageBtn1);
        $I->click($settingsMenu->publishPageBtn2);
        $I->wait(3);
        $I->click($settingsMenu->viewPageLink);
        $I->wait(5);
        $I->scrollTo($settingsMenu->wpadcenterAd, 20);
        $I->wait(2);
        $I->seeElement($settingsMenu->wpadcenterAd);
        $I->click($settingsMenu->wpadcenterAd);
        $I->switchToNextTab();
        $I->wait(4);
        $I->switchToPreviousTab();
        $I->wait(2);
        $testRelNofollow=$I->grabAttributeFrom('div.wpadcenter-ad-inner > a', 'rel');
        echo $testRelNofollow;
        $I->assertEquals($testRelNofollow, 'nofollow sponsored ugc');
        $settingsMenu->movePageToTrash($I);
        $settingsMenu->moveAdToTrash($I);
        $settingsMenu->moveAdGroupToTrash($I);
        $I->waitForElement($settingsMenu->wpadcenterMenuBtn, 20);
        $I->click($settingsMenu->wpadcenterMenuBtn);
        $I->waitForElementVisible($settingsMenu->settingsSubMenu, 20);
        $I->click($settingsMenu->settingsSubMenu);
        $I->waitForElement($settingsMenu->rolsesToExcludeFromSelector, 20);
        $I->scrollTo($settingsMenu->linkOptionTextSelector);
        $I->wait(2);
        $I->click($settingsMenu->openInNextTabButton);
        $I->click($settingsMenu->noFollowOnLinkButton);
        $I->click($settingsMenu->attributeDeselectButton);
        $I->click($settingsMenu->attributeDeselectButton);
        $I->fillField($settingsMenu->additionalCSSClassField, '');
        $I->scrollTo($settingsMenu->saveChangesButton);
        $I->click($settingsMenu->saveChangesButton);
        $I->wait(2);

        $loginpage->userLogout($I);
    }

    /**
     * Test to check the working of privacy options
     * 
     * @param $I            variable of WPAdcenter_FreeTester
     * @param $loginpage    Used to login and logout from the page.
     * @param $settingsMenu consist of selectors.
     * 
     * @return void
     * 
     * @since 1.0
     */
    public function privacyOptionsWorkingTest(WPAdcenter_FreeTester $I, loginpage $loginpage, SettingsMenu $settingsMenu)
    {
        $loginpage->userLogin($I);

        $I->waitForElement($settingsMenu->pluginsMenu, 20);
        $I->click($settingsMenu->pluginsMenu);
        $I->waitForElement($settingsMenu->searchInstalledPluginField, 20);
        $I->fillField($settingsMenu->searchInstalledPluginField, $settingsMenu->searchGDPRPluginVlaue);
        $I->wait(3);
        $I->click($settingsMenu->activateGDPRButton);
        $I->waitForElement($settingsMenu->wpadcenterMenuBtn, 20);
        $I->click($settingsMenu->wpadcenterMenuBtn);
        $I->waitForElementVisible($settingsMenu->settingsSubMenu, 20);
        $I->click($settingsMenu->settingsSubMenu);
        $I->waitForElement($settingsMenu->rolsesToExcludeFromSelector, 20);
        $I->scrollTo($settingsMenu->privacyOptionsTextSelector);
        $I->wait(2);
        $I->click($settingsMenu->enablePrivacyPolicyButton);
        $I->waitForElementVisible($settingsMenu->cookieRadioButton, 20);
        $I->click($settingsMenu->cookieRadioButton);
        $I->fillField($settingsMenu->cookieNameField, $settingsMenu->cookieNameValue);
        $I->fillField($settingsMenu->cookieValueField, $settingsMenu->cookieValueValue);
        $I->click($settingsMenu->saveChangesButton);
        $I->wait(3);
        $settingsMenu->createNewAdGroup($I);
        $I->waitForElementVisible($settingsMenu->createAdSubMenu, 20);
        $I->click($settingsMenu->createAdSubMenu);
        $I->waitForElement($settingsMenu->adTitleField, 20);
        $I->fillField($settingsMenu->adTitleField, $settingsMenu->adTitleValue);
        $I->click($settingsMenu->adtypeExternalImageLink);
        $I->click($settingsMenu->adsizesmallsquare);
        $I->fillField($settingsMenu->externalImageField, $settingsMenu->externalImageLinkValue);
        $I->fillField($settingsMenu->linkUrlField, $settingsMenu->linkUrlValue);
        $I->click($settingsMenu->adGroupSelectCheckbox);
        $I->scrollTo($settingsMenu->createadtext);
        $I->waitForElementVisible($settingsMenu->publishBtn, 20);
        $I->wait(2);
        $I->click($settingsMenu->publishBtn);
        $I->waitForText('Post published.', 20);
        $I->see('Post published.');

        $I->click($settingsMenu->pages_MainMenuLink);
        $I->click($settingsMenu->addNewPage_SubmenuLink);
        $I->wait(2);
        $I->click($settingsMenu->welcomeToBlockEditorPopUpCloseBtn);
        $I->waitForElement($settingsMenu->pageTitleField, 20);
        $I->fillField($settingsMenu->pageTitleField, $settingsMenu->pageTitleValue);
        $I->click($settingsMenu->addBlockBtn);
        $I->fillField($settingsMenu->wpadcenterSearchBlockField, $settingsMenu->wpadcenterSearchBlockValue);
        $I->click($settingsMenu->wpadcenterBlockSelect);
        $I->wait(2);
        $I->click($settingsMenu->selectAdDropdownBtn);
        $I->wait(2);
        $I->click($settingsMenu->selectAd);
        $I->wait(3);
        $I->click($settingsMenu->publishPageBtn1);
        $I->click($settingsMenu->publishPageBtn2);
        $I->wait(3);
        $I->click($settingsMenu->viewPageLink);
        $I->wait(2);
        $I->dontSeeElement($settingsMenu->wpadcenterAd);
        $I->waitForText('This website uses cookies to improve your experience. We\'ll assume you\'re ok with this, but you can opt-out if you wish.', 50);
        $I->waitForElementVisible($settingsMenu->acceptCookieButton, 20);
        $I->click($settingsMenu->acceptCookieButton);
        $I->reloadPage();
        $I->waitForElementVisible($settingsMenu->wpadcenterAd, 20);
        $I->scrollTo($settingsMenu->wpadcenterAd, 20);
        $I->wait(2);
        $I->seeElement($settingsMenu->wpadcenterAd);
        $I->wait(2);
        $settingsMenu->movePageToTrash($I);
        $settingsMenu->moveAdToTrash($I);
        $settingsMenu->moveAdGroupToTrash($I);
        $I->waitForElementVisible($settingsMenu->settingsSubMenu, 20);
        $I->click($settingsMenu->settingsSubMenu);
        $I->waitForElement($settingsMenu->rolsesToExcludeFromSelector, 20);
        $I->scrollTo($settingsMenu->privacyOptionsTextSelector);
        $I->wait(2);
        $I->click($settingsMenu->enablePrivacyPolicyButton);
        $I->click($settingsMenu->saveChangesButton);
        $I->wait(3);
        $I->waitForElement($settingsMenu->pluginsMenu, 20);
        $I->click($settingsMenu->pluginsMenu);
        $I->waitForElement($settingsMenu->searchInstalledPluginField, 20);
        $I->fillField($settingsMenu->searchInstalledPluginField, $settingsMenu->searchGDPRPluginVlaue);
        $I->wait(3);
        $I->click($settingsMenu->deactivateButton);
        $I->waitForText('Plugin deactivated.', 10);

        $loginpage->userLogout($I);
    }
}
