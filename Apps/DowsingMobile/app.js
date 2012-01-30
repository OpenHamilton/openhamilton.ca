// Copyright 2011 OpenHamilton.  All Rights Reserved.

/**
 * @fileoverview Mobile version of Dowsing that relies on zepto.js, backbone.js and handlebars.js
 * Uses hash-bangs to track application state. 
 * @author gavin.schulz@gmail.com (Gavin Schulz)
 */

/**
 * Register some helpers that are used in templating
 */
$(document).ready(function() {
	Handlebars.registerPartial('header', $("#header-template").html());

	/**
	 * Returns a properly decimalled cost
	 * @param {number} cost The cost number to format
	 * @return {number} The cost with 2 decimal places gauranteed
	 */
	Handlebars.registerHelper('showCost', function(cost) {
		return cost.toFixed(2);
	});

	/**
	 * Returns the html for showing a static google map
	 * @param {object} context The handlebars context that this helper is called from
	 * @return {string} The html need for showing the static map
	 */
	Handlebars.registerHelper('staticMap', function(context) {
		return '<img src="http://maps.googleapis.com/maps/api/staticmap?center=' + 
			   context.row.Lat + ',' + context.row.Long + 
			   '&zoom=15&size=283x240&maptype=roadmap&sensor=false&markers=color:red%7C' +
			   context.row.Lat + ',' + context.row.Long + '" class="map" />';
	});
});

/* Main app object */
var Dowsing = {
	/**
	 * ID of the Google Fusion Table that stores the swimming data
	 * @type {number}
	 */
	fusionTableId: 1203335,
	/**
	 * The google fusion table api url
	 * @type {string}
	 */
	fusionTableAPIUrl: 'https://www.google.com/fusiontables/api/query?sql=',
	/** 
	 * Stores the last query we did to determine if back to results link should be displayed 
	 * @type {string}
	 */
	lastQuery: '',

	/**
	 * Keeps track of which page of the search results we're on
	 * @type {number}
	 */
	page: 1,

	/**
	 * Number of results to return from Google Fusion Table query
	 * @type {number}
	 */
	results: 5,

	/**
	 * Backbone.js application objects storage
	 */
	Views: {},
	Routers: {},
	Collections: {},

	init: function() {
		this.router = new Dowsing.Routers.Spots();
		Backbone.history.start();
	}
};

// Create a new model
var Spot = Backbone.Model.extend();

// Creates a collection of `Spot`s
var Spots = Backbone.Collection.extend({ model: Spot });

