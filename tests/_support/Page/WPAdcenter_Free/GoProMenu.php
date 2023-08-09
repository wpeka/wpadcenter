<?php
/**
 * Selectors used in the automation testcases for GoPro Menu of WPAdcenter free plugin
 * 
 * @category AutomationTests
 * @package  WordPress_WPAdcenter_Free_Plugin
 * @author   WPEKA <hello@wpeka.com>
 * @license  GPL v3
 * @link     https://club.wpeka.com
 */
namespace Page\WPAdcenter_Free;

/**
 * Core class for all the selectors used in automation testcases for GoPro Menu of WPAdcenter free plugin
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
class GoProMenu
{
    public $wpadcenterMenuBtn='#menu-posts-wpadcenter-ads > a > div.wp-menu-name';
    public $goPro='#menu-posts-wpadcenter-ads > ul > li:nth-child(8) > a';
}
