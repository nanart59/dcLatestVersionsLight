<?php
# -- BEGIN LICENSE BLOCK ----------------------------------
#
# This file is part of dcLatestVersionsLight, a plugin for Dotclear 2.
# 
# Copyright (c) 2020 Nan'Art and contributors
# a light corrected and updated version of dcLatestVersions 2015-03-11, Jean-Christian Denis and contributors
# 
# Licensed under the GPL version 2.0 license.
# A copy of this license is available in LICENSE file or at
# http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
#
# -- END LICENSE BLOCK ------------------------------------
/**
 * @brief dcLatestVersionsLight, a plugin for Dotclear 2
 * 
 * define plugin' admin page:
	- just give user pref (+ url)
 */
/* dcLatestVersionsLight/index.php */

if (!defined('DC_CONTEXT_ADMIN')) {return;}

#debug ?
/*/
	define("PLUG_DEBUG", true);
//*/

#dc min version (tested)
	$dc_best = '2.15';
#php min version (tested)
#@ignore
	$php_min = '5.6.40'; //also tested on 7.4x

/*------please, dont change bellow---------*/
#module id
	$module_id = 'dcLatestVersionsLight';
#module_setting
	$module_setting = [
		[	'versions_type',
			"List of Dotclear's builds",
			'stable,unstable,testing,sexy', //sexy ignore?
			'string'
		]
	];
#user prefs
	$workspace		= 'dcLatestVersionsLight';
	$pref_show		= 'dclv_show_on_dashboard';

#page
	$index_title 	= __('Plugin informations');
	$index_comment 	= sprintf(__("plugin %s information"), __($module_id));

#preferences
		$core->auth->user_prefs->addWorkspace($workspace);
		$show 		= $core->auth->user_prefs->$workspace->$pref_show;
		$show_on 	= $show ?__('show on dashboard.') :__('not display on dashboard.');

#versions types
$version_types	= 'stable,unstable,testing,sexy';

#tab
$tab  = empty($_REQUEST['tab']) ? '' : $_REQUEST['tab'];

?>
<html>
<head>
	<title><?php echo $module_id; ?></title>
	<!-- onglets -->
	<?php 
	
	echo dcPage::jsPageTabs($tab); 
	?>
</head>
<body>
 <?php
# menu
    echo dcPage::breadcrumb(
        array(
            __('Plugins')     => '',
            __($module_id) => ''
        )
    );
# messages
	echo dcPage::notices();
?>

    <div class="fieldset">
	<p class="as_h3"><?php echo $index_comment; ?></p>
	<p class="as_h4"><?php printf(__("your current %s status is '<i>%s</i>'"), __('preferences about dotclear updates'), $show_on); ?></p>
	<p class="info">
		<?php 
		#add '.dcLatestVersionsLight' to link id version >= 2.15
				$id = version_compare($dc_best, DC_VERSION, '>=') ?'' :'.dcLatestVersionsLight';
			echo sprintf(
					__('You can change this options in your %s %s.'),
					'<a href="' . $core->adminurl->get('admin.user.preferences') 
								. '#user-favorites' .$id .'">' 
								. __('Preferences on Dashboard') . '</a>',
					'<br><i>' .__('also see below.') .'</i>'
						);
		?>
	</p>
<?php
/* just if PLUG_DEBUG */
if(defined('PLUG_DEBUG') && PLUG_DEBUG) {
	$file = dirname(__FILE__) .'/debug.php';
		if(file_exists($file)) {
			require_once $file;
		}
}//if PLUG_DEBUG
echo '</div>';
/* help */
dcPage::helpBlock('dcLatestVersionsLight');
?>

</body>
</html>
