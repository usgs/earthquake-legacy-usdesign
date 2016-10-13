/**
 *	@import usgs/location/LocationModel.js
 */
var usdtool = {};
(function() {
	// Start up the application
	usdtool.init = function() {
		controls.init();
		map.init();
		results.init();
		new Dialog;
	};

	var controls = usdtool.controls = {
		hide: function(sel) {
//			$(sel).val('').removeAttr('required').closest('li').hide();
			$(sel).removeAttr('required').closest('li').hide();
		},
		show: function(sel) {
			$(sel).attr('required', true).closest('li').show();
		},
		dcodes: {
			'asce-2005': function() {
				controls.show('#riskcategory');
				controls.setLabelName('#riskcategory', 'Occupancy Category');
			},
			'asce-2010': function() {
				controls.show('#riskcategory');
				controls.setLabelName('#riskcategory', 'Risk Category');
			},
			'nehrp-2003': function() {
				controls.show('#riskcategory');
				controls.setLabelName('#riskcategory', 'Seismic Use Group');
				$('#riskcategory option').html(function (i) {
					return ['Please Select&hellip;', 'I or II', 'III (e.g., essential facilities)'][i];
				});
			},
			'nehrp-2009': function() {
				controls.show('#riskcategory');
				controls.setLabelName('#riskcategory', 'Risk Category');
			},
			'nehrp-2015': function() {
				controls.show('#riskcategory');
				controls.setLabelName('#riskcategory', 'Risk Category');
				$('#siteclass').append('<option value="5">Undefined</option>');
				updatePos(); // In case location has now become valid.
			},
			'ibc-2009': function() {
				controls.show('#riskcategory');
				controls.setLabelName('#riskcategory', 'Occupancy Category');
			},
			'ibc-2012': function() {
				controls.show('#riskcategory');
				controls.setLabelName('#riskcategory', 'Risk Category');
			},
			'asce_41-2013': function() {
				var $varsel = $('#designCodeVariant');
				$varsel.empty();

				var variants = ['BSE-2N', 'BSE-1N', 'BSE-2E', 'BSE-1E', 'Custom'];

				$(variants).each(function(i, val) {
					$varsel.append('<option value="' + i + '">' + val + '</option>');
				});

				$varsel.change(function() {
					if ($varsel.val() == '4') {
						controls.show('#hazardProbability');
					} else {
						controls.hide('#hazardProbability');
					}
				});

				$varsel.closest('li').show();
				updatePos(); // In case location has now become valid.
			},
			'asce_41-2006': function() {
				var $varsel = $('#designCodeVariant');
				$varsel.empty();

				var variants = ['BSE-1', 'BSE-2', 'Custom'];

				$(variants).each(function(i, val) {
					$varsel.append('<option value="' + i + '">' + val + '</option>');
				});

				$varsel.closest('li').show();
			}
		},
		init: function() {
			$('#designCode').change(function() {
				controls.showForDcode($('#designCode').val());
			}).change();
			$('#asce-erratum-expand').click(function() {
				$('#asce-erratum').dialog({width: 600});

				return false;
			});
		},
		setLabelName: function(control_sel, name) {
			var label = $(control_sel).prev('label').get(0);
			if (label.firstChild.nodeValue) {
				label.firstChild.nodeValue = name; // IE7 fix
			} else {
				label.firstChild.textContent = name;
			}
		},
		showForDcode: function(edition_id) {
			this.hide('#designCodeVariant, #hazardProbability, #riskcategory');
			$('#siteclass option[value="5"]').remove();
			this.show('#siteclass');
			$('#riskcategory option').html(function (i) {
				return ['Please Select&hellip;', 'I or II or III',
						'IV (e.g., essential facilities)'][i];
			});
			this.dcodes[edition_id] && this.dcodes[edition_id]();
		}
	};


	var map = usdtool.map = {
		init: function() {
			this.locationmodel = new usgs.location.LocationModel({
				latitude: 39.0, longitude: -100.0
			});
			this.locationview = new usgs.location.LocationView({
				el: document.getElementById('mapdiv'),
				model: this.locationmodel,
				map: {
					zoom: 3,
					geocodeControlOpts: {
						placeholderText: 'Enter address (optional)',
						expanded: true
					}
				}
			});
			this.locationmodel.bind('change', (function (scope, model) {
				return function () { scope.onModelChange(model); };
			})(this, this.locationmodel));
		},

		onModelChange: function (model) {
			var lat = model.get('latitude'),
			    lng = model.get('longitude');

			if (this.pointInBounds(lat, lng)) {
				$('#latitude').val(lat);
				$('#longitude').val(lng);
			} else {
				alert('Input location is out of bounds, please try again.');
				// Go back to old location
				lat = parseFloat($('#latitude').val());
				lng = parseFloat($('#longitude').val());

				if (!this.pointInBounds(lat, lng)) {
					// Previous point was bad. Use default point.
					lat = 39.0;
					lng = -100.0;
				}

				model.set({latitude: lat, longitude: lng});
			}
		},

		pointInBounds: function(lat, lng) {
			return (lat && lng &&
				(
					( // Conterminous US
						lat >= 24.6 &&
						lat <= 50.0 &&
						lng >= -125.0 &&
						lng <= -67.0
					)
					||
					( //Hawaii
						$('#designCode').val() != 'nehrp-2015' &&
						lat >= 18.0 &&
						lat <= 23.0 &&
						lng >= -161.0 &&
						lng <= -154.0
					)
					||
					( //Alaska
						$('#designCode').val() != 'nehrp-2015' &&
						lat >= 49.75 &&
						lat <= 71.5 &&
						lng >= -196.6 &&
						lng <= -127.45
					)
					||
					( //Puerto Rico
						$('#designCode').val() != 'nehrp-2015' &&
						lat >= 16.0 &&
						lat <= 21.0 &&
						lng >= -70.0 &&
						lng <= -62.0
					)
					||
					( //Guam
						($('#designCode').val() == 'nehrp-2015' ||
						 $('#designCode').val() == 'asce_41-2013') &&
						lat >= 9.0 &&
						lat <= 23.0 &&
						lng >= 139.0 &&
						lng <= 151.0
					)
					||
					( //American Samoa
						($('#designCode').val() == 'nehrp-2015' ||
						 $('#designCode').val() == 'asce_41-2013') &&
						lat >= -33.0 &&
						lat <= -11.0 &&
						lng >= -195.0 &&
						lng <= -165.0
					)
				)
			);
		}
	};

	var updatePos = function() {
		window.clearInterval(latInterval);
		window.clearInterval(lonInterval);
		var lat = parseFloat($('#latitude').val());
		var lon = parseFloat($('#longitude').val());
		if (!isNaN(lat) && !isNaN(lon) && map.pointInBounds(lat, lon)) {
			var accuracy = 111000;
			if (lat % 1 != 0 && lon % 1 != 0) {
				var latDec = (String(lat).split('.')[1].length);
				var lonDec = (String(lon).split('.')[1].length);
				if (latDec > lonDec) accuracy = 111111/Math.pow(10, lonDec);
				else accuracy = 111111/Math.pow(10, latDec);
			}
			if (accuracy < 1) accuracy = 1;
			map.locationview.setLocation({latlng: {lat: lat, lng: lon}, accuracy: accuracy});
		}
	};

	var latInterval, lonInterval;

	$('#latitude').change(function() {
		updatePos();
	});

	$('#longitude').change(function() {
		updatePos();
	});

	$('#latitude').keyup(function() {
		window.clearInterval(latInterval);
		latInterval = window.setInterval(updatePos, 3000);
	});

	$('#longitude').keyup(function() {
		window.clearInterval(lonInterval);
		lonInterval = window.setInterval(updatePos, 3000);
	});

	var results = usdtool.results = {
		init: function() {
			// Add a handler to handle the submit button to compute results.
			$('#application').submit(function() {
				if ($('#designCode').val() == "") {
					window.IO.alert('You must select the "Model Building Code" ' +
							'before computing the report.',
							{title:'Error: Soil Conditions Not Set'}
						);
				} else if ($('#siteclass').val() == "") {
					window.IO.alert('You must select the site soil conditions before ' +
							'computing the report. If you are unsure which site soil ' +
							'class to select, the design code indicates you should ' +
							'select "Site Class D".',
							{title:'Error: Soil Conditions Not Set'}
						);
				} else if ($('#riskcategory').is(':visible') && $('#riskcategory').val() == "") {
					window.IO.alert('You must select the risk category before ' +
							'computing the report.',
							{title:'Error: Risk Category Not Set'}
						);
				} else {
					window.IO.showLoading('Computing Results');
					usdtool.results.submit();
				}
			});
		},

		submit: function() {
			var latitude = $('#latitude').val();
			var longitude = $('#longitude').val();
			if (!map.pointInBounds(
				parseFloat(latitude),
				parseFloat(longitude)
				)) {
				IO.alert(
					'The location was not within the regions supported by ' +
					'this tool. Please select a valid location.',
					{title: 'Location Out of Bounds'}
				);
				return false;
			}
			var siteclass;
			siteclass = $('#siteclass').val() || -1;
			var riskcategory = $('#riskcategory').val() || -1;
			var edition = $('#designCode').val();
			var variant = $('#designCodeVariant').val();
			var pe50 = $('#hazardProbability').val();

			// Only ASCE 41 editions support variant/pe50
			if (edition !== 'asce_41-2013' && edition !== 'asce_41-2006') {
				variant = 0;
				pe50 = '';
			} else {
				// Only include pe50 for ASCE 41 editions when variant is "custom"
				if (edition === 'asce_41-2013' && variant !== '4') {
					pe50 = '';
				} else if (edition === 'asce_41-2006' && variant !== '2') {
					pe50 = '';
				}
			}

			var url = BASE_URL + '/inc/dataminer.inc.php?';
			var params = {
				'latitude': latitude,
				'longitude': longitude,
				'siteclass': siteclass,
				'riskcategory': riskcategory,
				'edition': edition,
				'variant': variant + '',
				'pe50': pe50 + ''
			};

			if ($('#reportTitle').val() != '') {
				params['reportTitle'] = $('#reportTitle').val();
			}

			$.get(url + $.param(params), function(_r) {

				// Handle error messages
				if (_r.error) {
					var message = _r.error;
					var split_idx = message.indexOf('--');
					var error_title = message.substring(0, split_idx - 1);
					var error_text  = message.substring(split_idx + 3);
					window.IO.alert(error_text, {'title': error_title});
					return; // Stop trying to give a report
				}

				// If we got here then we are good to go
				var url = _r.source_host + '/summary.php?';
				var params = {
					'template': 'minimal',
					'latitude': latitude,
					'longitude': longitude,
					'siteclass': siteclass,
					'riskcategory': riskcategory,
					'edition': edition,
					'variant': variant + '',
					'pe50': pe50 + '',
					'resultid': _r.result_id
				};

				if ($('#reportTitle').val()!='') {
					params['reportTitle'] = $('#reportTitle').val();
				}

				var opts = 'status=0,toolbar=0,location=0,menubar=0,directories=0,' +
						'resizable=1,scrollbars=1,height=900,width=700';


				var success = false;
				alertPopupBlocker = function() {
					if (!success) {
						$("#popupBlocker").dialog();
					}
					window.IO.closeDialog(null);
				};
				usdtool.results.POPUP_TIMEOUT = setTimeout('alertPopupBlocker()', 3000);
				var results = window.open(url + $.param(params), 'report', opts);

				window.popup_succeeded = function() {
					success = true;
					window.IO.closeDialog(null);
				};
			});
		}
	}
})();

$(document).ready(usdtool.init);