/* Application routing object */
Dowsing.Routers.Spots = Backbone.Router.extend({
	_spots: null,

	/**
	 * Defines all the possibles routes for the app.
	 */
	routes : {
		""                      : "index",
		"home"                  : "index",
		"info"                  : "info",
		"search/:address"       : "search",
		"search/:address/:page" : "search",
		"display/:tag"          : "display"
	},

	/**
	 * Binds functions that are called by other parts of the application.
	 */
	initialize: function() {
		_.extend(this, Backbone.Events);
		_.bindAll(this, "search");
		_.bindAll(this, "processData");
		_.bindAll(this, "processDetails");
	},

	index: function() {
		this.navigate("home");
		var indexView  = new Dowsing.Views.Index();
		// Makes a call to the search function accessible from the view
		indexView.bind("index_view:search", this.search);
		indexView.render();
	},

	/**
	 * Shows the info pane when the info icon is clicked
	 */
	info: function() {
		this.navigate("info");
		var infoView  = new Dowsing.Views.Info();
		infoView.render();
	},

	/**
	 * Makes a call to the Google Fusion Table API to make a search request.
	 * @param {string} address The address for which to find close-by water spots for.
	 * @param {number} page The page of the results that is being requested.
	 */
	search: function(address, page) {
		if (page == undefined) { page = 1; }

		this.navigate("search/" + address + "/" + page);

		// Record this as the last query we did
		Dowsing.lastQuery = address;
		Dowsing.page = page;

		// Show the loading gif
		$("#content").append("<div class=\"loading\"></div>");

		var self = this;
		var geocoder = new google.maps.Geocoder();
		geocoder.geocode({ address: decodeURIComponent(address) }, function(results, status) {
			if (status == google.maps.GeocoderStatus.OK) {
				$.getJSON(self.constructQueryURL(results[0].geometry.location, page) + "&jsonCallback=?", self.processData);
			} else {
				// Render error
				self.navigate("home", true);
			}
		});
	},

	/**
	 * Constructs the query url for returning the neccesary data
	 * Query: SELECT * FROM {Dowsing.FusionTableId} ORDER BY 
	 *        ST_DISTANCE(Lat, LATLNG({location.lat()},{location.lng()}))
	 *        OFFSET {start} LIMIT {Dowsing.Results};
	 * @param {google.maps.LatLng} location Location as returned by the google geocoder.
	 * @param {number} start The row from which to start returning results
	 * @return {string} Returns the full url that needs to be loaded to perform the query
	 */
	constructQueryURL: function(location, start) {	
		var url = Dowsing.fusionTableAPIUrl;
		url    += "SELECT+%2A+FROM+" + Dowsing.fusionTableId;
		url    += "+ORDER+BY+ST_DISTANCE%28Lat%2CLATLNG%28";
		url    += encodeURIComponent(location.lat()) + "%2C" + encodeURIComponent(location.lng()) + "%29%29";
		url    += "+OFFSET+" + (start - 1) * Dowsing.results + "+LIMIT+" + Dowsing.results;
		return url;
	},

	/**
	 * Constructs the query url for returning data for a specific water spot
	 * Query: SELECT * FROM {Dowsing.FusionTableId} WHERE 
	 *        Lat = {{tag[0]}} AND Long = {{tag[1]}}
	 * @param {string} tag A string containing the latitude and longitude of the water spot
	 * joined via an underscore (_)
	 * @return {string} Returns the full url that needs to be loaded to perform the query
	 */
	constructDisplayURL: function(tag) {
		tag = decodeURIComponent(tag).split("_");
		var url = Dowsing.fusionTableAPIUrl;
		url    += "SELECT+%2A+FROM+" + Dowsing.fusionTableId;
		url    += "+WHERE+Lat%3D" + encodeURIComponent(tag[0]) + "+AND+Long%3D" + encodeURIComponent(tag[1]);
		return url;
	},

	/** 
	 * Processes the results returned by a search call
	 * Creates the Spots collection using the returned data
	 * Finally, renders the results view
	 * @param {object} results The resulting JSON returned from the Google Fusion Table query
	 */
	processData: function(results) {
		var _Spots = [];
		for (var i = 0, row; row = results.table.rows[i]; i++) {
			var _row = {};
			for (index in results.table.cols) {
				/* Only get the fields we really need for the result display */
				if (results.table.cols[index] == "Icon" || 
					results.table.cols[index] == "Lat" || 
					results.table.cols[index] == "Long" || 
					results.table.cols[index] == "Facility Name" || 
					results.table.cols[index] == "Address" || 
					results.table.cols[index] == "City" ||
					results.table.cols[index] == "Type") {
					// Add to row information
					_row[results.table.cols[index].split(' ').join('_')] = row[index];
				}
			}
			_Spots.push(new Spot(_row));
		}
		this.spots = new Spots(_Spots);
		var resultsView = new Dowsing.Views.Results({collection: this.spots});
		resultsView.render();
	},

	/**
	 * Performs the call to display a single water spot
	 * @param {string} tag The tag is in the format {{lat}}_{{long}}
	 */
	display: function(tag) {
		this.navigate("display/" + tag);
		if (Dowsing.lastQuery != '') {
			$("#content").append("<div class=\"loading\"></div>");
		}

		$.getJSON(this.constructDisplayURL(tag)+"&jsonCallback=?", this.processDetails);
	},

	/**
	 * Process the results of a display query and renders a single water spot display
	 * @param {object} results The resulting JSON returned by the Google Fusion Table API
	 */
	processDetails: function(results) {
		row = results.table.rows[0];
		var _row = {};
		for (index in results.table.cols) {
			// Don't copy the info window html that is also stored in the tables
			if (results.table.cols[index].indexOf("Window") == -1) {
				// Add to row information
				_row[results.table.cols[index].split(' ').join('_')] = row[index];
			}
		}
		var detailsView = new Dowsing.Views.Details({model: new Spot(_row)});
		detailsView.render();
	}
});

