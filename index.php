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
/* dcLatestVersionsLight/ndex.php */

if (!defined('DC_CONTEXT_ADMIN')) {return;}

#debug ?
//*/
	define("DEBUG", true);
//*/

#dc min version (tested)
	$dc_min = '2.16';
#php min version (tested)
#@ignore
	$php_min = '7.4.1';

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

?>
<html>
<head>
	<title><?php echo $module_id; ?></title>
	<!-- #onglets -->
	<?php echo dcPage::jsPageTabs($default_tab); ?>
</head>
<body>
 <?php
# menu
	echo dcPage::breadcrumb(
		[
			html::escapeHTML($core->blog->name) => '',
			$module_id						=> ''
		]);
# messages
	echo dcPage::notices();
?>

	<p class="as_h3"><?php echo $index_comment; ?></p>
	<p class="as_h4"><?php printf(__("your current %s status is '<i>%s</i>'"), __('preferences about dotclear updates'), $show_on); ?></p>
	<p class="info">
		<?php 
			echo sprintf(
					__('You can change this options in your %s %s.'),
					'<a href="' . $core->adminurl->get('admin.user.preferences') 
								. '#user-favorites.dcLatestVersionsLight">' 
								. __('Preferences on Dashboard') . '</a>',
					'<br><i>' .__('also see below.') .'</i>'
						);
		?>
	</p>
<?php
/* just if DEBUG */
if(defined('DEBUG') && DEBUG) {
	$file = dirname(__FILE__) .'/debug.php';
		if(file_exists($file)) {
			require_once $file;
		}
}//if DEBUG
?>
</body>
</html>
