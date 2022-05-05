<?php
/**
 * Selectors used in the automation testcases for settings menu of WPAdcenter free plugin
 * 
 * @category AutomationTests
 * @package  WordPress_WPAdcenter_Free_Plugin
 * @author   WPEKA <hello@wpeka.com>
 * @license  GPL v3
 * @link     https://club.wpeka.com
 */
namespace Page\WPAdcenter_Free;

/**
 * Core class of all the selectors used in automation testcases for settings menu of WPAdcenter free plugin
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
class SettingsMenu
{
    public $wpadcenterMenuBtn='#menu-posts-wpadcenter-ads > a > div.wp-menu-name';
    public $settingsSubMenu='#menu-posts-wpadcenter-ads > ul > li:nth-child(6) > a';
    public $rolsesToExcludeFromSelector='#vs1__combobox > div.vs__actions';
    public $administratorSelected='#vs1__listbox > li:nth-child(1)';
    public $saveChangesButton='.btn.wpadcenter_save.btn-info';
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
    public $viewPageLink='div.post-publish-panel__postpublish-buttons > a';
    public $addBlockBtn='.components-button.block-editor-inserter__toggle.has-icon';
    public $wpadcenterSearchBlockField='input.components-search-control__input';
    public $wpadcenterSearchBlockValue='WPAdCenter';
    public $wpadcenterBlockSelect='div.block-editor-block-types-list__list-item > button:nth-child(1)';
    public $selectAdDropdownBtn='div.components-placeholder__fieldset.is-column-layout > div:nth-child(2) > div > div > div:nth-child(2)';
    public $selectAd='div.wpadcenter-async-select > div.css-duvieu-menu';
    public $publishPageBtn1='.components-button.editor-post-publish-panel__toggle.editor-post-publish-button__button.is-primary';
    public $publishPageBtn2='.components-button.editor-post-publish-button.editor-post-publish-button__button.is-primary';
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

    public $reportsSubMenu='#menu-posts-wpadcenter-ads > ul > li:nth-child(5) > a';
    public $totalViewsSelector='#reports > div > div:nth-child(2) > div > div.tab-pane.active > div.card > div > div > div:nth-child(2) > div > strong';
    public $administratorDeselected='#vs1__combobox > div.vs__selected-options > span > button';
    public $adcolumntitle='td.title.column-title';
    public $trashbutton='span.trash';

    /**
     * Test to move page to trash
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
        $I->waitForElement($this->manageAdGroupsSubMenu, 20);
        $I->click($this->manageAdGroupsSubMenu);
        $I->waitForElement($this->createdAdGroupName, 20);
        $I->moveMouseOver($this->createdAdGroupName);
        $I->click($this->deleteAdGroup);
        $I->seeInPopup('You are about to permanently delete');
        $I->acceptPopup();
        $I->wait(2);
    }

    public $scriptsTab='#wpadcenter_tabs > div:nth-child(1) > ul > li:nth-child(2) > a';
    public $enableScriptsButton='#wpadcenter_tabs > div:nth-child(2) > div > div.tab-pane.active > div > div > label > span';
    public $headerScriptsField='#header_scripts_field';
    public $headerScriptsValue='<script>
    alert( \'Demo AdCode!\' );
    </script>';
    public $linkOptionTextSelector='#wpadcenter_tabs > div:nth-child(2) > div > div.tab-pane.active > div:nth-child(4) > header';
    public $openInNextTabButton='#wpadcenter_tabs > div:nth-child(2) > div > div.tab-pane.active > div:nth-child(4) > div > label:nth-child(2) > span';
    public $noFollowOnLinkButton='#wpadcenter_tabs > div:nth-child(2) > div > div.tab-pane.active > div:nth-child(4) > div > label:nth-child(4) > span';
    public $additionalRelAttributesButton='#vs2__combobox > div.vs__actions';
    public $sponsoredSelected='#vs2__listbox > li:nth-child(1)';
    public $ugcSelected='#vs2__listbox > li:nth-child(2)';
    public $attributeDeselectButton='#vs2__combobox > div.vs__selected-options > span:nth-child(1) > button';
    public $additionalCSSClassField='#wpadcenter-additional-css-class';
    public $additionalCSSClassValue='Demo-class';
    public $pluginsMenu='#menu-plugins > a > div.wp-menu-name';
    public $searchInstalledPluginField='#plugin-search-input';
    public $searchGDPRPluginVlaue='GDPR';
    public $activateGDPRButton='#activate-gdpr-cookie-consent';
    public $privacyOptionsTextSelector='#wpadcenter_tabs > div:nth-child(2) > div > div.tab-pane.active > div:nth-child(5) > header';
    public $enablePrivacyPolicyButton='#wpadcenter_tabs > div:nth-child(2) > div > div.tab-pane.active > div:nth-child(5) > div > label';
    public $deactivateButton='#the-list > tr > td.plugin-title.column-primary > div > span.deactivate';
    public $cookieRadioButton='#cookie-consent';
    public $cookieNameField='#wpadcenter_tabs > div:nth-child(2) > div > div.tab-pane.active > div:nth-child(5) > div > div.enable_privacy_enabled > div.cookie_method > div.cookie_options > div:nth-child(2) > input';
    public $cookieNameValue='wpl_viewed_cookie';
    public $cookieValueField='#wpadcenter_tabs > div:nth-child(2) > div > div.tab-pane.active > div:nth-child(5) > div > div.enable_privacy_enabled > div.cookie_method > div.cookie_options > div:nth-child(4) > input';
    public $cookieValueValue='yes';
    public $acceptCookieButton = '#cookie_action_accept';
}