/* Backbone view for the index page */
Dowsing.Views.Index = Backbone.View.extend({
	el: $("#content"),

	events: {
		"click #home": "home",
		"click #info": "info",
		"submit form": "search"
	},

	initialize: function() {
		_.extend(this, Backbone.Events);
		_.bindAll(this, 'render');
		this.render();
		return this;
	},

	search: function(e) {
		e.preventDefault();
		this.trigger("index_view:search", encodeURIComponent($("#address").val()));
		return false;
	},

	info: function(e) {
		e.preventDefault();
		Dowsing.router.navigate("info", true);
		return false;
	},

	home: function(e) {
		e.preventDefault();
		Dowsing.router.navigate("home", true);
		return false;
	},

	render: function() {
		var homeTemplate = Handlebars.compile($("#home-template").html());
		this.el.html(homeTemplate({title: "Dowsing"}));
		window.scrollTo(0, 1);
	}
});


// Backbone View object for the info page
Dowsing.Views.Info = Backbone.View.extend({
	el: $("#content"),

	events: {
		"click #home": "home",
		"click #info": "info"
	},

	initialize: function() {
		_.bindAll(this, 'render');
		this.render();
		return this;
	},

	home: function(e) {
		e.preventDefault();
		Dowsing.router.navigate("home", true);
		return false;
	},

	info: function(e) {
		e.preventDefault();
		return false;
	},

	render: function() {
		var homeTemplate = Handlebars.compile($("#info-template").html());
		this.el.html(homeTemplate({title: "About Dowsing"}));
		window.scrollTo(0, 1);
	}
});

// Backbone view for displaying the results
Dowsing.Views.Results = Backbone.View.extend({
	el : $("#content"),

	events : {
		"click #home"  : "home",
		"click #info"  : "info",
		"click .panel" : "showDetail",
		"click .prev"  : "prev",
		"click .next"  : "next"
	},

	initialize : function() {
		_.bindAll(this, 'render');
		this.render();
		return this;
	},

	showDetail : function(e) {
		e.preventDefault();
		Dowsing.router.navigate("display/" + $(e.currentTarget).attr("tag"), true);
		return false;
	},

	home : function(e) {
		e.preventDefault();
		Dowsing.router.navigate("home", true);
		return false;
	},

	info : function(e) {
		e.preventDefault();
		Dowsing.router.navigate("info", true);
		return false;
	},

	prev : function(e) {
		$(".nav_btns").empty();
		e.preventDefault();
		$(".prev").css("display", "none");
		Dowsing.router.navigate("search/" + Dowsing.lastQuery + "/" + (parseInt(Dowsing.page) - 1), true);
		return false;
	},

	next : function(e) {
		$(".nav_btns").empty();
		e.preventDefault();
		$(".next").css("display", "none");
		Dowsing.router.navigate("search/" + Dowsing.lastQuery + "/" + (parseInt(Dowsing.page) + 1), true);
		return false;
	},

	render : function() {
		var resultTemplate = Handlebars.compile($("#result-template").html());
		this.el.html(resultTemplate({
			prev: (Dowsing.page > 1) ? 1 : null,
			next: (this.collection.length >= Dowsing.results) ? 1 : null,
			title: "Search Results",
			results: this.collection.toJSON()
		}));
		window.scrollTo(0, 1);
	}
});

/* Backbone view for the details page */
Dowsing.Views.Details = Backbone.View.extend({
	el : $("#content"),

	events : {
		"click #home": "home",
		"click #info": "info",
		"click .back": "back"
	},

	initialize : function() {
		_.bindAll(this, 'render');
		this.render();
		return this;
	},

	back : function(e) {
		e.preventDefault();
		Dowsing.router.navigate("search/" + Dowsing.lastQuery + "/" + Dowsing.page, true);
		return false;
	},

	home : function(e) {
		e.preventDefault();
		Dowsing.router.navigate("home", true);
		return false;
	},

	info : function(e) {
		e.preventDefault();
		Dowsing.router.navigate("info", true);
		return false;
	},

	render : function() {
		var self = this;
		var detailTemplate = Handlebars.compile($("#detail-template").html());

		var content = detailTemplate({
			title: "Details",
			canGoBack : (Dowsing.lastQuery == '') ? "none" : "block",
			row : this.model.toJSON()
		});
		self.el.html(content);
		window.scrollTo(0, 1);
	}
});