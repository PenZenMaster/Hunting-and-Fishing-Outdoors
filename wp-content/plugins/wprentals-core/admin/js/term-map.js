(function($){
    'use strict';

    function TermMap(){
        this.config = window.wprentalsTermMap || {};
        this.mapEl = document.getElementById('googleMap');
        this.mapType = parseInt(this.config.mapType || 1, 10);
        this.map = null;
        this.marker = null;
        this.geocoder = null;
        this.latField = $('#term_latitude');
        this.lngField = $('#term_longitude');
        this.zoomField = $('#page_custom_zoom');
        this.addressField = $('#term_address');
        this.zipField = $('#term_zip');
        this.countryField = $('#property_country');
        this.geoFailMessage = this.config.geoFailMessage || '';
        this.init();
    }

    TermMap.prototype.init = function(){
        if (!this.mapEl) {
            return;
        }

        var initialPosition = this.getInitialPosition();
        var initialZoom = this.getInitialZoom();

        if (this.mapType === 2 && typeof window.L !== 'undefined') {
            this.initializeLeaflet(initialPosition, initialZoom);
        } else if (typeof window.google !== 'undefined' && window.google.maps) {
            this.initializeGoogle(initialPosition, initialZoom);
        } else {
            return;
        }

        this.bindEvents();

        if (!this.latField.val()) {
            this.latField.val(initialPosition.lat);
        }

        if (!this.lngField.val()) {
            this.lngField.val(initialPosition.lng);
        }

        if (this.zoomField.length && !this.zoomField.val()) {
            this.zoomField.val(initialZoom);
        }
    };

    TermMap.prototype.getInitialPosition = function(){
        var lat = parseFloat(this.latField.val());
        var lng = parseFloat(this.lngField.val());

        if (isFinite(lat) && isFinite(lng)) {
            return { lat: lat, lng: lng };
        }

        lat = parseFloat(this.config.termLat);
        lng = parseFloat(this.config.termLng);

        if (isFinite(lat) && isFinite(lng)) {
            return { lat: lat, lng: lng };
        }

        lat = parseFloat(this.config.defaultLat);
        lng = parseFloat(this.config.defaultLng);

        if (!isFinite(lat)) {
            lat = 40.781711;
        }

        if (!isFinite(lng)) {
            lng = -73.955927;
        }

        return { lat: lat, lng: lng };
    };

    TermMap.prototype.getInitialZoom = function(){
        var zoom = parseInt(this.zoomField.val(), 10);

        if (isFinite(zoom)) {
            return zoom;
        }

        zoom = parseInt(this.config.termZoom, 10);

        if (isFinite(zoom)) {
            return zoom;
        }

        zoom = parseInt(this.config.defaultZoom, 10);

        if (!isFinite(zoom)) {
            zoom = 15;
        }

        return zoom;
    };

    TermMap.prototype.initializeGoogle = function(position, zoom){
        var self = this;
        this.mapType = 1;
        this.geocoder = new google.maps.Geocoder();
        this.map = new google.maps.Map(this.mapEl, {
            center: position,
            zoom: zoom,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            draggable: true,
            scrollwheel: true
        });

        this.marker = new google.maps.Marker({
            position: position,
            map: this.map,
            draggable: true
        });

        google.maps.event.addListener(this.map, 'click', function(event){
            self.setPosition(event.latLng.lat(), event.latLng.lng(), true);
        });

        google.maps.event.addListener(this.marker, 'dragend', function(event){
            self.setPosition(event.latLng.lat(), event.latLng.lng(), false);
        });
    };

    TermMap.prototype.initializeLeaflet = function(position, zoom){
        var self = this;
        this.mapType = 2;

        if (window.L && window.L.DomUtil) {
            var container = window.L.DomUtil.get(this.mapEl);
            if (container && container._leaflet_id) {
                container._leaflet_id = null;
            }
        }

        this.map = window.L.map(this.mapEl, {
            center: [position.lat, position.lng],
            zoom: zoom,
            scrollWheelZoom: true
        });

        var tileUrl;
        var attribution;

        if (this.config.mapboxKey) {
            tileUrl = 'https://api.mapbox.com/styles/v1/mapbox/streets-v11/tiles/{z}/{x}/{y}?access_token=' + this.config.mapboxKey;
            attribution = '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://www.mapbox.com/">Mapbox</a>';
        } else {
            tileUrl = 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
            attribution = '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors';
        }

        window.L.tileLayer(tileUrl, {
            attribution: attribution,
            maxZoom: 20
        }).addTo(this.map);

        this.marker = window.L.marker([position.lat, position.lng], {
            draggable: true
        }).addTo(this.map);

        this.marker.on('dragend', function(event){
            var newPos = event.target.getLatLng();
            self.setPosition(newPos.lat, newPos.lng, false);
        });

        this.map.on('click', function(event){
            self.setPosition(event.latlng.lat, event.latlng.lng, true);
        });
    };

    TermMap.prototype.bindEvents = function(){
        var self = this;

        $('#admin_place_pin').on('click', function(event){
            event.preventDefault();
            self.geocodeAddress();
        });

        this.zoomField.on('change', function(){
            var zoom = parseInt($(this).val(), 10);

            if (!isFinite(zoom) || !self.map) {
                return;
            }

            if (self.mapType === 2 && self.map.setZoom) {
                self.map.setZoom(zoom);
            } else if (self.map.setZoom) {
                self.map.setZoom(zoom);
            }
        });
    };

    TermMap.prototype.setPosition = function(lat, lng, updateZoom){
        if (!isFinite(lat) || !isFinite(lng)) {
            return;
        }

        if (this.mapType === 2 && this.marker) {
            this.marker.setLatLng([lat, lng]);
            this.map.panTo([lat, lng]);
        } else if (this.marker) {
            this.marker.setPosition({ lat: lat, lng: lng });
            this.map.panTo({ lat: lat, lng: lng });
        }

        this.latField.val(lat);
        this.lngField.val(lng);

        if (updateZoom && this.zoomField.length && this.map) {
            if (this.mapType === 2 && this.map.getZoom) {
                this.zoomField.val(this.map.getZoom());
            } else if (this.map.getZoom) {
                this.zoomField.val(this.map.getZoom());
            }
        }
    };

    TermMap.prototype.geocodeAddress = function(){
        var parts = [];
        var address = this.addressField.val();
        var zip = this.zipField.val();
        var country = this.countryField.val();
        var termName = $('#tag-name').val() || $('#name').val();

        if (address) {
            parts.push(address);
        }

        if (zip) {
            parts.push(zip);
        }

        if (termName) {
            parts.push(termName);
        }

        if (country) {
            parts.push(country);
        }

        if (!parts.length) {
            return;
        }

        var query = parts.join(', ');
        var self = this;

        if (this.mapType === 2) {
            var url = 'https://nominatim.openstreetmap.org/search?format=json&limit=1&q=' + encodeURIComponent(query);

            window.fetch(url, {
                headers: {
                    'Accept': 'application/json'
                }
            })
                .then(function(response){
                    return response.json();
                })
                .then(function(data){
                    if (data && data.length) {
                        var location = data[0];
                        self.setPosition(parseFloat(location.lat), parseFloat(location.lon), true);
                    } else if (self.geoFailMessage) {
                        window.alert(self.geoFailMessage);
                    }
                })
                .catch(function(){
                    if (self.geoFailMessage) {
                        window.alert(self.geoFailMessage);
                    }
                });
        } else if (this.geocoder) {
            this.geocoder.geocode({ address: query }, function(results, status){
                if (status === 'OK' && results[0]) {
                    var geometry = results[0].geometry;
                    self.setPosition(geometry.location.lat(), geometry.location.lng(), true);

                    if (geometry.viewport && self.map && self.map.fitBounds) {
                        self.map.fitBounds(geometry.viewport);
                    }
                } else if (self.geoFailMessage) {
                    window.alert(self.geoFailMessage + ' ' + status);
                }
            });
        }
    };

    $(function(){
        new TermMap();
    });
})(jQuery);
