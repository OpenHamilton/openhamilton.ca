// Copyright 2011 OpenHamilton.  All Rights Reserved.

/**
 * @fileoverview Desktop widget implementation of Dowsing that
 * can be embedded into any modern browser.  The data is served
 * from a series of Google Fusion Tables
 * @author gavin.schulz@gmail.com (Gavin Schulz)
 */


var Dowsing = {
	/**
	 * The base url we're serving the widget from 
	 * @type {string}
	 */
	baseUrl: 'http://openhamilton.ca/dowsing/desktop/',

	/**
	 * Google Fusion Table Map Layers
	 * @type {google.maps.FusionTablesLayer}
	 */
	layer_1: null,
	layer_2: null,
	layer_3: null,
	layer_4: null,
	layer_5: null,

	/**
	 * IDs of the Google Fusion Tables that store the required data
	 * @type {number}
	 */
	tableid_1: 1170238,
	tableid_2: 1156706,
	tableid_3: 1171176,
	tableid_4: 1171298,
	tableid_5: 1171364,

	/**
	 * Location to initially center map on 
	 * @type {google.maps.LatLng}
	 */
	_center: null,

	/**
	 * Zoom level to start map on
	 * @type {number}
	 */
	zoom: 11,

	/**
	 * Geocoding object instance
	 * @type {google.maps.Geocoder}
	 */
	geocoder: null,

	/**
	 * The Google Map object instance
	 * @type {google.maps.Map}
	 */
	map: null,

	/**
	 * HTML element that is holding the {Dowsing.map}
	 * @type {HTMLelement}
	 */
	map_canvas: null,

	/**
	 */
	info_window: null,

	/**
	 * Height of the Dowsing widget
	 * @type {number}
	 */
	height: 0,

	/**
	 * Width of the Dowsing widget
	 * @type {number}
	 */
	width: 0,

	/**
	 * HTML element that the Dowsing widget is being inserted into
	 * @type {HTMLelement}
	 */
	elem: null,

	/**
	 * Lat/Lng co-ordinates to center the map on when initializing
	 * @type {object}
	 */
	center: {
		lat: 43.24895389686911, 
		lng: -79.86236572265625
	},
};

/**
 * The main entry point of Dowsing when embedded
 * Loads the google maps API and our custom stylesheet
 */
Dowsing.display = function() {
	this.elem = document.getElementById('dowsing_canvas');

	// Ensures the element exists in the DOM, otherwise try again in a second
	if ( this.elem == null ) {
		setTimeout("Dowsing.display()", 1000);
		return;
	}

	var script  = document.createElement("script");
	script.type = "text/javascript";
	script.src  = "http://maps.google.com/maps/api/js?sensor=false&callback=Dowsing.show";
	document.body.appendChild(script);

	var css = document.createElement("link");
	css.setAttribute("rel", "stylesheet");
	css.setAttribute("type", "text/css");
	css.setAttribute("media", "all");
	css.setAttribute("href", this.baseUrl + "style.css");
	document.body.appendChild(css);
};

/**
 * Clears placeholder text on focus.
 * @param {object} i A reference to the input that was focussed.
 */
Dowsing.addressFocus = function(i) {
	if ( i.value == 'Enter an address...' ) {
		i.value = '';
	}
};

/**
 * Restores placeholder if input is empty.
 * @param {object} i A reference to the input that was blurred.
 */
Dowsing.addressBlur = function(i) {
	if ( i.value == '' ) {
		i.value = 'Enter an address...';
	}
};

/**
 * Creates the header toolbar and appends it to the widget
 */
Dowsing.header = function() {
	var header = document.createElement('div');
	header.id = 'dowsing_header';
	header.className = 'grad_box';
	header.innerHTML = '<input type="text" id="dowsing_address" class="text_input" value="Enter an address..."' +
					   ' onfocus="javascript:Dowsing.addressFocus(this);" onblur="javascript:Dowsing.addressBlur(this);"/>' +
					   '<input type="submit" value="Search" id="dowsing_search" class="button input" onclick="javascript:Dowsing.zoomToAddress();"/>' + 
					   '<input type="submit" value="Reset" class="button input" style="float:right !important;margin-right: 8px;" id="dowsing_reset"' +
					   ' onclick="javascript:Dowsing.reset()"/>';

	this.elem.appendChild(header);
};

/**
 * Creates the bottom legend toolbar and appends it to the widget
 */
Dowsing.legend = function() {
	var legend       = document.createElement('div');
	legend.id        = 'dowsing_legend';
	legend.className = 'grad_box';

	var contents = '<ul id="dowsing_list">';
	contents    += '<li><img src="' + this.baseUrl + 'sm_red.png" class="dowsing_image"/>Beach</li>';
	contents    += '<li><img src="' + this.baseUrl + 'sm_pink.png" class="dowsing_image"/>Outdoor Pool</li>';
	contents    += '<li><img src="' + this.baseUrl + 'sm_yellow.png" class="dowsing_image"/>Indoor Pool</li>';
	contents    += '<li><img src="' + this.baseUrl + 'sm_purple.png" class="dowsing_image"/>Splash Pad</li>';
	contents    += '<li><img src="' + this.baseUrl + 'sm_green.png" class="dowsing_image"/>Wading Pool</li>';
	contents    += '</ul><div class="clearfix"></div>';

	legend.innerHTML = contents;
	this.elem.appendChild(legend);
};

