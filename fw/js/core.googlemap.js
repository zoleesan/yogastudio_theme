function yogastudio_googlemap_init(dom_obj, coords) {
	"use strict";
	if (typeof YOGASTUDIO_GLOBALS['googlemap_init_obj'] == 'undefined') yogastudio_googlemap_init_styles();
	YOGASTUDIO_GLOBALS['googlemap_init_obj'].geocoder = '';
	try {
		var id = dom_obj.id;
		YOGASTUDIO_GLOBALS['googlemap_init_obj'][id] = {
			dom: dom_obj,
			markers: coords.markers,
			geocoder_request: false,
			opt: {
				zoom: coords.zoom,
				center: null,
				scrollwheel: false,
				scaleControl: false,
				disableDefaultUI: false,
				panControl: true,
				zoomControl: true, //zoom
				mapTypeControl: false,
				streetViewControl: false,
				overviewMapControl: false,
				styles: YOGASTUDIO_GLOBALS['googlemap_styles'][coords.style ? coords.style : 'default'],
				mapTypeId: google.maps.MapTypeId.ROADMAP
			}
		};
		
		yogastudio_googlemap_create(id);

	} catch (e) {
		
		dcl(YOGASTUDIO_GLOBALS['strings']['googlemap_not_avail']);

	};
}

function yogastudio_googlemap_create(id) {
	"use strict";

	// Create map
	YOGASTUDIO_GLOBALS['googlemap_init_obj'][id].map = new google.maps.Map(YOGASTUDIO_GLOBALS['googlemap_init_obj'][id].dom, YOGASTUDIO_GLOBALS['googlemap_init_obj'][id].opt);

	// Add markers
	for (var i in YOGASTUDIO_GLOBALS['googlemap_init_obj'][id].markers)
		YOGASTUDIO_GLOBALS['googlemap_init_obj'][id].markers[i].inited = false;
	yogastudio_googlemap_add_markers(id);
	
	// Add resize listener
	jQuery(window).resize(function() {
		if (YOGASTUDIO_GLOBALS['googlemap_init_obj'][id].map)
			YOGASTUDIO_GLOBALS['googlemap_init_obj'][id].map.setCenter(YOGASTUDIO_GLOBALS['googlemap_init_obj'][id].opt.center);
	});
}

