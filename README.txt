=== WPAdCenter: Ads Manager, Banner Ads, Amazon, Google Adsense ===
Contributors: WPEka Club
Donate link: https://club.wpeka.com/
Tags: ads, adsense, ad rotation, ad manager, amazon, banners, adverts, ads shortcode, campaigns, adcenter
Requires at least: 5.0+
Requires PHP: 5.6
Tested up to: 5.7
Stable tag: 2.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

WPAdCenter helps you monetize your WordPress blog by displaying banner images and inserting advertising scripts anywhere on your website.

== Description ==

Looking for a powerful WordPress ad manager plugin that can display both Google AdSense and banner ads?
Get WPAdCenter. And get complete control over how ads are created, displayed and rotated on your WordPress website.
Display banner images or ads from ad networks like Google AdSense, Media.net, and Amazon advertising.
> “WPAdCenter will take care of all aspects of advertising on your WordPress powered websites. Whether you want to run your own advertising in the form of affiliate banners, Google Adsense, or any other advertising network you can think of, or you would like to offer advertising spots for sale on your site – it’s all possible with WPAdCenter.”
>
> <cite>– Oli, Founder , Kooc Media</cite>

== Features ==

- Display responsive ads anywhere on your WordPress website using a Gutenberg block and simple shortcodes.
- Display banner image ads.
- Connect to your AdSense Account and load ads directly from your wordpress.
- Display ads from ad networks including Google AdSense, Amazon Shopping Ads, Media.net, and more.
- Create unlimited ad groups to display single or multiple ads.
- Create unlimited ad banners, ad groups.
- Automatically start and stop ads by scheduling ads.
- Comprehensive statistics and reports for ads.
- Generates ads.txt with custom content.
- Customizable banner sizes supported (Pro feature).
- Unlimited advertiser accounts (Pro feature).

== Upgrade to Pro ==

