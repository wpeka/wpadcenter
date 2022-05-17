<?php
/**
 * Selectors used in the automation testcases for creation of different Ads of WPAdcenter free plugin
 * 
 * @category AutomationTests
 * @package  WordPress_WPAdcenter_Free_Plugin
 * @author   WPEKA <hello@wpeka.com>
 * @license  GPL v3
 * @link     https://club.wpeka.com
 */
namespace Page\WPAdcenter_Free;

/**
 * Core class for all the selectors used in automation testcases for creation of different Ads of WPAdcenter free plugin
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
class CreateAdMenu
{
    public $wpadcenterMenuBtn='#menu-posts-wpadcenter-ads > a > div.wp-menu-name';
    public $manageAdGroupsSubMenu='#menu-posts-wpadcenter-ads > ul > li:nth-child(4) > a';
    public $groupNameField='#tag-name';
    public $groupNameValue='Test Group';
    public $slugField='#tag-slug';
    public $slugValue='testgrp';
    public $descriptionField='#tag-description';
    public $descriptionValue='This is description for test ad';
    public $addNewGroupBtn='#submit';
    public $createAdSubMenu='#menu-posts-wpadcenter-ads > ul > li:nth-child(3) > a';
    public $adTitleField='#title';
    public $adTitleValue='New Test Ad';
    public $adtypeExternalImageLink='#ad-type > option:nth-child(2)';
    public $adsizesmallsquare='#size > optgroup:nth-child(2) > option:nth-child(1)';
    public $externalImageField='#external-image-link > div.inside > input[type=text]';
    public $externalImageLinkValue='https://picsum.photos/200/300';
    public $linkUrlField='#link-url';
    public $linkUrlValue='https://www.google.co.in';
    public $adGroupSelectCheckbox='#wpadcenter-adgroupschecklist > li > label > input';
    public $createadtext='#wpbody-content > div.wrap > h1';
    public $publishBtn='#publish';

    /**
     * Test to create the new Ad group
     *
     * @param $I All the functions related to codeception
     * 
     * @return void
     * 
     * @since 1.0
     */
    public function createNewAdGroup($I)
    {
        $I->waitForElementVisible($this->manageAdGroupsSubMenu);
        $I->click($this->manageAdGroupsSubMenu);
        $I->fillField($this->groupNameField, $this->groupNameValue);
        $I->fillField($this->slugField, $this->slugValue);
        $I->fillField($this->descriptionField, $this->descriptionValue);
        $I->wait(2);
        $I->click($this->addNewGroupBtn);
        $I->wait(2);
    }

    public $pages_MainMenuLink='//*[@id="menu-pages"]/a/div[3]';
    public $addNewPage_SubmenuLink='//*[@id="menu-pages"]/ul/li[3]/a';
    public $welcomeToBlockEditorPopUpCloseBtn='div.components-modal__header > button';
    public $pageTitleField='div.edit-post-visual-editor__post-title-wrapper > h1';
    public $pageTitleValue='Demo-Page';
    public $addBlockBtn='.components-button.block-editor-inserter__toggle.has-icon';
    public $wpadcenterSearchBlockField='input.components-search-control__input';
    public $wpadcenterSearchBlockValue='WPAdCenter';
    public $wpadcenterBlockSelect='div.block-editor-block-types-list__list-item > button:nth-child(1)';
    public $selectAd='div.wpadcenter-async-select > div.css-duvieu-menu';
    public $selectAdDropdownBtn='div.components-placeholder__fieldset.is-column-layout > div:nth-child(2) > div > div > div:nth-child(2)';
    public $publishPageBtn1='.components-button.editor-post-publish-panel__toggle.editor-post-publish-button__button.is-primary';
    public $publishPageBtn2='.components-button.editor-post-publish-button.editor-post-publish-button__button.is-primary';
    public $viewPageLink='div.post-publish-panel__postpublish-buttons > a';
    public $wpadcenterAd='#wpadcenter_ad';

    /**
     * Test to create page by adding WPAdCenterSingleAdBlock 
     *
     * @param $I All the functions related to codeception
     * 
     * @return void
     * 
     * @since 1.0
     */
    public function createPageByAddingWPAdCenterSingleAdBlock($I)
    {
        $I->click($this->pages_MainMenuLink);
        $I->click($this->addNewPage_SubmenuLink);
        $I->wait(2);
        $I->click($this->welcomeToBlockEditorPopUpCloseBtn);
        $I->waitForElement($this->pageTitleField, 20);
        $I->fillField($this->pageTitleField, $this->pageTitleValue);
        $I->click($this->addBlockBtn);
        $I->fillField($this->wpadcenterSearchBlockField, $this->wpadcenterSearchBlockValue);
        $I->click($this->wpadcenterBlockSelect);
        $I->wait(2);
        $I->click($this->selectAdDropdownBtn);
        $I->wait(2);
        $I->click($this->selectAd);
        $I->wait(3);
        $I->click($this->publishPageBtn1);
        $I->click($this->publishPageBtn2);
        $I->wait(3);
        $I->click($this->viewPageLink);
        $I->wait(5);
        $I->scrollTo($this->wpadcenterAd, 20);
        $I->wait(2);
        $I->seeElement($this->wpadcenterAd);
        $I->click($this->wpadcenterAd);
        $I->wait(4);
        $I->moveBack();
        $I->wait(2);
    }

    public $wordPressBtn='#wp-admin-bar-site-name > a';
    public $moveToTrashBtn='.components-button.editor-post-trash.is-tertiary.is-destructive';
    public $trashLinkInPages='#wpbody-content > div.wrap > ul > li.trash > a';
    public $emptyTrashBtn='//*[@id="delete_all"]';

    /**
     * Test to move the page in to trash
     *
     * @param $I All the functions related to codeception
     * 
     * @return void
     * 
     * @since 1.0
     */
    public function movePageToTrash($I)
    {
        $I->click($this->wordPressBtn);
        $I->wait(3);
        $I->click($this->pages_MainMenuLink);
        $I->click($this->pageTitleValue);
        $I->waitForElement($this->moveToTrashBtn, 20);
        $I->click($this->moveToTrashBtn);
        $I->waitForElement($this->trashLinkInPages, 20);
        $I->click($this->trashLinkInPages);
        $I->click($this->emptyTrashBtn);
    }

    public $wpadcenterAdGroupSelect='button.components-button.block-editor-block-types-list__item.editor-block-list-item-wpadcenter-adgroup';
    
     /**
      * Test to create page by adding WPAdCenterAdGroupBlock
      *
      * @param $I All the functions related to codeception
      * 
      * @return void
      * 
      * @since 1.0
      */
    public function createPageByAddingWPAdCenterAdGroupBlock($I)
    {
        $I->click($this->pages_MainMenuLink);
        $I->click($this->addNewPage_SubmenuLink);
        $I->wait(2);
        $I->fillField($this->pageTitleField, $this->pageTitleValue);
        $I->click($this->addBlockBtn);
        $I->fillField($this->wpadcenterSearchBlockField, $this->wpadcenterSearchBlockValue);
        $I->click($this->wpadcenterAdGroupSelect);
        $I->wait(2);
        $I->click($this->selectAdDropdownBtn);
        $I->wait(2);
        $I->click($this->selectAd);
        $I->wait(3);
        $I->click($this->publishPageBtn1);
        $I->click($this->publishPageBtn2);
        $I->wait(3);
        $I->click($this->viewPageLink);
        $I->wait(5);
        $I->scrollTo($this->wpadcenterAd, 20);
        $I->wait(2);
        $I->seeElement($this->wpadcenterAd);
        $I->click($this->wpadcenterAd);
        $I->wait(4);
        $I->moveBack();
        $I->wait(2);
    }
    
    public $adcolumntitle='td.title.column-title';
    public $trashbutton='span.trash';

     /**
      * Test to move the Ad in to trash
      *
      * @param $I All the functions related to codeception
      * 
      * @return void
      * 
      * @since 1.0
      */
    public function moveAdToTrash($I)
    {
        $I->waitForElement($this->wpadcenterMenuBtn, 20);
        $I->click($this->wpadcenterMenuBtn);
        $I->waitForElement($this->adcolumntitle, 20);
        $I->moveMouseOver($this->adcolumntitle);
        $I->click($this->trashbutton);
    }

    public $createdAdGroupName='td.name.column-name.has-row-actions.column-primary';
    public $deleteAdGroup='div.row-actions > span.delete > a';

     /**
      * Test to move the AdGroup in to trash
      *
      * @param $I All the functions related to codeception
      * 
      * @return void
      * 
      * @since 1.0
      */
    public function moveAdGroupToTrash($I)
    {
        $I->waitForElement($this->manageAdGroupsSubMenu, 20);
        $I->click($this->manageAdGroupsSubMenu);
        $I->waitForElement($this->createdAdGroupName, 20);
        $I->moveMouseOver($this->createdAdGroupName);
        $I->click($this->deleteAdGroup);
        $I->seeInPopup('You are about to permanently delete');
        $I->acceptPopup();
        $I->wait(2);
    }

    public $shortcode='td.shortcode.column-shortcode > a';
    public $wpadcenterSearchBlockValueForShortcode='shortcode';
    public $textareaForAddingShortcode='textarea.block-editor-plain-text.blocks-shortcode__textarea';

    public $adtypeAdCode='#ad-type > option:nth-child(4)';
    public $AdCodeField='#ad-code > div.inside > textarea';
    public $AdCodeValue='<script>
    alert( \'Demo AdCode!\' );
    </script>';
     
    /**
     * Test to create page with AdCode Using SingleAdBlock
     *
     * @param $I All the functions related to codeception
     * 
     * @return void
     * 
     * @since 1.0
     */
    public function createPageWithAdCodeUsingSingleAdBlock($I)
    {
        $I->click($this->pages_MainMenuLink);
        $I->click($this->addNewPage_SubmenuLink);
        $I->wait(2);
        $I->click($this->welcomeToBlockEditorPopUpCloseBtn);
        $I->fillField($this->pageTitleField, $this->pageTitleValue);
        $I->click($this->addBlockBtn);
        $I->fillField($this->wpadcenterSearchBlockField, $this->wpadcenterSearchBlockValue);
        $I->click($this->wpadcenterBlockSelect);
        $I->wait(2);
        $I->click($this->selectAdDropdownBtn);
        $I->wait(2);
        $I->click($this->selectAd);
        $I->wait(3);
        $I->click($this->publishPageBtn1);
        $I->click($this->publishPageBtn2);
        $I->wait(3);
        $I->click($this->viewPageLink);
        $I->wait(2);
        $I->seeInPopup('Demo AdCode!');
        $I->acceptPopup();
        $I->wait(2);
    }

     /**
      * Test to create page with AdCode Using SingleAdGroupBlock
      *
      * @param $I All the functions related to codeception
      * 
      * @return void
      * 
      * @since 1.0
      */
    public function createPageWithAdCodeUsingAdGroupBlockBlock($I)
    {
        $I->click($this->pages_MainMenuLink);
        $I->click($this->addNewPage_SubmenuLink);
        $I->wait(2);
        $I->fillField($this->pageTitleField, $this->pageTitleValue);
        $I->click($this->addBlockBtn);
        $I->fillField($this->wpadcenterSearchBlockField, $this->wpadcenterSearchBlockValue);
        $I->click($this->wpadcenterAdGroupSelect);
        $I->wait(2);
        $I->click($this->selectAdDropdownBtn);
        $I->wait(2);
        $I->click($this->selectAd);
        $I->wait(3);
        $I->click($this->publishPageBtn1);
        $I->click($this->publishPageBtn2);
        $I->wait(3);
        $I->click($this->viewPageLink);
        $I->wait(2);
        $I->seeInPopup('Demo AdCode!');
        $I->acceptPopup();
        $I->wait(2);
    }

    public $adtypeAMPAd='#ad-type > option:nth-child(6)';
    public $removeAttributeBtn='button.wpadcenter-amp-delete-attr-button';
    public $addAttribute_Btn='button.button-secondary.wpadcenter-amp-add-attr-btn';
    public $attributeField1='#wpadcenter-amp-attributes-container > div:nth-child(1) > input:nth-child(2)';
    public $attributeValue1='type';
    public $attributeValueField1='#wpadcenter-amp-attributes-container > div:nth-child(1) > input:nth-child(4)';
    public $attributeValueValue1='a9';
    public $attributeField2='#wpadcenter-amp-attributes-container > div:nth-child(2) > input:nth-child(2)';

    public $attributeValue2='data-amzn_assoc_ad_mode';
    public $attributeValueField2='#wpadcenter-amp-attributes-container > div:nth-child(2) > input:nth-child(4)';
    public $attributeValueValue2='auto';
    public $attributeField3='#wpadcenter-amp-attributes-container > div:nth-child(3) > input:nth-child(2)';

    public $attributeValue3='data-divid';
    public $attributeValueField3='#wpadcenter-amp-attributes-container > div:nth-child(3) > input:nth-child(4)';
    public $attributeValueValue3='amzn-assoc-ad-fe746097-f142-4f8d-8dfb-45ec747632e5';
    public $attributeField4='#wpadcenter-amp-attributes-container > div:nth-child(4) > input:nth-child(2)';

    public $attributeValue4='data-recomtype';
    public $attributeValueField4='#wpadcenter-amp-attributes-container > div:nth-child(4) > input:nth-child(4)';
    public $attributeValueValue4='async';
    public $attributeField5='#wpadcenter-amp-attributes-container > div:nth-child(5) > input:nth-child(2)';

    public $attributeValue5='data-adinstanceid';
    public $attributeValueField5='#wpadcenter-amp-attributes-container > div:nth-child(5) > input:nth-child(4)';
    public $attributeValueValue5='fe746097-f142-4f8d-8dfb-45ec747632e5';
    public $attributeField6='#wpadcenter-amp-attributes-container > div:nth-child(6) > input:nth-child(2)';

    public $attributeValue6='width';
    public $attributeValueField6='#wpadcenter-amp-attributes-container > div:nth-child(6) > input:nth-child(4)';
    public $attributeValueValue6='300';
    public $attributeField7='#wpadcenter-amp-attributes-container > div:nth-child(7) > input:nth-child(2)';

    public $attributeValue7='height';
    public $attributeValueField7='#wpadcenter-amp-attributes-container > div:nth-child(7) > input:nth-child(4)';
    public $attributeValueValue7='250';
    public $attributeField8='#wpadcenter-amp-attributes-container > div:nth-child(8) > input:nth-child(2)';

    public $attributeValue8='data-aax_size';
    public $attributeValueField8='#wpadcenter-amp-attributes-container > div:nth-child(8) > input:nth-child(4)';
    public $attributeValueValue8='300x250';
    public $attributeField9='#wpadcenter-amp-attributes-container > div:nth-child(9) > input:nth-child(2)';

    public $attributeValue9='data-aax_pubname';
    public $attributeValueField9='#wpadcenter-amp-attributes-container > div:nth-child(9) > input:nth-child(4)';
    public $attributeValueValue9='test123';
    public $attributeField10='#wpadcenter-amp-attributes-container > div:nth-child(10) > input:nth-child(2)';

    public $attributeValue10='data-aax_src';
    public $attributeValueField10='#wpadcenter-amp-attributes-container > div:nth-child(10) > input:nth-child(4)';
    public $attributeValueValue10='302';
    public $placeholderField='#amp-attributes > div.inside > input:nth-child(9)';
    public $placeholderValue='wait loading.....';
    public $fallbackField='#amp-attributes > div.inside > input:nth-child(17)';
    public $fallbackValue='falling back....';
    public $AMP='#wp-admin-bar-amp > a';
    public $ViewAMPVersion='#wp-admin-bar-amp-view > a';
    public $ViewNonAMPVersion='#wp-admin-bar-amp-view > a';

    /**
     * Test to create page by adding AMPAd Using SingleAdBlock
     *
     * @param $I All the functions related to codeception
     * 
     * @return void
     * 
     * @since 1.0
     */
    public function createPageByAddingAMPAdSingleAdBlock($I)
    {
        $I->click($this->pages_MainMenuLink);
        $I->click($this->addNewPage_SubmenuLink);
        $I->wait(2);
        $I->click($this->welcomeToBlockEditorPopUpCloseBtn);
        $I->fillField($this->pageTitleField, $this->pageTitleValue);
        $I->click($this->addBlockBtn);
        $I->fillField($this->wpadcenterSearchBlockField, $this->wpadcenterSearchBlockValue);
        $I->click($this->wpadcenterBlockSelect);
        $I->wait(2);
        $I->click($this->selectAdDropdownBtn);
        $I->wait(2);
        $I->click($this->selectAd);
        $I->wait(3);
        $I->click($this->publishPageBtn1);
        $I->click($this->publishPageBtn2);
        $I->wait(3);
        $I->click($this->viewPageLink);
        $I->wait(3);
        $I->moveMouseOver($this->AMP);
        $I->wait(1);
        $I->click($this->ViewAMPVersion);
        $I->wait(5);
        $I->scrollTo($this->wpadcenterAd, 20);
        $I->wait(3);
        $I->moveMouseOver($this->AMP);
        $I->wait(1);
        $I->click($this->ViewNonAMPVersion);
    }

    /**
     * Test to create page by adding AMPAd Using SingleAdGroupBlock
     *
     * @param $I All the functions related to codeception
     * 
     * @return void
     * 
     * @since 1.0
     */
    public function createPageByAddingAMPAdAdGroupBlock($I)
    {
        $I->click($this->pages_MainMenuLink);
        $I->click($this->addNewPage_SubmenuLink);
        $I->wait(2);
        $I->fillField($this->pageTitleField, $this->pageTitleValue);
        $I->click($this->addBlockBtn);
        $I->fillField($this->wpadcenterSearchBlockField, $this->wpadcenterSearchBlockValue);
        $I->click($this->wpadcenterAdGroupSelect);
        $I->wait(2);
        $I->click($this->selectAdDropdownBtn);
        $I->wait(2);
        $I->click($this->selectAd);
        $I->wait(3);
        $I->click($this->publishPageBtn1);
        $I->click($this->publishPageBtn2);
        $I->wait(3);
        $I->click($this->viewPageLink);
        $I->wait(3);
        $I->moveMouseOver($this->AMP);
        $I->wait(1);
        $I->click($this->ViewAMPVersion);
        $I->wait(5);
        $I->scrollTo($this->wpadcenterAd, 20);
        $I->wait(3);
        $I->moveMouseOver($this->AMP);
        $I->wait(1);
        $I->click($this->ViewNonAMPVersion);
    }

    public $adtypeTextAd='#ad-type > option:nth-child(3)';
    public $textAdEditorBodySelector='#tinymce';
    public $textAdEditorPTagSelector='#tinymce > p';
    public $wpadcenterContainer='div.wpadcenter-ad-container';
}
