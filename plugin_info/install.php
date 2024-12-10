<?php
/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
 */
require_once dirname(__FILE__) . '/../../../core/php/core.inc.php';

function mobile_install()
{
	/* function launched when activating the plugin or when installing the plugin */
	log::add('mobile', 'debug', '┌────────── :fg-warning: Launch function mobile_install() :/fg: ──────────');

	/* Create folder for notifications */
	$pathNotifications = dirname(__FILE__) . '/../core/data/notifications/';
	if (!is_dir($pathNotifications)) {
		log::add('mobile', 'debug', '| creating folder for the notifications');
		mkdir($pathNotifications, 0775, true);
	}
	log::add('mobile', 'debug', '└───────────────────────────────────────────');
}

function mobile_update()
{
	/* function launched when updating plugin */
	log::add('mobile', 'debug', '┌────────── :fg-warning: Launch function mobile_update() :/fg: ──────────');

	/* Create folder for notifications */
	$pathNotifications = dirname(__FILE__) . '/../core/data/notifications/';
	if (!is_dir($pathNotifications)) {
		log::add('mobile', 'debug', '| creating folder for the notifications');
		mkdir($pathNotifications, 0775, true);
	}

	$mobiles = eqLogic::byType('mobile');
	foreach ($mobiles as $mobile) {
		/* Delete mobile with bad logicalId */
		if ($mobile->getLogicalId() == null || $mobile->getLogicalId() == "") {
			log::add('mobile', 'debug', '| Removing equipment ' . $mobile->getId() . ' because it does not contain a logicalId');
			$mobile->remove();
			continue;
		}
		/* Set menu by defaut if no exist */
		$customMenu  = $mobile->getConfiguration('menuCustomArray');
		if (empty($customMenu)) {
			log::add('mobile', 'debug', '| Assigning a default menu to the equipment ' . $mobile->getId());
			$menuCustomArray = mobile::getMenuDefaultV2();
			$mobile->setConfiguration('nbIcones', count($menuCustomArray));
			$mobile->setConfiguration('defaultIdMobile', $mobile->getId());
			$mobile->setConfiguration('menuCustomArray', $menuCustomArray);
		}
		$mobile->save();
	}

	/* Delete old "menuCustom_" and "NoCut" save into config of plugin */
	foreach (config::searchKey('menuCustom_', 'mobile') as $configMenuCustom) {
		config::remove($configMenuCustom['key'], 'mobile');
	}
	foreach (config::searchKey('NoCut', 'mobile') as $iconNoCut) {
		config::remove($iconNoCut['key'], 'mobile');
	}

	/* Delete old infos save into config of plugin */
	config::remove('previousMenus', 'mobile');
	config::remove('pluginPanelOutMobile', 'mobile');
	config::remove('checkdefaultID', 'mobile');

	/* Delete old files of plugin */
	$oldFiles = [
		'/../desktop/css/panel.css',
		'/../desktop/php/panelMenuCustom.php',
		'/../desktop/php/modalConfigPlugin.php',
		'/../desktop/js/panelMenuCustom.js',
		'/../desktop/modal/health.php',
		'/../desktop/modal/modal.previousMenus.php',
		'/../desktop/modal/plugin.php',
		'/../desktop/modal/piece.php',
		'/../desktop/modal/scenario.php',
		'/../desktop/modal/info_app.mobile.php',
		'/../desktop/modal/plugin.mobile.php',
		'/../desktop/modal/object.mobile.php',
		'/../desktop/modal/scenario.mobile.php',
		'/../desktop/modal/update.mobile.php',
		'/../desktop/modal/firstPage.php',
		'/../desktop/modal/secPage.php',
		'/../desktop/modal/thirdPage.php',
		'/../desktop/modal/fourPage.php',
		'/../desktop/modal/fivePage.php',
		'/../desktop/modal/fiveModal.php',
		'/../desktop/modal/sixPage.php',
		'/../desktop/modal/wizard.php',
		'/../core/data/wizard.json',
		'/../data/mobile.json'
	];
	foreach ($oldFiles as $oldFile) {
		if (file_exists(dirname(__FILE__) . $oldFile)) {
			log::add('mobile', 'debug', '| Removing old file : ' . dirname(__FILE__) . $oldFile);
			shell_exec('rm ' . dirname(__FILE__) . $oldFile);
		}
	}

	/* Delete the old images in "core" folder */
	$oldCoreImgs = [
		'Button_Dashboard_icon@3x.png',
		'Button_Design_icon@3x.png',
		'Button_Synthese_icon@3x.png',
		'Button_URL_icon@3x.png',
		'IMG_0738.PNG',
		'android.png',
		'ios.png',
		'v22methods.jpeg',
		'v2ActualBoxFlouted.jpeg',
		'v2AddZone.jpeg',
		'v2ConnectBox.jpeg',
		'v2FullMenu.jpeg',
		'v2MenuBoxs.PNG',
		'v2MenuBoxs.jpeg',
		'v2ModalMenuCustom.png',
		'v2ModalQrCode.png',
		'v2ModifyBigRadius.jpeg',
		'v2ModifyLittleRadius.jpeg',
		'v2QRCodeConnect.PNG',
		'v2ZoneInactive.jpeg',
		'v2connectMarket.jpeg',
		'v2firstConnect.jpeg',
		'v2floutedBoxs.png',
		'v2greenBtnAdd.PNG',
		'mobile_icon.png',
		'v2app.png'
	];

	foreach ($oldCoreImgs as $oldCoreImg) {
		if (file_exists(dirname(__FILE__) . '/../core/img/' . $oldCoreImg)) {
			log::add('mobile', 'debug', '| Removing old image : ' . dirname(__FILE__) . '/../core/img/' . $oldCoreImg);
			shell_exec('rm ' . dirname(__FILE__) . '/../core/img/' . $oldCoreImg);
		}
	}
	if (file_exists(dirname(__FILE__) . '/../core/img') && !glob(dirname(__FILE__) . '/../core/img/' . '*')) {
		log::add('mobile', 'debug', '| Deleting empty core/img folder');
		shell_exec('rm -rf ' . dirname(__FILE__) . '/../core/img');
	}

	/* cleaning 3rdparty folder  */
	$old3rdpartyFolders = ['animate', 'css', 'js'];
	foreach ($old3rdpartyFolders as $old3rdpartyFolder) {
		if (file_exists(dirname(__FILE__) . '/../3rdparty/' . $old3rdpartyFolder)) {
			log::add('mobile', 'debug', '| Deleting folder ' . dirname(__FILE__) . '/../3rdparty/' . $old3rdpartyFolder);
			shell_exec('rm -rf ' . dirname(__FILE__) . '/../3rdparty/' . $old3rdpartyFolder);
		}
	}

	/* cleaning data folder */
	$path = dirname(__FILE__) . '/../data/';
	foreach (scandir($path) as $file) {
		if ($file != "." && $file != ".." && $file != ".htaccess" && $file != "images") {
			if (is_dir($path . '/' . $file)) {
				/* delete dashboard.json and favdash.json if exists */
				if (file_exists($path . $file . '/dashboard.json')) {
					log::add('mobile', 'debug', '| Deleting ' . $path . $file . '/dashboard.json');
					shell_exec('rm ' . $path . $file . '/dashboard.json');
				}
				if (file_exists($path . $file . '/favdash.json')) {
					log::add('mobile', 'debug', '| Deleting ' . $path . $file .  '/favdash.json');
					shell_exec('rm ' . $path . $file . '/favdash.json');
				}
				/* delete folder if empty */
				if (!glob($path . $file . '/*')) {
					log::add('mobile', 'debug', '| Deleting empty folder : ' . $path . $file);
					shell_exec('rm -rf ' . $path . $file);
				}
			}
		}
	}

	/* Generate ApiKey if no exist */
	jeedom::getApiKey('mobile');

	log::add('mobile', 'debug', '└───────────────────────────────────────────');
}
