<?php
/**
 * Selectors used in the automation testcases for getting started menu of WPAdcenter free plugin
 * 
 * @category AutomationTests
 * @package  WordPress_WPAdcenter_Free_Plugin
 * @author   WPEKA <hello@wpeka.com>
 * @license  GPL v3
 * @link     https://club.wpeka.com
 */
namespace Page\WPAdcenter_Free;

/**
 * Core class for all the selectors used in getting started menu of WPAdcenter free plugin
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
class GettingStartedMenu
{
    public $wpadcenterMenuBtn='#menu-posts-wpadcenter-ads > a > div.wp-menu-name';  
    public $gettingStartedPage='#menu-posts-wpadcenter-ads > ul > li:nth-child(7) > a';
    public $configureWPADcenterButton='div.adc-container > div.adc-container-main > div.adc-settings-section > div > a';
    public $rolsesToExcludeFromSelector='#vs1__combobox > div.vs__actions';
    public $wpquicklinksbutton='div.adc-mascot-quick-links > button';
    public $supportButton='div.adc-mascot-quick-links-menu > a:nth-child(1)';
    public $faqButton='div.adc-mascot-quick-links-menu > a:nth-child(2)';
    public $documentationButton='div.adc-mascot-quick-links-menu > a:nth-child(3)';
    public $upgradeToProButton='div.adc-mascot-quick-links-menu > a:nth-child(4)';
}