Get more from your WPAdCenter. Upgrade to the [Pro version](https://club.wpeka.com/product/wpadcenter/?utm_source=wporg&utm_medium=wpadcenter&utm_campaign=wpadcenter&utm_content=pro-version).

== What Next? ==

If you like this plugin, then consider checking out our other projects:

- [WP Legal Pages](https://wplegalpages.com/?utm_source=wporg&utm_medium=referral&utm_campaign=wpadcenter): Generate 25+ legal policy pages for your WordPress website in just a few minutes, including, Privacy Policy, Terms & Conditions, Cookie Policy and many more.
- [WP Cookie Consent](https://wplegalpages.com/cookie-consent-banner-on-your-website/?utm_source=wporg&utm_medium=referral&utm_campaign=wpadcenter): Display a customized cookie consent notice (for GDPR), and "Do Not Sell" opt-out notice (for CCPA). Get granular consent and record a consent log.
- [WPeka Woo Auction Software](https://wpauctionsoftware.com/?utm_source=wporg&utm_medium=referral&utm_campaign=wpadcenter): Host eBay like auctions or Simple / Reverse / Penny Auctions on your WooCommerce website.

== Installation ==

1. Upload `wpadcenter` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= How can I insert ads anywhere on my WordPress website? =
You can easily insert ads anywhere on your website. There are widgets available.

There are also Gutenberg blocks available to select to add it anywhere within your content as well.

= Can I use any other hosted ad networks? =
Yes. You can either choose to use your own hosted ads, or use ads from any other ad networks (like Google Adsense).

= Can I schedule Ads? =
Yes, You can schedule Ads.

To schedule Ads, create a Campaign in Ad Center with start date in future and appropriate end date.

When Campaign is created with a start date in the future, status of such campaigns will be scheduled. When the start date is reached those campaigns will start and their status will be changed to Running.

To know more about creating banners visit [documentation](https://docs.wpeka.com/wp-adcenter/campaigns)

= How to add Google Adsense Auto Ads in the plugin? =
You can insert the Auto Ads script either across the site or on a specific page/post where you want the ads to appear.

To display ads from external sources like Google Adsense, you can create banners with Ad Code from Google Adsense.

- Navigate to **WordPress Dashboard > WPAdCenter > Ads.**
- In **Ad Type** - Select type of the ad as **Ad Code.**
- With Ad type selected as Ad Code you can provide code from Google Adsense instead of Banner image.

To know more about creating banners visit [documentation](https://docs.wpeka.com/wp-adcenter/banners)

To display ads on a specific page/post, create one or more Ad Zones with required Campaigns and add those Ad Zones to a specific page/post.

= What banner sizes are supported? =

- IAB Full Banner (468 x 60)
- IAB Skyscraper (120 x 600)
- IAB Wide Skyscraper (160 x 600)
- IAB Leaderboard (728 x 90)
- IAB Rectangle (180 x 150)
- IAB Medium Rectangle (300 x 250)
- IAB Button 1 (120 x 90)
- IAB Button 2 (120 x 60)
- IAB Square Button (125 x 125)
- You can also customize the size of banners according to your requirements.

= How to add Ads in WordPress? =
You can use WPAdcenter to add ads into your WordPress site wherever you want.

= How does WPAdCenter manage GDPR and ePrivacy? =
For EU visitors, you can show ads only if you have the user consent.

You can use the [GDPR Cookie Consent Plugin](https://club.wpeka.com/product/wp-gdpr-cookie-consent/) which manages this. The Ads will be displayed only after the user consent is given.

WPAdcenter doesn’t capture or save personal information (e.g., an IP address). It also doesn’t store any cookies in the visitor’s browser.

= Which ad networks are supported? =
WPAdCenter works with ad networks which support ads via Banner Images, Videos, Javascript code.

It’s compatible with popular ad networks like Google AdSense, Amazon, Chitika, Clickbank, Buy Sell Ads, Google Ad Manager (Google Double Click, DFP), media.net.

You can also advertisement scripts to header/footer on specific pages or across your website without coding.

= Is there PHP code or shortcodes I can embed in my theme? =
You can also place a Ad Zone in your template or theme via the PHP command “do_shortcode()”. Just pass the ID of the Ad Zone you want to render.

For example:
“< ? php echo do_shortcode( "[wpadcenter_ad id=2803]" ); ? >“
Where 2803 is the Ad Zone’s ID.

== Screenshots ==

1. Settings Menu.
2. Manage Ads.
3. Create New Ad.
4. Manage Ad Groups.
5. Reports.

== Changelog ==
= 2.0.0 =
* Feature: Plugin rewrite.

= 1.1.4 =
* Feature: Ad-block detection.
* Feature: Content ads.
* Feature: Hide Ads option for logged in users and on specific pages and/or posts.
* Fix: Minor plugin bug fixes.

= 1.1.3 =
* Update: Improvised UI for Getting Started page.

= 1.1.2 =
* Fix: Fixed banner clicks tracking issue on the frontend.

= 1.1.1 =
* Fix: Fixed vulnerability for analytics within the plugin.

= 1.1.0 =
* Feature: Adsense Importer.
* Update: Spanish translation.

= 1.0.4 =
* Feature: ads.txt support.
* Update: usability.
* Update: French translation.

= 1.0.3 =
* Feature: Create ads with simple and easy to use - Ad Wizard.
* Update: Improved Link settings for Banner - Added Open in new tab and nofollow field settings.
* Update: Select custom size for Adzone templates.
* Update: Added feature comparison table for Free and Pro versions.
* Fix: Issue related to Global scripts on Blog post page.

= 1.0.2 =
* Update: Support for Pro features.
* Fix: Minor bug fixes.

= 1.0.1 =
* Feature: Add custom scripts to header, body, and/or footer.
* Feature: Daily Cron for scheduled campaigns.
* Update: Integration support for Pro features.
* Update: Updated plugin name as per the slug.
* Fix: Campaigns status issue on save.

= 1.0.0 =
* Feature: Initial version.

== Upgrade Notice ==
= 2.0.0 =
* Feature: Plugin rewrite.

= 1.1.4 =
* Feature: Ad-block detection.
* Feature: Content ads.
* Feature: Hide Ads option for logged in users and on specific pages and/or posts.
* Fix: Minor plugin bug fixes.

= 1.1.3 =
* Update: Improvised UI for Getting Started page.

= 1.1.2 =
* Fix: Fixed banner clicks tracking issue on the frontend.

= 1.1.1 =
* Fix: Fixed vulnerability for analytics within the plugin.

= 1.1.0 =
* Feature: Adsense Importer.
* Update: Spanish translation.

= 1.0.4 =
* Feature: ads.txt support.
* Update: usability.
* Update: French translation.

= 1.0.3 =
* Feature: Create ads with simple and easy to use - Ad Wizard.
* Update: Improved Link settings for Banner - Added Open in new tab and nofollow field settings.
* Update: Select custom size for Adzone templates.
* Update: Added feature comparison table for Free and Pro versions.
* Fix: Issue related to Global scripts on Blog post page.

= 1.0.2 =
* Update: Support for Pro features.
* Fix: Minor bug fixes.

= 1.0.1=
* Feature: Add custom scripts to header, body, and/or footer.
* Feature: Daily Cron for scheduled campaigns.
* Update: Integration support for Pro features.
* Update: Updated plugin name as per the slug.
* Fix: Campaigns status issue on save.

= 1.0.0 =
* Feature: Initial version.