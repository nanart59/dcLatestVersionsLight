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
 * define plugin':
	- name: dcLatestVersionsLight
	- description: Show the latest available versions of Dotclear in dashboard
	- author(s): Nan'Art and contributors
	- version: 1.13
	- properties
		. require (tested) Dc 2.9.x or >
		. require (tested)php 5.6.40 7.4.1
		- usage: content admin -- no widget
		- setting: 
			user pref favorite, class dcLatestVersionsLight
		- support: https://forum.dotclear.org/viewtopic.php?id=49826
		- details: https://plugins.dotaddict.org/dc2/details/dcLatestVersionsLight
 */
/* dcLatestVersionsLight/_define.php */

if (!defined('DC_RC_PATH')) {
	return null;
}
 
$this->registerModule(
	/* Name */
	"dcLatestVersionsLight",
	/* Description*/
	"Show the latest available versions of Dotclear in dashboard",
	/* Author */
	"Nan'Art and contributors",
	/* Version */
	'1.13',
	/* Properties */
	[
		'requires'    => [['core', '2.9']],				// dotclear min version (tested)
		'permissions' => 'contentadmin',					// Permissions all admin dashboard user
		'type' => 'plugin',									// type
		'priority' => 		2000,							//priority
		'support' => 'https://github.com/nanart59/dcLatestVersionsLight',
		'details' => 'https://github.com/nanart59/dcLatestVersionsLight',
		'settings'    => [									// Settings
			'pref' => '#user-favorites.dcLatestVersionsLight'
			],
	]
);
