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

var app_config = {
    init: function () {
        $("#saveconf").click(function () {
			$.ajax({
				type: 'POST',
				url: 'plugins/mobile/core/ajax/mobile.ajax.php',
				data: {
					action: 'saveJSON',
					file: $("#platformFile").val()
				},
				dataType: 'json',
				global: false,
				error: function (request, status, error) {
					handleAjaxError(request, status, error, $('#div_configMobileAlert'));
				},
				success: function (data) {
					if (data['result']!=false) {
                        $('#div_configMobileAlert').showAlert({
                            message: '{{Sauvegarde de la configuration réussie. Relancez le demon}}',
                            level: 'success'
                        });
                    } else {
                        $('#div_configMobileAlert').showAlert({
                            message: '{{Echec de la sauvegarde de la configuration : }}' + data['data'],
                            level: 'danger'
                        });
                    }
				}
			});
        });
    },
    show: function () {
		$.ajax({
			url: 'plugins/mobile/core/ajax/mobile.ajax.php',
			data: {
				action: 'getJSON'
			},
			dataType: 'json',
			global: false,
			error: function (request, status, error) {
				handleAjaxError(request, status, error, $('#div_configMobileAlert'));
			},
			success: function (data) {
				$("#platformFile").val(data['result']);
			}
		});
    },
    hide: function () {

    }
}
