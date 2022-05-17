<?php
/**
 * Selectors used in the automation testcases for activation and deactivation page of WPAdcenter free plugin
 * 
 * @category AutomationTests
 * @package  WordPress_WPAdcenter_Free_Plugin
 * @author   WPEKA <hello@wpeka.com>
 * @license  GPL v3
 * @link     https://club.wpeka.com
 */
namespace Page\WPAdcenter_Free;

/**
 * Core class for all the selectors used in automation testcases for activation and deactivation page of WPAdcenter free plugin
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
class ActivationDeactivation
{
    public $pluginsMenu = '#menu-plugins > a > div.wp-menu-name';
    public $searchInstalledPluginField='#plugin-search-input';
    public $searchInstalledPluginValue='wpadcenter';
    public $activateButton='#activate-wpadcenter';
    public $helpUsImproveBannerCloseBtn='.notice-dismiss';  

    public $upgradeToProLink='#the-list > tr > td.plugin-title.column-primary > div > span > a';
    public $wpekaClubLink='#the-list > tr > td.column-description.desc > div.active.is-uninstallable.second.plugin-version-author-uri > a:nth-child(1)';

    public $activateTransientsManager='#activate-transients-manager';
    public $toolsMenu='#menu-tools > a > div.wp-menu-name';
    public $transientsSubMenu='#menu-tools > ul > li:nth-child(8) > a';
    public $searchTransientField='#transient-search-input';
    public $searchTransientValue='wpadcenter-ask-for-review-flag';
    public $searchTransientButton='#search-submit';
    public $wplegalpages_ask_for_review_Selector='#transients-delete > table > tbody > tr > td.column-primary';
    public $deleteTransientBtn='#transients-delete > table > tbody > tr > td.column-primary > div > span.delete';
    public $searchTransientManagerPluginValue='Transients Manager';
    public $deactivateButton='#the-list > tr > td.plugin-title.column-primary > div > span.deactivate';
    public $cancelFeebackButton='body > div.as-modal.as-modal-deactivation-feedback.no-confirmation-message.active > div > div.as-modal-footer > a.button.button-primary.button-close';
}
