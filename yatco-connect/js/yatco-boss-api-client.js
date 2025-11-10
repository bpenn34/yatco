var _YBA={};

	_YBA.url = '';

	_YBA.YACHTS={};

	_YBA.FORM_SELECT_OPTIONS={};

	_YBA.call_api=function(method, path, passing_data) {
		var xhttp = new XMLHttpRequest();

		return new Promise(function(resolve, reject) {
			
			xhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {

					var responseData = JSON.parse( this.responseText );

					resolve(responseData);

				}
			};

			switch (method) {
				case 'GET':
					var searchParams = new URLSearchParams();

					for (const property in passing_data) {
						searchParams.set(property, passing_data[ property ]);
					}

					xhttp.open("GET", _yatco_wp_url._yatco_wp_rest_url+"yatco/"+ path +"?"+searchParams.toString(), true);

					xhttp.send();

					break;

				case 'POST':

					xhttp.open("POST", _yatco_wp_url._yatco_wp_rest_url+"yatco/"+ path, true);

					xhttp.setRequestHeader('Content-Type', 'application/json');

					xhttp.send(JSON.stringify(passing_data));

					break;
			}
			
			/*
				xhttp.open("POST", "http://data-wordpress.yatcoboss.com:80/API/V1/ForSale/Vessel/Search", true);

				xhttp.setRequestHeader('Authorization', "Basic /lQhJqv6kgCkA8Gxv/tNSU1mU6CuzLMXWo92FEKcLPg=");
				xhttp.setRequestHeader('Accept', 'application/json');
				xhttp.setRequestHeader('Content-Type', 'application/json');
			*/

		});

	};

	_YBA.label_options=function(label) {
		var passing_data;

		if (typeof label == 'object' && ! Array.isArray(label)) {
			passing_data = label;

		} else if (label === '' || (Array.isArray(label) && label.length == 0)) {

			return {};

		} else if (typeof label == 'string' || Array.isArray(label)) {
			passing_data={};

			passing_data.label = label;
		}

		return _YBA.call_api('GET', 'form-data-common', passing_data);
	};

	_YBA.yacht_search=function(params) {

		if (typeof params == 'undefined') {
			params = {};
		}
 
		return _YBA.call_api('POST', 'yachts', params);

	};

	_YBA.yacht_details=function(id) {
		
		return _YBA.call_api('POST', 'yacht-detail', {vessel_id: id});

	};

	_YBA.charter_search=function(params) {

		if (typeof params == 'undefined') {
			params = {};
		}
 
		return _YBA.call_api('POST', 'charters', params);

	};

	_YBA.broker_search=function(params) {

		if (typeof params == 'undefined') {
			params = {};
		}
 
		return _YBA.call_api('POST', 'brokers', params);

	};

	_YBA.brokerage_search=function(params) {

		if (typeof params == 'undefined') {
			params = {};
		}
 
		return _YBA.call_api('POST', 'brokerages', params);

	};

	_YBA.send_lead=function(params) {

		return _YBA.call_api('POST', 'send-lead', params);

	};
