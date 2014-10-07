<?php
/*
Plugin Name: Live Chat Support - Help Desk Plugin by Social Intents
Plugin URI: http://www.socialintents.com
Description: Add Live Chat Support with unlimited agents to any site with this simple plugin.  Additional widgets such as Email List Building, and Social Offers are also available! 
Version: 1.0.2
Author: Social Intents
Author URI: http://www.socialintents.com/
*/

$silc_domain = plugins_url();
add_action('init', 'silc_init');
add_action('admin_notices', 'silc_notice');
add_filter('plugin_action_links', 'silc_plugin_actions', 10, 2);
add_action('wp_footer', 'silc_insert',4);

function silc_init() {
    if(function_exists('current_user_can') && current_user_can('manage_options')) {
        add_action('admin_menu', 'silc_add_settings_page');
    }
}

function silc_insert() {

    global $current_user;
    if(strlen(get_option('silc_widgetID')) == 32 && get_option('silc_tab_text')) {
	$tabText=str_replace("'","\'",get_option('silc_tab_text'));
	$tabOfflineText=str_replace("'","\'",get_option('silc_tab_offline_text'));
        get_currentuserinfo();
	echo("\n\n<!-- Social Intents Customization -->\n");
        echo("<script type=\"text/javascript\">\n");
        echo("var socialintents_vars_chat ={\n");
        echo("'widgetId':'".get_option('silc_widgetID')."',\n");
        echo("'tabLocation':'".get_option('silc_tab_placement')."',\n");
        echo("'tabText':'".$tabText."',\n");
	echo("'tabOfflineText':'".$tabOfflineText."',\n");
	echo("'type':'chat',\n");
        echo("'tabColor':'".get_option('silc_tab_color')."',\n");
        echo("'tabWidth':'250px',\n");
        echo("'marginRight':'60px', \n");
	echo("'marginTop':'180px', \n");
        echo("'headerTitle':'".get_option('silc_header_text')."'\n");
        echo("};\n");
        echo("(function() {function socialintents(){\n");
        echo("    var siJsHost = ((\"https:\" === document.location.protocol) ? \"https://\" : \"http://\");\n");
        echo("    var s = document.createElement('script');s.type = 'text/javascript';s.async = true;s.src = siJsHost+'www.socialintents.com/api/chat/socialintents.js';\n");
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
            <h3 class="hndle"><span><?php _e('Live Chat Settings', $silc_domain) ?></span></h3> 
            <div class="inside" style="padding: 0 10px">
                <form id="saveSettings" method="post" action="options.php">
                    <p style="text-align:center"><?php wp_nonce_field('update-options') ?>
			<a href="http://www.socialintents.com/" title="Live Chat to help grow your business">
			<?php echo '<img src="'.plugins_url( 'socialintents.png' , __FILE__ ).'" height="150" "/> ';?></a></p>

                    <p><label for="silc_widgetID"><?php printf(__('Enter your Widget Key below to activate the plugin.  If you don\'t have your key but have already signed up, you can <a href=\'http://www.socialintents.com\' target=\'_blank\'>login here</a> to grab your key under your widget --> your code snippet..<br>', $silc_domain), '<strong><a href="http://www.socialintents.com/" title="', '">', '</a></strong>') ?></label><br />
			<input type="text" name="silc_widgetID" id="silc_widgetID" placeholder="Your Widget Key" value="<?php echo(get_option('silc_widgetID')) ?>" style="width:100%" />
                    <p class="submit" style="padding:0"><input type="hidden" name="action" value="update" />
                        <input type="hidden" name="page_options" value="silc_widgetID" />
                        <input type="submit" name="silc_submit" id="silc_submit" value="<?php _e('Save Settings', $silc_domain) ?>" class="button-primary" /> 
			</p>
                 </form>
            </div>
        </div>
        <div class="postbox" style="float:left;width:38em">
            <h3 class="hndle"><span id="silc_noAccountSpan"><?php _e('No Account?  Sign up for a Free Social Intents Trial!', $silc_domain) ?></span></h3>
            <div id="silc_register" class="inside" style="padding: -30px 10px">			
		<p><?php printf(__('Social Intents is a live chat support and social widgets platform that helps you grow your business with simple, effective plugins
			with targeted rules and dynamic reports.
			Please visit %1$sSocial Intents%2$ssocialintents.com%3$s to 
				learn more.', $silc_domain), '<a href="
http://www.socialintents.com/" title="', '">', '</a>') ?></p>
			<b>Sign Up For a Free Trial Now!</b> (or register directly on our site at <a href="http://www.socialintents.com" target="_blank">Social Intents</a>)<br>
			<input type="text" name="silc_email" id="silc_email" value="<?php echo(get_option('admin_email')) ?>" placeholder="Your Email" style="width:50%;margin:3px;" />
			<input type="text" name="silc_name" id="silc_name" value="<?php echo(get_option('user_nicename')) ?>" placeholder="Your Name" style="width:50%;margin:3px;" />
			<input type="password" name="silc_password" id="silc_password" value="" placeholder="Your Password" style="width:50%;margin:3px;" />
			<br><input type="button" name="silc_inputRegister" id="silc_inputRegister" value="Register" class="button-primary" style="margin:3px;" /> 
			
			
               
            </div>
	    <div id="silc_registerComplete" class="inside" style="padding: -20px 10px;display:none;">
		<p>View reports, customize chat widget and CSS styles, and export chat history on our website at <a href='http://www.socialintents.com'>www.socialintents.com</a>
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
		
		<tr><td>When To Show Proactive Chat: </td><td>
		<?php 
		if(get_option('silc_time_on_page') && get_option('silc_time_on_page') == '0') {
     		?>
     		<select id="silc_time_on_page" name="silc_time_on_page">
			<option value="0" selected>Disable</option>
			<option value="10">10 Seconds</option>
			<option value="15">15 Seconds</option>
			<option value="20">20 Seconds</option>
			<option value="30">30 Seconds</option>
			<option value="45">45 Seconds</option>
			<option value="60">60 Seconds</option>
		</select> 	
    		<?php 
			} else if(get_option('silc_time_on_page') == '10') {
   		?>
		<select id="silc_time_on_page" name="silc_time_on_page">
			<option value="0" selected>Disable</option>
			<option value="10"  selected>10 Seconds</option>
			<option value="15">15 Seconds</option>
			<option value="20">20 Seconds</option>
			<option value="30">30 Seconds</option>
			<option value="45">45 Seconds</option>
			<option value="60">60 Seconds</option>
		</select>  
		<?php 
			} else if(get_option('silc_time_on_page') == '15') {
   		?>
		<select id="silc_time_on_page" name="silc_time_on_page">
			<option value="0" selected>Disable</option>
			<option value="10">10 Seconds</option>
			<option value="15"  selected>15 Seconds</option>
			<option value="20">20 Seconds</option>
			<option value="30">30 Seconds</option>
			<option value="45">45 Seconds</option>
			<option value="60">60 Seconds</option>
		</select> 
		<?php 
			} else if(get_option('silc_time_on_page') == '20') {
   		?>
		<select id="silc_time_on_page" name="silc_time_on_page">
			<option value="0" selected>Disable</option>
			<option value="10">10 Seconds</option>
			<option value="15">15 Seconds</option>
			<option value="20"   selected>20 Seconds</option>
			<option value="30">30 Seconds</option>
			<option value="45">45 Seconds</option>
			<option value="60">60 Seconds</option>
		</select> 
		<?php 
			} else if(get_option('silc_time_on_page') == '30') {
   		?>
		<select id="silc_time_on_page" name="silc_time_on_page">
			<option value="0" selected>Disable</option>
			<option value="10">10 Seconds</option>
			<option value="15">15 Seconds</option>
			<option value="20" >20 Seconds</option>
			<option value="30"  selected>30 Seconds</option>
			<option value="45">45 Seconds</option>
			<option value="60">60 Seconds</option>
		</select> 
		<?php 
			} else if(get_option('silc_time_on_page') == '45') {
   		?>
		<select id="silc_time_on_page" name="silc_time_on_page">
			<option value="0" selected>Disable</option>
			<option value="10">10 Seconds</option>
			<option value="15">15 Seconds</option>
			<option value="20" >20 Seconds</option>
			<option value="30">30 Seconds</option>
			<option value="45"  selected>45 Seconds</option>
			<option value="60">60 Seconds</option>
		</select> 
		<?php 
			} else if(get_option('silc_time_on_page') == '60') {
   		?>
		<select id="silc_time_on_page" name="silc_time_on_page">
			<option value="0" selected>Disable</option>
			<option value="10">10 Seconds</option>
			<option value="15">15 Seconds</option>
			<option value="20">20 Seconds</option>
			<option value="30">30 Seconds</option>
			<option value="45">45 Seconds</option>
			<option value="60"  selected>60 Seconds</option>
		</select>  
		<?php 
			} else {
   		?>
		<select id="silc_time_on_page" name="silc_time_on_page">
			<option value="0" selected>Disable</option>
			<option value="10">10 Seconds</option>
			<option value="15">15 Seconds</option>
			<option value="20">20 Seconds</option>
			<option value="30">30 Seconds</option>
			<option value="45">45 Seconds</option>
			<option value="60">60 Seconds</option>
		</select>  
		<?php 
			}
   		?>
		
		</td></tr>
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
		<br><small >If you don't see your latest settings reflected in your site, please refresh your browser cache
		or close and open the browser.  
		</small>	
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
	$( "#silc_noAccountSpan" ).html("Configure you Live Chat Support Widget");

}
$(document).on("click", "#silc_inputSaveSettings", function () {

var silc_wid= $('#silc_widgetID').val();
var silc_tt= encodeURIComponent($('#silc_tab_text').val());
var silc_ht= encodeURIComponent($('#silc_header_text').val());
var silc_to= encodeURIComponent($('#silc_tab_offline_text').val());
var silc_tc= encodeURIComponent($('#silc_tab_color').val());
var silc_top= $('#silc_time_on_page').val();


var url = 'https://www.socialintents.com/json/jsonSaveChatSettings.jsp?tc='silc_tc+'&tt='+silc_tt+'&ht='+silc_ht+'&wid='+silc_wid+'&to='+silc_to+'&top='+silc_top+'&callback=?';sessionStorage.removeItem("settings");
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
		alert("Thanks for signing up!  Now Customize your settings...");
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
    add_submenu_page('options-general.php', __('Social Intents', $silc_domain), __('Social Intents', $silc_domain), 'manage_options', 'live-chat-support-by-socialintents', 'silc_settings_page');
}?>