function yogastudio_googlemap_add_markers(id) {
	"use strict";
	for (var i in YOGASTUDIO_GLOBALS['googlemap_init_obj'][id].markers) {
		
		if (YOGASTUDIO_GLOBALS['googlemap_init_obj'][id].markers[i].inited) continue;
		
		if (YOGASTUDIO_GLOBALS['googlemap_init_obj'][id].markers[i].latlng == '') {
			
			if (YOGASTUDIO_GLOBALS['googlemap_init_obj'][id].geocoder_request!==false) continue;
			
			if (YOGASTUDIO_GLOBALS['googlemap_init_obj'].geocoder == '') YOGASTUDIO_GLOBALS['googlemap_init_obj'].geocoder = new google.maps.Geocoder();
			YOGASTUDIO_GLOBALS['googlemap_init_obj'][id].geocoder_request = i;
			YOGASTUDIO_GLOBALS['googlemap_init_obj'].geocoder.geocode({address: YOGASTUDIO_GLOBALS['googlemap_init_obj'][id].markers[i].address}, function(results, status) {
				"use strict";
				if (status == google.maps.GeocoderStatus.OK) {
					var idx = YOGASTUDIO_GLOBALS['googlemap_init_obj'][id].geocoder_request;
					if (results[0].geometry.location.lat && results[0].geometry.location.lng) {
						YOGASTUDIO_GLOBALS['googlemap_init_obj'][id].markers[idx].latlng = '' + results[0].geometry.location.lat() + ',' + results[0].geometry.location.lng();
					} else {
						YOGASTUDIO_GLOBALS['googlemap_init_obj'][id].markers[idx].latlng = results[0].geometry.location.toString().replace(/\(\)/g, '');
					}
					YOGASTUDIO_GLOBALS['googlemap_init_obj'][id].geocoder_request = false;
					yogastudio_googlemap_add_markers(id);
				} else
					dcl(YOGASTUDIO_GLOBALS['strings']['geocode_error'] + ' ' + status);
			});
		
		} else {
			
			// Prepare marker object
			var latlngStr = YOGASTUDIO_GLOBALS['googlemap_init_obj'][id].markers[i].latlng.split(',');
			var markerInit = {
				map: YOGASTUDIO_GLOBALS['googlemap_init_obj'][id].map,
				position: new google.maps.LatLng(latlngStr[0], latlngStr[1]),
				clickable: YOGASTUDIO_GLOBALS['googlemap_init_obj'][id].markers[i].description!=''
			};
			if (YOGASTUDIO_GLOBALS['googlemap_init_obj'][id].markers[i].point) markerInit.icon = YOGASTUDIO_GLOBALS['googlemap_init_obj'][id].markers[i].point;
			if (YOGASTUDIO_GLOBALS['googlemap_init_obj'][id].markers[i].title) markerInit.title = YOGASTUDIO_GLOBALS['googlemap_init_obj'][id].markers[i].title;
			YOGASTUDIO_GLOBALS['googlemap_init_obj'][id].markers[i].marker = new google.maps.Marker(markerInit);
			
			// Set Map center
			if (YOGASTUDIO_GLOBALS['googlemap_init_obj'][id].opt.center == null) {
				YOGASTUDIO_GLOBALS['googlemap_init_obj'][id].opt.center = markerInit.position;
				YOGASTUDIO_GLOBALS['googlemap_init_obj'][id].map.setCenter(YOGASTUDIO_GLOBALS['googlemap_init_obj'][id].opt.center);				
			}
			
			// Add description window
			if (YOGASTUDIO_GLOBALS['googlemap_init_obj'][id].markers[i].description!='') {
				YOGASTUDIO_GLOBALS['googlemap_init_obj'][id].markers[i].infowindow = new google.maps.InfoWindow({
					content: YOGASTUDIO_GLOBALS['googlemap_init_obj'][id].markers[i].description
				});
				google.maps.event.addListener(YOGASTUDIO_GLOBALS['googlemap_init_obj'][id].markers[i].marker, "click", function(e) {
					var latlng = e.latLng.toString().replace("(", '').replace(")", "").replace(" ", "");
					for (var i in YOGASTUDIO_GLOBALS['googlemap_init_obj'][id].markers) {
						if (latlng == YOGASTUDIO_GLOBALS['googlemap_init_obj'][id].markers[i].latlng) {
							YOGASTUDIO_GLOBALS['googlemap_init_obj'][id].markers[i].infowindow.open(
								YOGASTUDIO_GLOBALS['googlemap_init_obj'][id].map,
								YOGASTUDIO_GLOBALS['googlemap_init_obj'][id].markers[i].marker
							);
							break;
						}
					}
				});
			}
			
			YOGASTUDIO_GLOBALS['googlemap_init_obj'][id].markers[i].inited = true;
		}
	}
}

function yogastudio_googlemap_refresh() {
	"use strict";
	for (id in YOGASTUDIO_GLOBALS['googlemap_init_obj']) {
		yogastudio_googlemap_create(id);
	}
}

