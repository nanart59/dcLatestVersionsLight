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
	- name
	- description
	- author(s)
	- version -- model: last versions
	- properties
		. require (tested) Dc 2.16.x or >
		. require (tested)php 7.3.1
		- usage: content all & admin
		- setting: user pref favorite
	- to do: tests on Dotclear < 2.16
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
	'0.1',
	/* Properties */
	[
		'requires'    => [['core', '2.16']],				        // dotclear min version (tested)
		'permissions' => 'contentadmin',					// Permissions all admin dashboard user
		'type' => 'plugin',							// type
		'priority' => 		2000,						//priority
		'support' => 'https://github.com/nanart59/dcLatestVersionsLight',
		'details' => 'https://github.com/nanart59/dcLatestVersionsLight',
		'settings'    => [							// Settings
			'pref' => '#user-favorites.dcLatestVersionsLight'
			],
	]
);

