<?php
/**
 * Automation test cases for AMP Ad of WPAdcenterFree plugin
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
 * Core class used for AMP Ad of WPAdcenterFree plugin
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
class AMPAdCest
{
    /**
     * Test For Creation Of Amp Ad Using Gutenberg Blocks
     * 
     * @param $I            variable of WPAdcenter_FreeTester
     * @param $loginpage    Used to login and logout from the page.
     * @param $createAdMenu consist of selectors.
     * 
     * @return void
     * 
     * @since 1.0
     */
    public function testForAmpAdUsingGutenbergBlocks(WPAdcenter_FreeTester $I, loginpage $loginpage, CreateAdMenu $createAdMenu)
    {
        $loginpage->userLogin($I);

        $I->waitForElement($createAdMenu->wpadcenterMenuBtn, 20);
        $I->click($createAdMenu->wpadcenterMenuBtn);
        $createAdMenu->createNewAdGroup($I);
        $I->waitForElementVisible($createAdMenu->createAdSubMenu, 20);
        $I->click($createAdMenu->createAdSubMenu);
        $I->waitForElement($createAdMenu->adTitleField, 20);
        $I->fillField($createAdMenu->adTitleField, $createAdMenu->adTitleValue);
        $I->click($createAdMenu->adtypeAMPAd);
        $I->click($createAdMenu->adGroupSelectCheckbox);
        $I->wait(2);
        $I->click($createAdMenu->removeAttributeBtn);
        $I->click($createAdMenu->removeAttributeBtn);
        $I->click($createAdMenu->removeAttributeBtn);
        $I->click($createAdMenu->addAttribute_Btn);
        $I->click($createAdMenu->addAttribute_Btn);
        $I->click($createAdMenu->addAttribute_Btn);
        $I->click($createAdMenu->addAttribute_Btn);
        $I->click($createAdMenu->addAttribute_Btn);
        $I->click($createAdMenu->addAttribute_Btn);
        $I->click($createAdMenu->addAttribute_Btn);
        $I->click($createAdMenu->addAttribute_Btn);
        $I->click($createAdMenu->addAttribute_Btn);
        $I->click($createAdMenu->addAttribute_Btn);
        $I->wait(2);
        $I->fillField($createAdMenu->attributeField1, $createAdMenu->attributeValue1);
        $I->fillField($createAdMenu->attributeValueField1, $createAdMenu->attributeValueValue1);
        $I->fillField($createAdMenu->attributeField2, $createAdMenu->attributeValue2);
        $I->fillField($createAdMenu->attributeValueField2, $createAdMenu->attributeValueValue2);
        $I->fillField($createAdMenu->attributeField3, $createAdMenu->attributeValue3);
        $I->fillField($createAdMenu->attributeValueField3, $createAdMenu->attributeValueValue3);
        $I->fillField($createAdMenu->attributeField4, $createAdMenu->attributeValue4);
        $I->fillField($createAdMenu->attributeValueField4, $createAdMenu->attributeValueValue4);
        $I->fillField($createAdMenu->attributeField5, $createAdMenu->attributeValue5);
        $I->fillField($createAdMenu->attributeValueField5, $createAdMenu->attributeValueValue5);
        $I->fillField($createAdMenu->attributeField6, $createAdMenu->attributeValue6);
        $I->fillField($createAdMenu->attributeValueField6, $createAdMenu->attributeValueValue6);
        $I->fillField($createAdMenu->attributeField7, $createAdMenu->attributeValue7);
        $I->fillField($createAdMenu->attributeValueField7, $createAdMenu->attributeValueValue7);
        $I->fillField($createAdMenu->attributeField8, $createAdMenu->attributeValue8);
        $I->fillField($createAdMenu->attributeValueField8, $createAdMenu->attributeValueValue8);
        $I->fillField($createAdMenu->attributeField9, $createAdMenu->attributeValue9);
        $I->fillField($createAdMenu->attributeValueField9, $createAdMenu->attributeValueValue9);
        $I->fillField($createAdMenu->attributeField10, $createAdMenu->attributeValue10);
        $I->fillField($createAdMenu->attributeValueField10, $createAdMenu->attributeValueValue10);
        $I->fillField($createAdMenu->placeholderField, $createAdMenu->placeholderValue);
        $I->fillField($createAdMenu->fallbackField, $createAdMenu->fallbackValue);
        $I->scrollTo($createAdMenu->createadtext);
        $I->waitForElementVisible($createAdMenu->publishBtn, 20);
        $I->wait(2);
        $I->click($createAdMenu->publishBtn);
        $I->waitForText('Post published.', 20);
        $I->see('Post published.');

        $createAdMenu->createPageByAddingAMPAdSingleAdBlock($I);
        $createAdMenu->movePageToTrash($I);
        $createAdMenu->createPageByAddingAMPAdAdGroupBlock($I);
        $createAdMenu->movePageToTrash($I);
        $createAdMenu->moveAdToTrash($I);
        $createAdMenu->moveAdGroupToTrash($I);
        $loginpage->userLogout($I);
    }

    /**
     * Test For Creation Of Amp Ad Using shortcode
     * 
     * @param $I            variable of WPAdcenter_FreeTester
     * @param $loginpage    Used to login and logout from the page.
     * @param $createAdMenu consist of selectors.
     * 
     * @return void
     * 
     * @since 1.0
     */
    public function testForAmpAdWithShortCode(WPAdcenter_FreeTester $I, loginpage $loginpage, CreateAdMenu $createAdMenu)
    { 
        $loginpage->userLogin($I);

        $I->waitForElement($createAdMenu->wpadcenterMenuBtn, 20);
        $I->click($createAdMenu->wpadcenterMenuBtn);
        $createAdMenu->createNewAdGroup($I);
        $I->waitForElementVisible($createAdMenu->createAdSubMenu, 20);
        $I->click($createAdMenu->createAdSubMenu);
        $I->waitForElement($createAdMenu->adTitleField, 20);
        $I->fillField($createAdMenu->adTitleField, $createAdMenu->adTitleValue);
        $I->click($createAdMenu->adtypeAMPAd);
        $I->click($createAdMenu->adGroupSelectCheckbox);
        $I->wait(2);
        $I->click($createAdMenu->removeAttributeBtn);
        $I->click($createAdMenu->removeAttributeBtn);
        $I->click($createAdMenu->removeAttributeBtn);
        $I->click($createAdMenu->addAttribute_Btn);
        $I->click($createAdMenu->addAttribute_Btn);
        $I->click($createAdMenu->addAttribute_Btn);
        $I->click($createAdMenu->addAttribute_Btn);
        $I->click($createAdMenu->addAttribute_Btn);
        $I->click($createAdMenu->addAttribute_Btn);
        $I->click($createAdMenu->addAttribute_Btn);
        $I->click($createAdMenu->addAttribute_Btn);
        $I->click($createAdMenu->addAttribute_Btn);
        $I->click($createAdMenu->addAttribute_Btn);
        $I->wait(2);
        $I->fillField($createAdMenu->attributeField1, $createAdMenu->attributeValue1);
        $I->fillField($createAdMenu->attributeValueField1, $createAdMenu->attributeValueValue1);
        $I->fillField($createAdMenu->attributeField2, $createAdMenu->attributeValue2);
        $I->fillField($createAdMenu->attributeValueField2, $createAdMenu->attributeValueValue2);
        $I->fillField($createAdMenu->attributeField3, $createAdMenu->attributeValue3);
        $I->fillField($createAdMenu->attributeValueField3, $createAdMenu->attributeValueValue3);
        $I->fillField($createAdMenu->attributeField4, $createAdMenu->attributeValue4);
        $I->fillField($createAdMenu->attributeValueField4, $createAdMenu->attributeValueValue4);
        $I->fillField($createAdMenu->attributeField5, $createAdMenu->attributeValue5);
        $I->fillField($createAdMenu->attributeValueField5, $createAdMenu->attributeValueValue5);
        $I->fillField($createAdMenu->attributeField6, $createAdMenu->attributeValue6);
        $I->fillField($createAdMenu->attributeValueField6, $createAdMenu->attributeValueValue6);
        $I->fillField($createAdMenu->attributeField7, $createAdMenu->attributeValue7);
        $I->fillField($createAdMenu->attributeValueField7, $createAdMenu->attributeValueValue7);
        $I->fillField($createAdMenu->attributeField8, $createAdMenu->attributeValue8);
        $I->fillField($createAdMenu->attributeValueField8, $createAdMenu->attributeValueValue8);
        $I->fillField($createAdMenu->attributeField9, $createAdMenu->attributeValue9);
        $I->fillField($createAdMenu->attributeValueField9, $createAdMenu->attributeValueValue9);
        $I->fillField($createAdMenu->attributeField10, $createAdMenu->attributeValue10);
        $I->fillField($createAdMenu->attributeValueField10, $createAdMenu->attributeValueValue10);
        $I->fillField($createAdMenu->placeholderField, $createAdMenu->placeholderValue);
        $I->fillField($createAdMenu->fallbackField, $createAdMenu->fallbackValue);
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
        $I->wait(3);
        $I->moveMouseOver($createAdMenu->AMP);
        $I->wait(1);
        $I->click($createAdMenu->ViewAMPVersion);
        $I->wait(5);
        $I->scrollTo($createAdMenu->wpadcenterAd, 20);
        $I->wait(3);
        $I->moveMouseOver($createAdMenu->AMP);
        $I->wait(1);
        $I->click($createAdMenu->ViewNonAMPVersion);
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
        $I->wait(3);
        $I->moveMouseOver($createAdMenu->AMP);
        $I->wait(1);
        $I->click($createAdMenu->ViewAMPVersion);
        $I->wait(5);
        $I->scrollTo($createAdMenu->wpadcenterAd, 20);
        $I->wait(3);
        $I->moveMouseOver($createAdMenu->AMP);
        $I->wait(1);
        $I->click($createAdMenu->ViewNonAMPVersion);
        $createAdMenu->movePageToTrash($I);
        $createAdMenu->moveAdToTrash($I);
        $createAdMenu->moveAdGroupToTrash($I);

        $loginpage->userLogout($I);
    }
}