function yogastudio_googlemap_init_styles() {
	// Init Google map
	YOGASTUDIO_GLOBALS['googlemap_init_obj'] = {};
	YOGASTUDIO_GLOBALS['googlemap_styles'] = {
		'default': [],
		'invert': [ { "stylers": [ { "invert_lightness": true }, { "visibility": "on" } ] } ],
		'dark': [{"featureType":"landscape","stylers":[{ "invert_lightness": true },{"saturation":-100},{"lightness":65},{"visibility":"on"}]},{"featureType":"poi","stylers":[{"saturation":-100},{"lightness":51},{"visibility":"simplified"}]},{"featureType":"road.highway","stylers":[{"saturation":-100},{"visibility":"simplified"}]},{"featureType":"road.arterial","stylers":[{"saturation":-100},{"lightness":30},{"visibility":"on"}]},{"featureType":"road.local","stylers":[{"saturation":-100},{"lightness":40},{"visibility":"on"}]},{"featureType":"transit","stylers":[{"saturation":-100},{"visibility":"simplified"}]},{"featureType":"administrative.province","stylers":[{"visibility":"off"}]},{"featureType":"water","elementType":"labels","stylers":[{"visibility":"on"},{"lightness":-25},{"saturation":-100}]},{"featureType":"water","elementType":"geometry","stylers":[{"hue":"#ffff00"},{"lightness":-25},{"saturation":-97}]}],
		'simple': [
				{
				  stylers: [
					{ hue: "#00ffe6" },
					{ saturation: -20 }
				  ]
				},{
				  featureType: "road",
				  elementType: "geometry",
				  stylers: [
					{ lightness: 100 },
					{ visibility: "simplified" }
				  ]
				},{
				  featureType: "road",
				  elementType: "labels",
				  stylers: [
					{ visibility: "off" }
				  ]
				}
			  ],
	'greyscale': [
					{
						"stylers": [
							{ "saturation": -100 }
						]
					}
				],
	'greyscale2': [
				{
				 "featureType": "landscape",
				 "stylers": [
				  { "hue": "#FF0300" },
				  { "saturation": -100 },
				  { "lightness": 20.4705882352941 },
				  { "gamma": 1 }
				 ]
				},
				{
				 "featureType": "road.highway",
				 "stylers": [
				  { "hue": "#FF0300" },
				  { "saturation": -100 },
				  { "lightness": 25.59999999999998 },
				  { "gamma": 1 }
				 ]
				},
				{
				 "featureType": "road.arterial",
				 "stylers": [
				  { "hue": "#FF0300" },
				  { "saturation": -100 },
				  { "lightness": -22 },
				  { "gamma": 1 }
				 ]
				},
				{
				 "featureType": "road.local",
				 "stylers": [
				  { "hue": "#FF0300" },
				  { "saturation": -100 },
				  { "lightness": 21.411764705882348 },
				  { "gamma": 1 }
				 ]
				},
				{
				 "featureType": "water",
				 "stylers": [
				  { "hue": "#FF0300" },
				  { "saturation": -100 },
				  { "lightness": 21.411764705882348 },
				  { "gamma": 1 }
				 ]
				},
				{
				 "featureType": "poi",
				 "stylers": [
				  { "hue": "#FF0300" },
				  { "saturation": -100 },
				  { "lightness": 4.941176470588232 },
				  { "gamma": 1 }
				 ]
				}
			   ],
	'style1': [
    {
        "featureType": "all",
        "elementType": "labels.text.fill",
        "stylers": [
            {
                "saturation": 36
            },
            {
                "color": "#333333"
            },
            {
                "lightness": 40
            }
        ]
    },
    {
        "featureType": "all",
        "elementType": "labels.text.stroke",
        "stylers": [
            {
                "visibility": "on"
            },
            {
                "color": "#ffffff"
            },
            {
                "lightness": 16
            }
        ]
    },
    {
        "featureType": "all",
        "elementType": "labels.icon",
        "stylers": [
            {
                "visibility": "off"
            }
        ]
    },
    {
        "featureType": "administrative",
        "elementType": "geometry.fill",
        "stylers": [
            {
                "color": "#e3e3e8"
            },
            {
                "lightness": "0"
            }
        ]
    },
    {
        "featureType": "administrative",
        "elementType": "geometry.stroke",
        "stylers": [
            {
                "color": "#fefefe"
            },
            {
                "lightness": 17
            },
            {
                "weight": 1.2
            }
        ]
    },
    {
        "featureType": "landscape",
        "elementType": "geometry",
        "stylers": [
            {
                "color": "#e3e3e8"
            },
            {
                "lightness": 20
            }
        ]
    },
    {
        "featureType": "poi",
        "elementType": "geometry",
        "stylers": [
            {
                "color": "#a2a2a7"
            },
            {
                "lightness": 21
            }
        ]
    },
    {
        "featureType": "poi",
        "elementType": "labels.text",
        "stylers": [
            {
                "hue": "#ff0000"
            }
        ]
    },
    {
        "featureType": "poi.park",
        "elementType": "geometry",
        "stylers": [
            {
                "color": "#d0d0d5"
            },
            {
                "lightness": 21
            }
        ]
    },
    {
        "featureType": "road.highway",
        "elementType": "geometry.fill",
        "stylers": [
            {
                "color": "#ffffff"
            },
            {
                "lightness": 17
            }
        ]
    },
    {
        "featureType": "road.highway",
        "elementType": "geometry.stroke",
        "stylers": [
            {
                "color": "#ffffff"
            },
            {
                "lightness": 29
            },
            {
                "weight": 0.2
            }
        ]
    },
    {
        "featureType": "road.arterial",
        "elementType": "geometry",
        "stylers": [
            {
                "color": "#ffffff"
            },
            {
                "lightness": 18
            }
        ]
    },
    {
        "featureType": "road.local",
        "elementType": "geometry",
        "stylers": [
            {
                "color": "#ffffff"
            },
            {
                "lightness": 16
            }
        ]
    },
    {
        "featureType": "transit",
        "elementType": "geometry",
        "stylers": [
            {
                "color": "#f2f2f2"
            },
            {
                "lightness": 19
            }
        ]
    },
    {
        "featureType": "water",
        "elementType": "geometry",
        "stylers": [
            {
                "color": "#e9e9e9"
            },
            {
                "lightness": 17
            }
        ]
    },
    {
        "featureType": "water",
        "elementType": "geometry.fill",
        "stylers": [
            {
                "color": "#cacacf"
            }
        ]
    }
],
	'style2': [
		 {
		  "featureType": "landscape",
		  "stylers": [
		   {
		    "hue": "#007FFF"
		   },
		   {
		    "saturation": 100
		   },
		   {
		    "lightness": 156
		   },
		   {
		    "gamma": 1
		   }
		  ]
		 },
		 {
		  "featureType": "road.highway",
		  "stylers": [
		   {
		    "hue": "#FF7000"
		   },
		   {
		    "saturation": -83.6
		   },
		   {
		    "lightness": 48.80000000000001
		   },
		   {
		    "gamma": 1
		   }
		  ]
		 },
		 {
		  "featureType": "road.arterial",
		  "stylers": [
		   {
		    "hue": "#FF7000"
		   },
		   {
		    "saturation": -81.08108108108107
		   },
		   {
		    "lightness": -6.8392156862745
		   },
		   {
		    "gamma": 1
		   }
		  ]
		 },
		 {
		  "featureType": "road.local",
		  "stylers": [
		   {
		    "hue": "#FF9A00"
		   },
		   {
		    "saturation": 7.692307692307736
		   },
		   {
		    "lightness": 21.411764705882348
		   },
		   {
		    "gamma": 1
		   }
		  ]
		 },
		 {
		  "featureType": "water",
		  "stylers": [
		   {
		    "hue": "#0093FF"
		   },
		   {
		    "saturation": 16.39999999999999
		   },
		   {
		    "lightness": -6.400000000000006
		   },
		   {
		    "gamma": 1
		   }
		  ]
		 },
		 {
		  "featureType": "poi",
		  "stylers": [
		   {
		    "hue": "#00FF60"
		   },
		   {
		    "saturation": 17
		   },
		   {
		    "lightness": 44.599999999999994
		   },
		   {
		    "gamma": 1
		   }
		  ]
		 }
	],
	'style3':  [
 {
  "featureType": "landscape",
  "stylers": [
   {
    "hue": "#FFA800"
   },
   {
    "saturation": 17.799999999999997
   },
   {
    "lightness": 152.20000000000002
   },
   {
    "gamma": 1
   }
  ]
 },
 {
  "featureType": "road.highway",
  "stylers": [
   {
    "hue": "#007FFF"
   },
   {
    "saturation": -77.41935483870967
   },
   {
    "lightness": 47.19999999999999
   },
   {
    "gamma": 1
   }
  ]
 },
 {
  "featureType": "road.arterial",
  "stylers": [
   {
    "hue": "#FBFF00"
   },
   {
    "saturation": -78
   },
   {
    "lightness": 39.19999999999999
   },
   {
    "gamma": 1
   }
  ]
 },
 {
  "featureType": "road.local",
  "stylers": [
   {
    "hue": "#00FFFD"
   },
   {
    "saturation": 0
   },
   {
    "lightness": 0
   },
   {
    "gamma": 1
   }
  ]
 },
 {
  "featureType": "water",
  "stylers": [
   {
    "hue": "#007FFF"
   },
   {
    "saturation": -77.41935483870967
   },
   {
    "lightness": -14.599999999999994
   },
   {
    "gamma": 1
   }
  ]
 },
 {
  "featureType": "poi",
  "stylers": [
   {
    "hue": "#007FFF"
   },
   {
    "saturation": -77.41935483870967
   },
   {
    "lightness": 42.79999999999998
   },
   {
    "gamma": 1
   }
  ]
 }
]
}
}