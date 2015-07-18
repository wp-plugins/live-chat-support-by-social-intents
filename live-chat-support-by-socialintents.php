<?php
/*
Plugin Name: Live Chat
Plugin URI: http://www.socialintents.com
Description: Add Live Chat Support and Help to any site with this simple plugin.  Delight visitors, improve service, and sell more with Live Chat.
Version: 1.1.7
Author: Social Intents
Author URI: http://www.socialintents.com/
*/

$silc_domain = plugins_url();
add_action('init', 'silc_init');
add_action('admin_notices', 'silc_notice');
add_filter('plugin_action_links', 'silc_plugin_actions', 10, 2);
add_action('wp_footer', 'silc_insert',4);
add_action('admin_footer', 'siRedirect');

define('SI_DASHBOARD_URL', "https://www.socialintents.com/chat.do");
define('SI_SMALL_LOGO',$plugurldir.'/si-small.png');

function silc_init() {
    if(function_exists('current_user_can') && current_user_can('manage_options')) {
        add_action('admin_menu', 'silc_add_settings_page');
        add_action('admin_menu', 'silc_create_menu');
    }
	

}

function silc_insert() {

    global $current_user;
    if(strlen(get_option('silc_widgetID')) == 32 ) {
	echo("\n\n<!-- Live Chat -->\n<script type=\"text/javascript\">\n");
        
	echo("(function() {function socialintents(){\n");
        echo("    var siJsHost = ((\"https:\" === document.location.protocol) ? \"https://\" : \"http://\");\n");
        echo("    var s = document.createElement('script');s.type = 'text/javascript';s.async = true;s.src = siJsHost+'www.socialintents.com/api/chat/socialintents.js#".get_option('silc_widgetID')."';\n");
        
        echo("    var x = document.getElementsByTagName('script')[0];x.parentNode.insertBefore(s, x);};\n");
        echo("if (window.attachEvent)window.attachEvent('onload', socialintents);else window.addEventListener('load', socialintents, false);})();\n");
        echo("</script>\n");
    }
}

function silc_notice() {
    if(!get_option('silc_widgetID')) echo('<div class="error"><p><strong>'.sprintf(__('Your Live Chat Support Plugin is disabled. Please go to the <a href="%s">plugin settings</a> to enter a valid widget key.  Find your widget key by logging in at www.socialintents.com and selecting your Widget General Settings.  New to socialintents.com?  <a href="http://www.socialintents.com">Sign up for a Free Trial!</a>' ), admin_url('options-general.php?page=live-chat-support-by-socialintents')).'</strong></p></div>');
}

function silc_plugin_actions($links, $file) {
    static $this_plugin;
    if(!$this_plugin) $this_plugin = plugin_basename(__FILE__);
    if($file == $this_plugin && function_exists('admin_url')) {
        $settings_link = '<a href="'.admin_url('options-general.php?page=live-chat-support-by-socialintents').'">'.__('Settings', $silc_domain).'</a>';
        array_unshift($links, $settings_link);
    }
    return($links);
}

