<?php
/**
 * @package typing-language
 * @version 1.0
 */
/*
Plugin Name: Typing Language
Plugin URI: http://logicsart.com
Description: A wordpress plugin which provide a powerful functionality to type post or page description in your selected language into wordpress site/blog. 
In this plugin user can select language and then type word in english after space it will convert word into selected language. This plugin uses Google transliteration api.
Author: Dileep Awasthi
Version: 1.6
Author URI: http://logicsart.com/
*/
	
add_action( 'add_meta_boxes', 'tl_action_add_meta_boxes', 0 );
add_action( 'admin_head', 'tl_headfun');

function tl_headfun() { ?>
<script type="text/javascript" src="https://www.google.com/jsapi">
    </script>
    <script type="text/javascript">
	window.tl_changelang = function() {
		var x = document.getElementById("logic_lang").value;
		window.location = "post-new.php?lang=" + x;
	}
	function tl_GetURLParameter(sParam)
	{
		var sPageURL = window.location.search.substring(1);
		var sURLVariables = sPageURL.split('&');
		for (var i = 0; i < sURLVariables.length; i++) 
		{
			var sParameterName = sURLVariables[i].split('=');
			if (sParameterName[0] == sParam) 
			{
				return sParameterName[1];
			}
		}
	}
	var tech = tl_GetURLParameter('lang');

      // Load the Google Transliterate API
      google.load("elements", "1", {
            packages: "transliteration"
          });

      <?php if(isset($_GET["lang"])) { 
		  $urlparam = $_GET["lang"];
	  } else {
		  $urlparam = 'ENGLISH';
	  } ?>
	  function onLoad() { 
		  if(tech) {
			  var options = {
					sourceLanguage:
						[google.elements.transliteration.LanguageCode.ENGLISH],
					destinationLanguage:
						[google.elements.transliteration.LanguageCode.<?php echo $urlparam ?>],
					shortcutKey: 'ctrl+g',
					transliterationEnabled: true
				};
		  } else { 
			  var options = {
					sourceLanguage:
						google.elements.transliteration.LanguageCode.ENGLISH,
					destinationLanguage:
						[google.elements.transliteration.LanguageCode.ENGLISH],
					shortcutKey: 'ctrl+g',
					transliterationEnabled: true
				}; 
		  }

        // Create an instance on TransliterationControl with the required
        // options.
        var control =
            new google.elements.transliteration.TransliterationControl(options);

        // Enable transliteration in the textbox with id
        // 'transliterateTextarea'.
        control.makeTransliteratable(['content']);
      }
      <?php if(isset($_GET["lang"])) { ?>
		  google.setOnLoadCallback(onLoad);
	  <?php } ?>
    </script>
<?php }

function tl_action_add_meta_boxes() {
	global $_wp_post_type_features;
	if (isset($_wp_post_type_features['post']['editor']) && $_wp_post_type_features['post']['editor']) {
		unset($_wp_post_type_features['post']['editor']);
		add_meta_box(
			'description_section',
			'Description',
			'tl_inner_custom_box',
			'post', 'normal', 'high'
		);
	}
	if (isset($_wp_post_type_features['page']['editor']) && $_wp_post_type_features['page']['editor']) {
		unset($_wp_post_type_features['page']['editor']);
		add_meta_box(
			'description_sectionid',
			'Description',
			'tl_inner_custom_box',
			'page', 'normal', 'high'
		);
	}
}
function tl_inner_custom_box( $post ) {
	$lanArray = array('AMHARIC','ARABIC', 'BENGALI', 'CHINESE', 'GREEK', 'GUJARATI', 'HINDI', 'KANNADA', 'MALAYALAM', 'MARATHI', 'NEPALI', 'ORIYA', 'PERSIAN', 'PUNJABI', 'RUSSIAN', 'SANSKRIT', 'SERBIAN', 'SINHALESE', 'TAMIL', 'TELUGU', 'TIGRINYA', 'URDU');
	
	echo 'Select Language :<select name="logic_lang" id="logic_lang" onchange="tl_changelang()"><option value="">Select Typing language</option>';
	foreach($lanArray as $lan) {
		echo '<option value="'.$lan.'" ';
		if($lan == $_GET["lang"]) { 
			echo 'selected ';
		}
		echo '>'.$lan.'</option>';
	}
	echo '</select>';
	the_editor($post->post_content);
}