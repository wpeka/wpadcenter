<?php
/**
 * Automation test cases for Text Ad of WPAdcenterFree plugin
 * 
 * @category AutomationTests
 * @package  WordPress_WPAdcenter_Free_Plugin
 * @author   WPEKA <hello@wpeka.com>
 * @license  GPL v3
 * @link     https://club.wpeka.com
 */
use Facebook\WebDriver\WebDriverBy;
use Page\Acceptance\loginpage;
use Page\WPAdcenter_Free\CreateAdMenu;

/**
 * Core class used for Text Ad testcases of WPAdcenterFree plugin
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
class TextAdCest
{
    /**
     * Test For Creation Of Text Ad Using Gutenberg Blocks
     * 
     * @param $I            variable of WPAdcenter_FreeTester
     * @param $loginpage    Used to login and logout from the page.
     * @param $createAdMenu consist of selectors.
     * 
     * @return void
     * 
     * @since 1.0
     */
    public function testForCreationOfTextAdUsingGutenbergBlocks(WPAdcenter_FreeTester $I, loginpage $loginpage, CreateAdMenu $createAdMenu)
    {
        $loginpage->userLogin($I);

        $I->waitForElement($createAdMenu->wpadcenterMenuBtn, 20);
        $I->click($createAdMenu->wpadcenterMenuBtn);
        $createAdMenu->createNewAdGroup($I);
        $I->waitForElementVisible($createAdMenu->createAdSubMenu, 20);
        $I->click($createAdMenu->createAdSubMenu);
        $I->waitForElement($createAdMenu->adTitleField, 20);
        $I->fillField($createAdMenu->adTitleField, $createAdMenu->adTitleValue);
        $I->click($createAdMenu->adGroupSelectCheckbox);
        $I->wait(2);
        $I->click($createAdMenu->adtypeTextAd);
        $I->click($createAdMenu->adsizesmallsquare);
        $I->executeJS('jQuery("iframe").attr("name", "new_name")');
        $I->seeElement('iframe');
        $I->switchToIFrame("new_name");
        $I->click($createAdMenu->textAdEditorBodySelector);
        $I->fillField($createAdMenu->textAdEditorPTagSelector, 'Demo Text Ad');
        $I->switchToIFrame();
        $I->scrollTo($createAdMenu->createadtext);
        $I->waitForElementVisible($createAdMenu->publishBtn, 20);
        $I->wait(2);
        $I->click($createAdMenu->publishBtn);
        $I->waitForText('Post published.', 20);
        $I->see('Post published.');

        $I->click($createAdMenu->pages_MainMenuLink);
        $I->click($createAdMenu->addNewPage_SubmenuLink);
        $I->wait(2);
        $I->click($createAdMenu->welcomeToBlockEditorPopUpCloseBtn);
        $I->fillField($createAdMenu->pageTitleField, $createAdMenu->pageTitleValue);
        $I->click($createAdMenu->addBlockBtn);
        $I->fillField($createAdMenu->wpadcenterSearchBlockField, $createAdMenu->wpadcenterSearchBlockValue);
        $I->click($createAdMenu->wpadcenterBlockSelect);
        $I->wait(2);
        $I->click($createAdMenu->selectAdDropdownBtn);
        $I->wait(2);
        $I->click($createAdMenu->selectAd);
        $I->wait(3);
        $I->click($createAdMenu->publishPageBtn1);
        $I->click($createAdMenu->publishPageBtn2);
        $I->wait(3);
        $I->click($createAdMenu->viewPageLink);
        $I->wait(5);
        $I->scrollTo($createAdMenu->wpadcenterContainer);
        $I->wait(2);
        $I->waitForText('Demo Text Ad', 20);
        $createAdMenu->movePageToTrash($I);
        $createAdMenu->moveAdToTrash($I);
        $createAdMenu->moveAdGroupToTrash($I);

        $loginpage->userLogout($I);
    }

    /**
     * Test For Creation Of Text Ad Using short code 
     * 
     * @param $I            variable of WPAdcenter_FreeTester
     * @param $loginpage    Used to login and logout from the page.
     * @param $createAdMenu consist of selectors.
     * 
     * @return void
     * 
     * @since 1.0
     */      
    public function testForTextAdCreationWithShortcode(WPAdcenter_FreeTester $I, loginpage $loginpage, CreateAdMenu $createAdMenu)
    {
        $loginpage->userLogin($I);

        $I->waitForElement($createAdMenu->wpadcenterMenuBtn, 20);
        $I->click($createAdMenu->wpadcenterMenuBtn);
        $createAdMenu->createNewAdGroup($I);
        $I->waitForElementVisible($createAdMenu->createAdSubMenu, 20);
        $I->click($createAdMenu->createAdSubMenu);
        $I->waitForElement($createAdMenu->adTitleField, 20);
        $I->fillField($createAdMenu->adTitleField, $createAdMenu->adTitleValue);
        $I->click($createAdMenu->adGroupSelectCheckbox);
        $I->wait(2);
        $I->click($createAdMenu->adtypeTextAd);
        $I->click($createAdMenu->adsizesmallsquare);
        $I->executeJS('jQuery("iframe").attr("name", "new_name")');
        $I->seeElement('iframe');
        $I->switchToIFrame("new_name");
        $I->click($createAdMenu->textAdEditorBodySelector);
        $I->fillField($createAdMenu->textAdEditorPTagSelector, 'Demo Text Ad');
        $I->switchToIFrame();
        $I->scrollTo($createAdMenu->createadtext);
        $I->waitForElementVisible($createAdMenu->publishBtn, 20);
        $I->wait(2);
        $I->click($createAdMenu->publishBtn);
        $I->waitForText('Post published.', 20);
        $I->see('Post published.');

        $I->waitForElement($createAdMenu->wpadcenterMenuBtn, 20);
        $I->click($createAdMenu->wpadcenterMenuBtn);
        $I->waitForElement($createAdMenu->shortcode, 20);
        $testAdShortcode=$I->grabAttributeFrom($createAdMenu->shortcode, 'data-attr');
        echo $testAdShortcode;
        $I->click($createAdMenu->manageAdGroupsSubMenu);
        $testAdGroupShortcode=$I->grabAttributeFrom($createAdMenu->shortcode, 'data-attr');
        echo $testAdGroupShortcode;

        $I->click($createAdMenu->pages_MainMenuLink);
        $I->click($createAdMenu->addNewPage_SubmenuLink);
        $I->wait(2);
        $I->click($createAdMenu->welcomeToBlockEditorPopUpCloseBtn);
        $I->fillField($createAdMenu->pageTitleField, $createAdMenu->pageTitleValue);
        $I->click($createAdMenu->addBlockBtn);
        $I->fillField($createAdMenu->wpadcenterSearchBlockField, $createAdMenu->wpadcenterSearchBlockValueForShortcode);
        $I->click($createAdMenu->wpadcenterBlockSelect);
        $I->wait(2);
        $I->fillField($createAdMenu->textareaForAddingShortcode, $testAdShortcode);
        $I->wait(3);
        $I->click($createAdMenu->publishPageBtn1);
        $I->click($createAdMenu->publishPageBtn2);
        $I->wait(3);
        $I->click($createAdMenu->viewPageLink);
        $I->wait(5);
        $I->scrollTo($createAdMenu->wpadcenterContainer);
        $I->wait(2);
        $I->waitForText('Demo Text Ad', 20);
        $createAdMenu->movePageToTrash($I);

        $I->click($createAdMenu->pages_MainMenuLink);
        $I->click($createAdMenu->addNewPage_SubmenuLink);
        $I->wait(2);
        $I->fillField($createAdMenu->pageTitleField, $createAdMenu->pageTitleValue);
        $I->click($createAdMenu->addBlockBtn);
        $I->fillField($createAdMenu->wpadcenterSearchBlockField, $createAdMenu->wpadcenterSearchBlockValueForShortcode);
        $I->click($createAdMenu->wpadcenterBlockSelect);
        $I->wait(2);
        $I->fillField($createAdMenu->textareaForAddingShortcode, $testAdGroupShortcode);
        $I->wait(3);
        $I->click($createAdMenu->publishPageBtn1);
        $I->click($createAdMenu->publishPageBtn2);
        $I->wait(3);
        $I->click($createAdMenu->viewPageLink);
        $I->wait(5);
        $I->scrollTo($createAdMenu->wpadcenterContainer);
        $I->wait(2);
        $I->waitForText('Demo Text Ad', 20);
        $createAdMenu->movePageToTrash($I);
        $createAdMenu->moveAdToTrash($I);
        $createAdMenu->moveAdGroupToTrash($I);

        $loginpage->userLogout($I);
    }
}
