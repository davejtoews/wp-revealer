<?php 
/*
Plugin Name: DaveJToews Revealer
Version: 1.0
Author: Dave J Toews
Author URI: http://davejtoews.com
Description: Creates shortcodes and loads the necessary javascript to nicely show/hide content on click.
*/

add_shortcode('djt_reveal', 'djt_reveal');
add_shortcode('djt_reveal_section', 'djt_reveal_section');
add_shortcode('djt_reveal_hide', 'djt_reveal_hide');

add_action( 'wp_footer', 'djt_revealer_js' );

wp_enqueue_script( 'jquery' );

function djt_reveal($atts, $content = null) {
	$a = shortcode_atts( array(
		'target' => ''
	), $atts);

	$output .= "<div class='djt-reveal' data-djt-reveal-target='".$a['target']."'>";
	$output .= do_shortcode($content);
	$output .= "</div>";

	return $output;
}

function djt_reveal_section($atts, $content = null) {
	$a = shortcode_atts( array(
		'auto_hide' => false,
		'class' => '',
		'id' => ''
	), $atts);

	$classString = $a['class'];
	if ($a['auto_hide']) {
		$classString += " auto-hide";
	}

	$output .= "<div id='".$a['id']."' class='".$a['class']."'>";
	$output .= do_shortcode($content);
	$output .= "</div>";

	return $output;
}

function djt_reveal_hide($atts, $content = null) {
	$a = shortcode_atts( array(
		'target' => ''
	), $atts);

	$output .= "<div class='djt-reveal-hide' data-djt-reveal-target='".$a['target']."'>";
	$output .= do_shortcode($content);
	$output .= "</div>";

	return $output;
}

function djt_revealer_js() {
?>
<script type="text/javascript">
  	if ( undefined !== window.jQuery ) {
	    // script dependent on jQuery

        function djt_initializeListeners() {
            jQuery(".djt-reveal-hide").each(function(i, obj) {
                var target = jQuery(obj).data("djt-reveal-target");
                jQuery(obj).click(function() {
                    jQuery(target).removeClass('djt-reveal-open');
                });
            });
                           
            jQuery(".djt-reveal").each(function(i, obj) {
                var target = jQuery(obj).data("djt-reveal-target");
                jQuery(obj).click(function() {
                    jQuery(".djt-auto-hide").removeClass('djt-reveal-open');
                    jQuery(target).addClass('djt-reveal-open');
                });
                djt_initializeHeightRules(i, target);
            });

        }

        var djt_sheet = (function() {
            // Create the <style> tag
            var style = document.createElement("style");

            // WebKit hack :(
            style.appendChild(document.createTextNode(""));

            // Add the <style> element to the page
            document.head.appendChild(style);

            return style.sheet;
        })();

        function djt_addCSSRule(sheet, selector, rules, index) {

            if("insertRule" in sheet) {
                djt_sheet.insertRule(selector + "{" + rules + "}", index);
            }
            else if("addRule" in sheet) {
                sheet.addRule(selector, rules, index);
            }
        }

        function djt_initializeHeightRules(i, target) {
            var revealHeight = jQuery(target).height();
            var heightRuleString = "height: "+revealHeight+"px;";
            var uniqueClass = "djt-reveal-section"+i;
            var uniqeClosedSelectorString = "."+uniqueClass;
            var uniqueOpenSelectorString = ".djt-reveal-open."+uniqueClass;
            var zeroHeightRuleString = "height: 0; overflow: hidden; -webkit-transition: height 1s; transition: height 0.5s;";

            jQuery(target).addClass(uniqueClass);
            djt_addCSSRule(djt_sheet, uniqueOpenSelectorString, heightRuleString);

            djt_addCSSRule(djt_sheet, uniqeClosedSelectorString, zeroHeightRuleString);
        }

        jQuery(window).load(function(){
            djt_initializeListeners();
        });

  	}
</script>
<?php
}

?>