function silc_add_settings_page() {
    function silc_settings_page() {
        global $silc_domain ?>
<div class="wrap">
        <?php screen_icon() ?>
    <h2><?php _e('Live Chat Support by Social Intents', $silc_domain) ?></h2>
    <div class="metabox-holder meta-box-sortables ui-sortable pointer">
        <div class="postbox" style="float:left;width:30em;margin-right:10px">
            <h3 class="hndle"><span><?php _e('Live Chat Widget Key', $silc_domain) ?></span></h3> 
            <div class="inside" style="padding: 0 10px">
                <form id="saveSettings" method="post" action="options.php">
                    <p style="text-align:center"><?php wp_nonce_field('update-options') ?>
			<a href="http://www.socialintents.com/" title="Live Chat to help grow your business">
			<?php echo '<img src="'.plugins_url( 'socialintents.png' , __FILE__ ).'" height="150" "/> ';?></a></p>

                    <p><label for="silc_widgetID"><?php printf(__('Enter your Widget Key below to activate the plugin. <br><br> If you\'ve already signed up, <a href=\'http://www.socialintents.com\' target=\'_blank\'>login here</a> to grab your key under Widgets --> Live Chat --> Your Code Snippet.<br>', $silc_domain), '<strong><a href="http://www.socialintents.com/" title="', '">', '</a></strong>') ?></label><br />
			<input type="text" name="silc_widgetID" id="silc_widgetID" placeholder="Your Widget Key" value="<?php echo(get_option('silc_widgetID')) ?>" style="width:100%" />
                    <p class="submit" style="padding:0"><input type="hidden" name="action" value="update" />
                        <input type="hidden" name="page_options" value="silc_widgetID" />
                        <input type="submit" name="silc_submit" id="silc_submit" value="<?php _e('Save Settings', $silc_domain) ?>" class="button-primary" /> 
			</p>
                 </form>
            </div>
        </div>
        <div class="postbox" style="float:left;width:38em">
            <h3 class="hndle"><span id="silc_noAccountSpan"><?php _e('No Account?  Sign up for a Free Trial!', $silc_domain) ?></span></h3>
            <div id="silc_register" class="inside" style="padding: -30px 10px">			
		<p><?php printf(__('Social Intents is a live chat support and widgets platform that helps you grow your business with simple plugins, targeted display rules and dynamic reports.
			Please visit %1$sSocial Intents%2$ssocialintents.com%3$s to 
				learn more.', $silc_domain), '<a href="
http://www.socialintents.com/" title="', '">', '</a>') ?></p>
			<b>Register Now!</b> (or register directly on our site at <a href="http://www.socialintents.com" target="_blank">Social Intents</a>)<br>
			<input type="text" name="silc_email" id="silc_email" value="<?php echo(get_option('admin_email')) ?>" placeholder="Your Email" style="width:50%;margin:3px;" />
			<input type="text" name="silc_name" id="silc_name" value="<?php echo(get_option('user_nicename')) ?>" placeholder="Your Name" style="width:50%;margin:3px;" />
			<input type="password" name="silc_password" id="silc_password" value="" placeholder="Your Password" style="width:50%;margin:3px;" />
			<br><input type="button" name="silc_inputRegister" id="silc_inputRegister" value="Register" class="button-primary" style="margin:3px;" /> 
			
			
               
            </div>
	    <div id="silc_registerComplete" class="inside" style="padding: -20px 10px;display:none;">
<p>Simply open the Live Chat console to answer chats right in your browser.</p>
		<p>Just Getting Started?  <a href='http://www.socialintents.com/assets/pdfs/LiveChatSupportGuide.pdf' target="_blank">Download Our Live Chat Help Guide</a>
		<p><a href='https://www.socialintents.com/chat.do' class="button button-primary" target="_blank">Open Live Chat Console</a>&nbsp;
			<a href='https://www.socialintents.com/widget.do?id=<?php echo(get_option('silc_widgetID')) ?>' class="button button-primary" target="_blank">Customize Text & Settings</a>
		</p><form id='saveDetailSettings' method="post" action="options.php">
		<?php wp_nonce_field('update-options') ?>
		<input type="hidden" name="action" value="update" />
		<input type="hidden" name="silc_tab_placement" value="bottom" />
                <input type="hidden" name="page_options" value="silc_tab_placement,silc_tab_text,silc_tab_offline_text,silc_header_text,silc_time_on_page,silc_tab_color" />
		<table width="100%" >
		<tr><td width="25%">Tab Text: </td>
		<td >
		<?php
		if(get_option('silc_tab_text') ) {
     		?>
     			<input type="text" name="silc_tab_text" id="silc_tab_text" value="<?php echo(get_option('silc_tab_text')) ?>" style="margin:3px;width:100%;" />
		
    		<?php 
			} else {
   		?>
			<input type="text" name="silc_tab_text" id="silc_tab_text" value="Need Help? Chat with us!" style="margin:3px;width:100%;" />
		<?php 
			}
   		?>
		</td>
		</tr>
		<tr><td width="25%">Tab Offline Text: </td>
		<td >
		<?php
		if(get_option('silc_tab_offline_text') ) {
     		?>
     			<input type="text" name="silc_tab_offline_text" id="silc_tab_offline_text" value="<?php echo(get_option('silc_tab_offline_text')) ?>" style="margin:3px;width:100%;" />
		
    		<?php 
			} else {
   		?>
			<input type="text" name="silc_tab_offline_text" id="silc_tab_offline_text" value="We're offline" style="margin:3px;width:100%;" />
		<?php 
			}
   		?>
		</td>
		</tr>
		<tr><td width="25%">Tab Color: </td>
		<td >
		<?php
		if(get_option('silc_tab_color') && get_option('silc_tab_color') != '') {
     		?>
     			<input type="text" name="silc_tab_color" id="silc_tab_color" value="<?php echo(get_option('silc_tab_color')) ?>" style="margin:3px;width:100%;" />
		
    		<?php 
			} else {
   		?>
			<input type="text" name="silc_tab_color" id="silc_tab_color" value="#00AEEF" style="margin:3px;width:100%;" />
		<?php 
			}
   		?>
		</td>
		</tr>
		
		
		<tr><td>Welcome Text: </td><td>
		<?php 
		if(get_option('silc_header_text') && get_option('silc_header_text') != '') {
     		?>
     		<input type="text" name="silc_header_text" id="silc_header_text" value="<?php echo(get_option('silc_header_text')) ?>" style="margin:3px;width:100%;" />
		
    		<?php 
			} else {
   		?>
		<input type="text" name="silc_header_text" id="silc_header_text" value="Questions? Chat with us!" style="margin:3px;width:100%;" />
		<?php 
			}
   		?>
		</td></tr>
		<tr><td>
		</td><td>
		<input id='silc_inputSaveSettings' type="button" value="<?php _e('Save Settings', $silc_domain) ?>" class="button-primary" /> 
			
		</td></tr>
		</table> 
			
		</form>
	    </div>
        </div>
    </div>
</div>
<script>
jQuery(document).ready(function($) {



var silc_wid= $('#silc_widgetID').val();
if (silc_wid=='') 
{}
else
{
	$( "#silc_register" ).hide();
	$( "#silc_registerComplete" ).show();
	$( "#silc_noAccountSpan" ).html("Live Chat Plugin Settings");

}
$(document).on("click", "#silc_inputSaveSettings", function () {

var silc_wid= $('#silc_widgetID').val();
var silc_tt= encodeURIComponent($('#silc_tab_text').val());
var silc_ht= encodeURIComponent($('#silc_header_text').val());
var silc_to= encodeURIComponent($('#silc_tab_offline_text').val());
var silc_tc= encodeURIComponent($('#silc_tab_color').val());
var silc_top= $('#silc_time_on_page').val();


var url = 'https://www.socialintents.com/json/jsonSaveChatSettings.jsp?tc='+silc_tc+'&tt='+silc_tt+'&ht='+silc_ht+'&wid='+silc_wid+'&to='+silc_to+'&top='+silc_top+'&callback=?';sessionStorage.removeItem("settings");
$.ajax({
   type: 'GET',
    url: url,
    async: false,
    jsonpCallback: 'jsonCallBack',
    contentType: "application/json",
    dataType: 'jsonp',
    success: function(json) {
       $('#silc_widgetID').val(json.key);
	sessionStorage.removeItem("settings");
	sessionStorage.removeItem("socialintents_vs_chat");
	sessionStorage.setItem("hasSeenPopup","false");
	$( "#saveDetailSettings" ).submit();
	
    },
    error: function(e) {
    }
});

  });

$(document).on("click", "#silc_inputRegister", function () {

var silc_email= $('#silc_email').val();
var silc_name= $('#silc_name').val();
var silc_password= $('#silc_password').val();
var url = 'https://www.socialintents.com/json/jsonSignup.jsp?type=chat&name='+silc_name+'&email='+silc_email+'&pw='+silc_password+'&callback=?';
$.ajax({
   type: 'GET',
    url: url,
    async: false,
    jsonpCallback: 'jsonCallBack',
    contentType: "application/json",
    dataType: 'jsonp',
    success: function(json) {
	if (json.msg=='') {
         	$('#silc_widgetID').val(json.key);
		alert("Thanks for signing up!  You can use these same credentials to login to socialintents.com.  Now Customize your settings...");
		$( "#saveSettings" ).submit();
		
	}
	else {
		alert(json.msg);
	}
    },
    error: function(e) {
       
    }
});

});
});
</script>
<?php }
add_submenu_page('options-general.php', __('Live Chat', $silc_domain), __('Live Chat', $silc_domain), 'manage_options', 'live-chat-support-by-socialintents', 'silc_settings_page');
}
function addSilcLink() {
$dir = plugin_dir_path(__FILE__);
include $dir . 'options.php';
}
function silc_create_menu() {
  $optionPage = add_menu_page('Live Chat', 'Live Chat', 'administrator', 'silc_dashboard', addSilcLink, plugins_url('live-chat-support-by-social-intents/si-small.png'));
}
function siRedirect() {
$redirectUrl = "https://www.socialintents.com/chat.do";
echo "<script> jQuery('a[href=\"admin.php?page=silc_dashboard\"]').attr('href', '".$redirectUrl."').attr('target', '_blank') </script>";
}
?>

