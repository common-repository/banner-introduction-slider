=== Banner Introduction Slider ===
Contributors: Module Express
Donate link: http://beautiful-module.com/
Tags: banner introduction slider,image slider,responsive header gallery slider,responsive banner slider,header banner slider,responsive slideshow,header image slideshow
Requires at least: 3.5
Tested up to: 4.4
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A quick, easy way to add an Responsive header Banner Introduction Slider OR Responsive Banner Introduction Slider inside wordpress page OR Template. Also mobile touch Banner Introduction Slider

== Description ==

This plugin add a Responsive Banner Introduction Slider in your website. Also you can add Responsive Banner Introduction Slider page and mobile touch slider in to your wordpress website.

View [DEMO](http://beautiful-module.com/demo/banner-introduction-slider/) for additional information.

= Installation help and support =
* Please check [Installation and Document](http://beautiful-module.com/documents/banner-introduction-slider/)  on our website.

The plugin adds a "Responsive Banner Introduction Slider" tab to your admin menu, which allows you to enter Image Title, Content, Link and image items just as you would regular posts.

To use this plugin just copy and past this code in to your header.php file or template file 
<code><div class="headerslider">
 <?php echo do_shortcode('[banner.intro.slider]'); ?>
 </div></code>

You can also use this Banner Introduction Slider inside your page with following shortcode 
<code>[banner.intro.slider] </code>

Display Banner Introduction Slider catagroies wise :
<code>[banner.intro.slider cat_id="cat_id"]</code>
You can find this under  "Banner Introduction Slider-> Gallery Category".

= Complete shortcode is =
<code>[banner.intro.slider cat_id="9" autoplay="true" autoplay_interval="3000"]</code>
 
Parameters are :

* **limit** : [banner.intro.slider limit="-1"] (Limit define the number of images to be display at a time. By default set to "-1" ie all images. eg. if you want to display only 5 images then set limit to limit="5")
* **cat_id** : [banner.intro.slider cat_id="2"] (Display Image slider catagroies wise.) 
* **autoplay** : [banner.intro.slider autoplay="true"] (Set autoplay or not. value is "true" OR "false")
* **autoplay_interval** : [banner.intro.slider autoplay="true" autoplay_interval="3000"] (Set autoplay interval)

= Features include: =
* Mobile touch slide
* Responsive
* Shortcode <code>[banner.intro.slider]</code>
* Php code for place image slider into your website header  <code><div class="headerslider"> <?php echo do_shortcode('[banner.intro.slider]'); ?></div></code>
* Banner Introduction Slider inside your page with following shortcode <code>[banner.intro.slider] </code>
* Easy to configure
* Smoothly integrates into any theme
* CSS and JS file for custmization

== Installation ==

1. Upload the 'banner-introduction-slider' folder to the '/wp-content/plugins/' directory.
2. Activate the 'Banner Introduction Slider' list plugin through the 'Plugins' menu in WordPress.
3. If you want to place Banner Introduction Slider into your website header, please copy and paste following code in to your header.php file  <code><div class="headerslider"> <?php echo do_shortcode('[banner.intro.slider limit="-1"]'); ?></div></code>
4. You can also display this Images slider inside your page with following shortcode <code>[banner.intro.slider limit="-1"] </code>


== Frequently Asked Questions ==

= Are there shortcodes for Banner Introduction Slider items? =

If you want to place Banner Introduction Slider into your website header, please copy and paste following code in to your header.php file  <code><div class="headerslider"> <?php echo do_shortcode('[banner.intro.slider limit="-1"]'); ?></div>  </code>

You can also display this Banner Introduction Slider inside your page with following shortcode <code>[banner.intro.slider limit="-1"] </code>



== Screenshots ==
1. Designs Views from admin side
2. Catagroies shortcode

== Changelog ==

= 1.0 =
Initial release