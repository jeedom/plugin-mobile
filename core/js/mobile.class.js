jeedom.mobile = function() {
}


jeedom.mobile.panel = function() {
}


jeedom.mobile.panel.test = function(_params) {
  	var paramsRequired = ['method', 'options']
  	var paramsSpecifics = {}
  	try {
  		jeedom.private.checkParamsRequired(_params || {}, paramsRequired)
  	} catch (e) {
  		(_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e)
  		return
  	}
  	var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {})
  	var paramsAJAX = jeedom.private.getParamsAJAX(params)
  	paramsAJAX.url = 'plugins/mobile/core/ajax/mobile.ajax.php'
  	paramsAJAX.data = {
  		action: 'testPanel',
  		method: _params.method,
  		options: _params.options,
  	}
  	$.ajax(paramsAJAX)
}
