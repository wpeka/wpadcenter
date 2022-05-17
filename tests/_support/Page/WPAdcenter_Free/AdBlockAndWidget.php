<?php
/**
 * Selectors used in the automation testcases for AdBlock and Widget of WPAdcenter free plugin
 * 
 * @category AutomationTests
 * @package  WordPress_WPAdcenter_Free_Plugin
 * @author   WPEKA <hello@wpeka.com>
 * @license  GPL v3
 * @link     https://club.wpeka.com
 */
namespace Page\WPAdcenter_Free;

/**
 * Core class for all the selectors used in automation testcases for AdBlock and Widget of WPAdcenter free plugin
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
class AdBlockAndWidget
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
    public $adTitleValue2='Banner Ad';
    public $adtypeExternalImageLink='#ad-type > option:nth-child(2)';
    public $adsizesmallsquare='#size > optgroup:nth-child(2) > option:nth-child(1)';
    public $externalImageField='#external-image-link > div.inside > input[type=text]';
    public $externalImageLinkValue='https://picsum.photos/200/300';
    public $linkUrlField='#link-url';
    public $linkUrlValue='https://www.google.co.in';
    public $adGroupSelectCheckbox='#wpadcenter-adgroupschecklist > li > label > input';
    public $createadtext='#wpbody-content > div.wrap > h1';
    public $publishBtn='#publish';
    public $adtypeBannerImage='#ad-type > option:nth-child(1)';
    public $setAdImage='#set-post-thumbnail';
    public $mediaLibrary='#menu-item-browse';
    public $selectImage='#__wp-uploader-id-0 > div.media-frame-tab-panel > div.media-frame-content > div > div.attachments-wrapper > ul > li > div > div';
    public $setAdImageBtn='#__wp-uploader-id-0 > div.media-frame-toolbar > div > div.media-toolbar-primary.search-form > button';

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
    public $wpadcenterSearchBlockValue='WPAdCenter Ad Block';
    public $wpadcenterBlockSelect='div.block-editor-block-types-list__list-item > button:nth-child(1)';
    public $selectAdType='#editor > div > div.edit-post-layout.is-mode-visual.has-metaboxes.interface-interface-skeleton.has-footer > div.interface-interface-skeleton__editor > div.interface-interface-skeleton__body > div.interface-interface-skeleton__content > div.edit-post-visual-editor > div.edit-post-visual-editor__content-area > div > div.editor-styles-wrapper.block-editor-writing-flow > div.is-root-container.block-editor-block-list__layout > div.block-editor-block-list__block.wp-block.is-selected > div > div > div.components-placeholder__fieldset.is-column-layout > div > div > div > div.css-1wy0on6 > div > svg';
    public $adTypeValue='#editor > div > div.edit-post-layout.is-mode-visual.has-metaboxes.interface-interface-skeleton.has-footer > div.interface-interface-skeleton__editor > div.interface-interface-skeleton__body > div.interface-interface-skeleton__content > div.edit-post-visual-editor > div.edit-post-visual-editor__content-area > div > div.editor-styles-wrapper.block-editor-writing-flow > div.is-root-container.block-editor-block-list__layout > div.block-editor-block-list__block.wp-block.is-selected > div > div > div.components-placeholder__fieldset.is-column-layout > div:nth-child(2) > div > div:nth-child(3) > div > div:nth-child(1)';
    public $selectAdDropdownBtn='#editor > div > div.edit-post-layout.is-mode-visual.has-metaboxes.interface-interface-skeleton.has-footer > div.interface-interface-skeleton__editor > div.interface-interface-skeleton__body > div.interface-interface-skeleton__content > div.edit-post-visual-editor > div.edit-post-visual-editor__content-area > div > div.editor-styles-wrapper.block-editor-writing-flow > div.is-root-container.block-editor-block-list__layout > div.block-editor-block-list__block.wp-block.is-selected > div > div > div.components-placeholder__fieldset.is-column-layout > div:nth-child(3) > div:nth-child(1) > div:nth-child(2) > div > div > div.css-1wy0on6 > div > svg';
    public $selectAd='#editor > div > div.edit-post-layout.is-mode-visual.has-metaboxes.interface-interface-skeleton.has-footer > div.interface-interface-skeleton__editor > div.interface-interface-skeleton__body > div.interface-interface-skeleton__content > div.edit-post-visual-editor > div.edit-post-visual-editor__content-area > div > div.editor-styles-wrapper.block-editor-writing-flow > div.is-root-container.block-editor-block-list__layout > div.block-editor-block-list__block.wp-block.is-selected > div > div > div.components-placeholder__fieldset.is-column-layout > div:nth-child(3) > div:nth-child(1) > div:nth-child(2) > div > div:nth-child(3) > div > div';
    public $selectAlignment= '#editor > div > div.edit-post-layout.is-mode-visual.has-metaboxes.interface-interface-skeleton.has-footer > div.interface-interface-skeleton__editor > div.interface-interface-skeleton__body > div.interface-interface-skeleton__content > div.edit-post-visual-editor > div.edit-post-visual-editor__content-area > div > div.editor-styles-wrapper.block-editor-writing-flow > div.is-root-container.block-editor-block-list__layout > div.block-editor-block-list__block.wp-block.is-selected > div > div > div.components-placeholder__fieldset.is-column-layout > div:nth-child(3) > div:nth-child(1) > div:nth-child(3) > div > div > button:nth-child(3)';
    public $enableMaxWidth = '#inspector-checkbox-control-1';
    public $publishPageBtn1='.components-button.editor-post-publish-panel__toggle.editor-post-publish-button__button.is-primary';
    public $publishPageBtn2='.components-button.editor-post-publish-button.editor-post-publish-button__button.is-primary';
    public $viewPageLink='div.post-publish-panel__postpublish-buttons > a';
    public $wordPressBtn='#wp-admin-bar-site-name > a';
    public $moveToTrashBtn='.components-button.editor-post-trash.is-tertiary.is-destructive';
    public $trashLinkInPages='#wpbody-content > div.wrap > ul > li.trash > a';
    public $emptyTrashBtn='//*[@id="delete_all"]';
    public $adTypeValueAsAdgroup = '#editor > div > div.edit-post-layout.is-mode-visual.is-sidebar-opened.has-metaboxes.interface-interface-skeleton.has-footer > div.interface-interface-skeleton__editor > div.interface-interface-skeleton__body > div.interface-interface-skeleton__content > div.edit-post-visual-editor > div.edit-post-visual-editor__content-area > div > div.editor-styles-wrapper.block-editor-writing-flow > div.is-root-container.block-editor-block-list__layout > div.block-editor-block-list__block.wp-block.is-selected > div > div > div.components-placeholder__fieldset.is-column-layout > div > div > div:nth-child(3) > div > div:nth-child(2)';
    public $selectAdGroupDropdownBtn = '#editor > div > div.edit-post-layout.is-mode-visual.is-sidebar-opened.has-metaboxes.interface-interface-skeleton.has-footer > div.interface-interface-skeleton__editor > div.interface-interface-skeleton__body > div.interface-interface-skeleton__content > div.edit-post-visual-editor > div.edit-post-visual-editor__content-area > div > div.editor-styles-wrapper.block-editor-writing-flow > div.is-root-container.block-editor-block-list__layout > div.block-editor-block-list__block.wp-block.is-selected > div > div > div.components-placeholder__fieldset.is-column-layout > div:nth-child(3) > div:nth-child(1) > div > div > div > div > div.css-1wy0on6 > div > svg';
    public $selectAdGroup = '#editor > div > div.edit-post-layout.is-mode-visual.is-sidebar-opened.has-metaboxes.interface-interface-skeleton.has-footer > div.interface-interface-skeleton__editor > div.interface-interface-skeleton__body > div.interface-interface-skeleton__content > div.edit-post-visual-editor > div.edit-post-visual-editor__content-area > div > div.editor-styles-wrapper.block-editor-writing-flow > div.is-root-container.block-editor-block-list__layout > div.block-editor-block-list__block.wp-block.is-selected > div > div > div.components-placeholder__fieldset.is-column-layout > div:nth-child(3) > div:nth-child(1) > div > div > div > div:nth-child(3) > div > div';
    public $selectAlignmentForAdGroup = '#editor > div > div.edit-post-layout.is-mode-visual.is-sidebar-opened.has-metaboxes.interface-interface-skeleton.has-footer > div.interface-interface-skeleton__editor > div.interface-interface-skeleton__body > div.interface-interface-skeleton__content > div.edit-post-visual-editor > div.edit-post-visual-editor__content-area > div > div.editor-styles-wrapper.block-editor-writing-flow > div.is-root-container.block-editor-block-list__layout > div.block-editor-block-list__block.wp-block.is-selected > div > div > div.components-placeholder__fieldset.is-column-layout > div:nth-child(3) > div:nth-child(3) > div:nth-child(1) > div > div > button:nth-child(4)';
    public $enableMaxWidthForAdgroup = '#editor > div > div.edit-post-layout.is-mode-visual.is-sidebar-opened.has-metaboxes.interface-interface-skeleton.has-footer > div.interface-interface-skeleton__editor > div.interface-interface-skeleton__body > div.interface-interface-skeleton__content > div.edit-post-visual-editor > div.edit-post-visual-editor__content-area > div > div.editor-styles-wrapper.block-editor-writing-flow > div.is-root-container.block-editor-block-list__layout > div.block-editor-block-list__block.wp-block.is-selected > div > div > div.components-placeholder__fieldset.is-column-layout > div:nth-child(3) > div.wpadcenter-maxwidth-container > div > div > div > span';
    public $adTypeValueAsRandomAd='#editor > div > div.edit-post-layout.is-mode-visual.is-sidebar-opened.has-metaboxes.interface-interface-skeleton.has-footer > div.interface-interface-skeleton__editor > div.interface-interface-skeleton__body > div.interface-interface-skeleton__content > div.edit-post-visual-editor > div.edit-post-visual-editor__content-area > div > div.editor-styles-wrapper.block-editor-writing-flow > div.is-root-container.block-editor-block-list__layout > div.block-editor-block-list__block.wp-block.is-selected > div > div > div.components-placeholder__fieldset.is-column-layout > div:nth-child(2) > div > div:nth-child(3) > div > div:nth-child(3)';
    public $selectAlignmentForRandomAd='#editor > div > div.edit-post-layout.is-mode-visual.is-sidebar-opened.has-metaboxes.interface-interface-skeleton.has-footer > div.interface-interface-skeleton__editor > div.interface-interface-skeleton__body > div.interface-interface-skeleton__content > div.edit-post-visual-editor > div.edit-post-visual-editor__content-area > div > div.editor-styles-wrapper.block-editor-writing-flow > div.is-root-container.block-editor-block-list__layout > div.block-editor-block-list__block.wp-block.is-selected > div > div > div.components-placeholder__fieldset.is-column-layout > div:nth-child(3) > div:nth-child(3) > div > div > button:nth-child(2)';
    public $enableMaxWidthForRandomAd='#inspector-checkbox-control-1';

    /**
     * Test to move page to trash
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

    public $adcolumntitle='td.title.column-title';
    public $trashbutton='span.trash';

     /**
      * Test to move ad to trash
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
      * Test to move ad group to trash
      *
      * @param $I All the functions related to codeception
      * 
      * @return void
      * 
      * @since 1.0
      */
    public function moveAdGroupToTrash($I)
    {
        $I->waitForElement($this->wpadcenterMenuBtn, 20);
        $I->click($this->wpadcenterMenuBtn);
        $I->waitForElement($this->manageAdGroupsSubMenu, 20);
        $I->click($this->manageAdGroupsSubMenu);
        $I->waitForElement($this->createdAdGroupName, 20);
        $I->moveMouseOver($this->createdAdGroupName);
        $I->click($this->deleteAdGroup);
        $I->seeInPopup('You are about to permanently delete');
        $I->acceptPopup();
        $I->wait(2);
    } 
    
    public $elementorBtn='#elementor-switch-mode-button > span.elementor-switch-mode-off';
    public $searchWidget='#elementor-panel-elements-search-input';
    public $searchWidgetValue='Ad Widget';
    public $tools = '//li[@id="menu-tools"]';
    public $importBtn = '//*[text()="Import"]';
    public $runImporterBtn = '//a[@aria-label="Run WordPress"]';
    public $uploadBtn = '//input[@id="upload"]';
    public $importFileBtn = '//*[@value="Upload file and import"]';
    public $importAttachmentsCheck = '//input[@id="import-attachments"]';
    public $submitBtn = '//input[@value="Submit"]';
    public $editWithElementorBtn = '#wp-admin-bar-elementor_edit_page';

     /**
      * Test to import pages
      *
      * @param $I        All the functions related to codeception
      * @param $filename Specifies name of the file 
      * 
      * @return void
      * 
      * @since 1.0
      */
    public function importPages($I, $filename)
    {
        $I->click($this->tools);
        $I->click($this->importBtn);
        $I->scrollTo($this->runImporterBtn, 20);
        $I->wait(1);
        $I->click($this->runImporterBtn);
        $I->wait(1);
        $I->attachFile($this->uploadBtn, $filename);
        $I->wait(1);
        $I->click($this->importFileBtn);
        $I->wait(1);
        $I->click($this->importAttachmentsCheck);
        $I->click($this->submitBtn);
        $I->wait(1);
    }

    public $blockIframe = '#elementor-preview-iframe';
    public $blockContainer = '(//div[contains(@class," elementor-widget ")])[1]';

    /**
     * Test to click on widget button
     *
     * @param $I All the functions related to codeception
     *
     * @return void
     * 
     * @since 1.0
     */
    public function clickWiget($I)
    {
        $I->switchToIFrame($this->blockIframe);
        $I->click($this->blockContainer);
        $I->wait(1);
        $this->seeCommonPanel($I);
    }

    /**
     * Test to see pannel 
     *
     * @param $I All the functions related to codeception
     *
     * @return void
     * 
     * @since 1.0
     */
    public function seeCommonPanel($I)
    {
        $I->switchToIFrame();
        $I->wait(1);
        $I->see('Content');
        $I->see('Advanced');
    }

    public $selectAdTypeForElementor='#elementor-controls > div.elementor-control.elementor-control-ad_type.elementor-control-type-select.elementor-label-inline.elementor-control-separator-default > div > div > div > select';
    public $selectAdTypeValueForElementor='#elementor-controls > div.elementor-control.elementor-control-ad_type.elementor-control-type-select.elementor-label-inline.elementor-control-separator-default > div > div > div > select > option:nth-child(1)';
    public $selectAdTypeValueForElementor2='#elementor-controls > div.elementor-control.elementor-control-ad_type.elementor-control-type-select.elementor-label-inline.elementor-control-separator-default > div > div > div > select > option:nth-child(2)';
    public $selectAdTypeValueForElementor3='#elementor-controls > div.elementor-control.elementor-control-ad_type.elementor-control-type-select.elementor-label-inline.elementor-control-separator-default > div > div > div > select > option:nth-child(3)';
    public $selectAdForElementor = '#elementor-controls > div.elementor-control.elementor-control-ad_id.elementor-control-type-select.elementor-label-inline.elementor-control-separator-default > div > div > div > select';
    public $selectAdValueForElementor = '#elementor-controls > div.elementor-control.elementor-control-ad_id.elementor-control-type-select.elementor-label-inline.elementor-control-separator-default > div > div > div > select > option';
    public $selectAdgroupForElementor='#elementor-controls > div.elementor-control.elementor-control-adgroup_ids.elementor-control-type-select2.elementor-label-inline.elementor-control-separator-default > div > div > div > span > span.selection > span > ul';
    public $selectAdgroupValueForElementor='body > span > span > span > ul > li';
    public $alignment1 = '#elementor-controls > div.elementor-control.elementor-control-alignment.elementor-control-type-choose.elementor-label-inline.elementor-control-separator-default > div > div > div > div > label:nth-child(4)';
    public $alignment2 ='#elementor-controls > div.elementor-control.elementor-control-alignment.elementor-control-type-choose.elementor-label-inline.elementor-control-separator-default > div > div > div > div > label:nth-child(2)';
    public $alignment3 = '#elementor-controls > div.elementor-control.elementor-control-alignment.elementor-control-type-choose.elementor-label-inline.elementor-control-separator-default > div > div > div > div > label:nth-child(6)';
    public $maxWidth = '#elementor-controls > div.elementor-control.elementor-control-max_width.elementor-control-type-switcher.elementor-label-inline.elementor-control-separator-default > div > div > div > label > span.elementor-switch-label';
    public $updateBtn= '#elementor-panel-saver-button-publish';
}