/**
 * Initializes some class variables and then draws the 
 * widget into the specified div
 */
Dowsing.show = function() {
	/* Center our map on Hamilton */
	this._center = new google.maps.LatLng(this.center.lat, this.center.lng);

	// Creates a google geocoder and keeps a reference for later
	this.geocoder = new google.maps.Geocoder();

	this.config(DowsingConfig);

	this.header();

	this.map_canvas = document.createElement('div');
	this.map_canvas.id = 'dowsing_map_canvas';

	// 44px = the height of the header
	// 28px = the height of the legend
	this.map_canvas.style.height = (this.height - 44 - 28) + "px";
	this.map_canvas.style.width  = this.width + "px";
	this.elem.appendChild(this.map_canvas);

	this.legend();	

	// Draw a new google map
	this.map = new google.maps.Map(this.map_canvas, {
		center    : this._center,
		zoom      : this.zoom,
		mapTypeId : google.maps.MapTypeId.ROADMAP
	});
	
	// Styling information for Fusion Table Layers
	var style = [{
		featureType : 'all',
		elementType : 'all',
		stylers     : [{
			saturation : -57
		}]
	}];

	this.info_window = new google.maps.InfoWindow(); 

	// Add each fusion table as a new layer on the map
  	this.layer_1 = new google.maps.FusionTablesLayer({
  		query : {
  			select : 'Lat',
  			from   : this.tableid_1
  		},
  		map : this.map,
  		suppressInfoWindows : true
  	});
  	 	
  	this.layer_2 = new google.maps.FusionTablesLayer({
  		query : {
  			select : 'Lat',
  			from   : this.tableid_2
  		},
  		map : this.map,
  		suppressInfoWindows : true
  	});

  	this.layer_3 = new google.maps.FusionTablesLayer({
  		query : {
  			select : 'Lat',
  			from   : this.tableid_3
  		},
  		map : this.map,
  		suppressInfoWindows : true
  	});

  	this.layer_4 = new google.maps.FusionTablesLayer({
  		query : {
  			select : 'Lat',
  			from   : this.tableid_4
  		},
  		map : this.map,
  		suppressInfoWindows : true
  	});

  	Dowsing.layer_5 = new google.maps.FusionTablesLayer({
  		query : {
  			select : 'Lat',
  			from   : Dowsing.tableid_5
  		},
  		map : Dowsing.map,
  		suppressInfoWindows : true
  	});

  	// Add the click handlers to the map
  	google.maps.event.addListener(this.layer_1, 'click', this.windowControl);
  	google.maps.event.addListener(this.layer_2, 'click', this.windowControl);
  	google.maps.event.addListener(this.layer_3, 'click', this.windowControl);
  	google.maps.event.addListener(this.layer_4, 'click', this.windowControl);
  	google.maps.event.addListener(this.layer_5, 'click', this.windowControl);
};

/**
 * Defines the handler for display the info 
 * window pop-ups when the user clicks on a point
 * @param {event} event An event created by clicking on an icon on the google map
 */
Dowsing.windowControl = function(event) {
	Dowsing.info_window.setOptions({
		content     : event.infoWindowHtml,
		position    : event.latLng,
		pixelOffset : event.pixelOffset
	});
	Dowsing.info_window.open(Dowsing.map);
};

/**
 * Called when a user searches their address
 * Makes a geocode call and centers map on location
 * and increases zoom level
 */
Dowsing.zoomToAddress = function() {
	var self = this;
	/* Use the geocoder to geocode the address */
	this.geocoder.geocode({ 'address' : document.getElementById("dowsing_address").value }, function(results, status) {
		/* If the status of the geocode is OK */
		if ( status == google.maps.GeocoderStatus.OK ) {
			/* Change the center and zoom of the map */
			self.map.setCenter(results[0].geometry.location);
			self.map.setZoom(14);
		}
	});
};

/**
 * Reset the zoom & center values
 */
Dowsing.reset = function() {
	this.map.setCenter(this._center);
	this.map.setZoom(this.zoom);
	document.getElementById('dowsing_address').value = 'Enter an address...';
};

/**
 * Gets the configuration options and
 * parse them to setup up the canvas
 * @param {object} options A Dowsing config object containing any special configured value
 */
Dowsing.config = function( options ) {
	/* Check what configuration options were defined */
	this.height = (!options.height || (options.height < 400) ) ? 400 : options.height;
	this.width  = (!options.width  || (options.width  < 500) ) ? 500 : options.width;

	this.elem.style.height = this.height + "px";
	this.elem.style.width  = this.width  + "px";
};

// Add the widget canvas to the page
document.write(unescape("%3Cdiv id='dowsing_canvas' style='height:0px;width:0px;'%3E%3C/div%3E"));

// Display the widget
Dowsing.display();