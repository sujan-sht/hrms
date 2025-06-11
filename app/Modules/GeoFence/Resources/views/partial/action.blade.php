<style>
    .map {
        width: auto;
        position: fixed;
        overflow: hidden;
        height: 250px;
    }

    #pac-input {
        width: 25%;
        height: 35px;
        top: 13px !important;
        position: absolute;
        left: 188px;
    }
</style>
<div class="card">
    <div class="card-body">
        <div class="col-md-6 mb-3">
            <span class="btn btn-outline-warning mx-1 addMore"><i class="icon-plus-circle2"></i>&nbsp;&nbsp;ADD</span>
        </div>
    </div>
</div>

<div class="card clone-div">
    <div class="card-body">
        <legend class="text-uppercase font-size-sm font-weight-bold">GeoFence Location</legend>
        <div class="row abc">
            <input id="pac-input" class="controls mb-1" type="text" placeholder="Search Location"
                name="location_name">
            <div id="map" class="col-md-7 map"></div>
            <div class="col-md-5">
                <div class="row mb-1">
                    <label class="col-form-label col-lg-2">Title:</label>
                    <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                        <div class="input-group">
                            {!! Form::text('multi[0][title]', null, [
                                'id' => 'title',
                                'placeholder' => 'Enter Title',
                                'class' => 'form-control',
                            ]) !!}
                        </div>
                        @if ($errors->has('title'))
                            <span class="text-danger">{{ $errors->first('title') }}</span>
                        @endif
                    </div>
                </div>
                <div class="row mb-1">
                    <label class="col-form-label col-lg-2">Latitude: <span class="text-danger">*</span></label>
                    <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                        <div class="input-group">
                            {!! Form::text('multi[0][latitude]', null, [
                                'id' => 'latitude',
                                'placeholder' => 'Enter Latitude',
                                'class' => 'form-control',
                            ]) !!}
                        </div>
                        @if ($errors->has('latitude'))
                            <span class="text-danger">{{ $errors->first('latitude') }}</span>
                        @endif
                    </div>
                </div>
                <div class="row mb-1">
                    <label class="col-form-label col-lg-2">Longitude: <span class="text-danger">*</span></label>
                    <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                        <div class="input-group">
                            {!! Form::text('multi[0][longitude]', null, [
                                'id' => 'longitude',
                                'placeholder' => 'Enter Longitude',
                                'class' => 'form-control',
                            ]) !!}
                        </div>
                        @if ($errors->has('longitude'))
                            <span class="text-danger">{{ $errors->first('longitude') }}</span>
                        @endif
                    </div>
                </div>
                <div class="row mb-1">
                    <label class="col-form-label col-lg-2">Radius(meter): <span class="text-danger">*</span></label>
                    <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                        <div class="input-group">
                            {!! Form::text('multi[0][radius]', null, [
                                'id' => 'radius',
                                'placeholder' => 'Enter Radius',
                                'class' => 'form-control',
                            ]) !!}
                        </div>
                        @if ($errors->has('radius'))
                            <span class="text-danger">{{ $errors->first('radius') }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="append-clone"></div>


<div class="text-center">
    <button type="submit" class="btn btn-success btn-labeled btn-labeled-left"><b><i
                class="icon-database-insert"></i></b>{{ $btnType }}</button>
</div>

<script src="https://maps.google.com/maps/api/js?key=AIzaSyArU1pgr2LV8HdlPV0vfuzF-U8wqDBiYPM&libraries=places"
    type="text/javascript"></script>
<script>
    $('.select-search1').select2();

    $(document).ready(function() {
        var latlng = new google.maps.LatLng('27.65584728817464', '85.36826186631829');
        var map = new google.maps.Map(document.getElementById('map'), {
            center: latlng,
            zoom: 11,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            streetViewControl: false,
        });

        var input = document.getElementById('pac-input');
        var searchBox = new google.maps.places.SearchBox(input);
        map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

        // Bias the SearchBox results towards current map's viewport.
        map.addListener('bounds_changed', function() {
            searchBox.setBounds(map.getBounds());
        });

        var marker = new google.maps.Marker({
            position: latlng,
            map: map,
            title: 'Set lat/lon values for this property',
            draggable: true
        });

        searchBox.addListener('places_changed', function() {
            var places = searchBox.getPlaces();

            if (places.length === 0) {
                return;
            }

            // For each place, get the icon, name and location.
            var bounds = new google.maps.LatLngBounds();
            places.forEach(function(place) {
                if (!place.geometry) {
                    // console.log("Returned place contains no geometry");
                    return;
                }

                marker.setPosition(new google.maps.LatLng(place.geometry.location.lat(), place
                    .geometry.location.lng()));
                marker.setTitle(place.name);
                marker.setMap(map);


                if (place.geometry.viewport) {
                    bounds.union(place.geometry.viewport);
                } else {
                    bounds.extend(place.geometry.location);
                }
                $('#latitude').val(place.geometry.location.lat());
                $('#longitude').val(place.geometry.location.lng());
            });
            map.fitBounds(bounds);
        });

        google.maps.event.addListener(marker, 'dragend', function(event, element) {
            lat = this.getPosition().lat();
            long = this.getPosition().lng();

            $('#title').val($('#pac-input').val());
            $('#latitude').val(lat);
            $('#longitude').val(long);
        });

        google.maps.event.addListener(map, 'click', function(event) {
            placeMarker(event.latLng);
        });

        function placeMarker(location) {
            if (marker == undefined) {
                marker = new google.maps.Marker({
                    position: location,
                    map: map,
                    animation: google.maps.Animation.DROP,
                });
            } else {
                marker.setPosition(location);

                $('#latitude').val(location.lat());
                $('#longitude').val(location.lng());
            }

        }
    });

    $('.addMore').on('click', function() {
        length = $('.clone-div').length + 1;
        var clone = $('.clone-div:first');
        var appendClone = clone.clone(true, true).appendTo(".append-clone");
        // $('#geofence_submit').validate();
        mapid = $(".clone-div:last").find('.map').attr('id') + '-' + length;

        appendClone.find('.map').attr('id', mapid);

        $(".clone-div:last").find(":input").each(function(index) {
            name = $(this).attr('name')
            replace = name.replace(/0/g, length);
            rep_name = $(this).attr('name', replace);
            id = $(this).attr('id') + '-' + length;
            $(this).attr('id', id);
        })
        loadMap(length);

        ary = [
            'latitude-' + length,
            'longitude-' + length,
            'radius-' + length,
        ];
        addRules(ary);
        appendClone.find(".card-body").prepend(
            '<button type="button" class="btn btn-outline-danger mx-1 btn-remove float-right" ><i class="icon-trash"></i>&nbsp;&nbsp;Remove</button>'
        );
        appendClone.find(':input').val('');
    })

    $(document).on('click', '.btn-remove', function() {
        var parent = $(this).parent().parent();
        parent.remove();
    })

    function loadMap(length) {
        var latlng = new google.maps.LatLng('27.65584728817464', '85.36826186631829');
        var map = new google.maps.Map(document.getElementById('map' + '-' + length), {
            center: latlng,
            zoom: 11,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            streetViewControl: false,
        });

        var input = document.getElementById('pac-input');
        var searchBox = new google.maps.places.SearchBox(input);
        map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

        // Bias the SearchBox results towards current map's viewport.
        map.addListener('bounds_changed', function() {
            searchBox.setBounds(map.getBounds());
        });

        var marker = new google.maps.Marker({
            position: latlng,
            map: map,
            title: 'Set lat/lon values for this property',
            draggable: true
        });

        searchBox.addListener('places_changed', function() {
            var places = searchBox.getPlaces();

            if (places.length === 0) {
                return;
            }

            // For each place, get the icon, name and location.
            var bounds = new google.maps.LatLngBounds();
            places.forEach(function(place) {
                if (!place.geometry) {
                    // console.log("Returned place contains no geometry");
                    return;
                }

                marker.setPosition(new google.maps.LatLng(place.geometry.location.lat(), place
                    .geometry.location.lng()));
                marker.setTitle(place.name);
                marker.setMap(map);


                if (place.geometry.viewport) {
                    bounds.union(place.geometry.viewport);
                } else {
                    bounds.extend(place.geometry.location);
                }
                $('#latitude' + '-' + length).val(place.geometry.location.lat());
                $('#longitude' + '-' + length).val(place.geometry.location.lng());
            });
            map.fitBounds(bounds);
        });


        google.maps.event.addListener(marker, 'dragend', function(event, element) {
            lat = this.getPosition().lat();
            long = this.getPosition().lng();

            $('#latitude' + '-' + length).val(lat);
            $('#longitude' + '-' + length).val(long);
        });

        google.maps.event.addListener(map, 'click', function(event) {
            placeMarker(event.latLng);
        });

        function placeMarker(location) {
            if (marker == undefined) {
                marker = new google.maps.Marker({
                    position: location,
                    map: map,
                    animation: google.maps.Animation.DROP,
                });
            } else {
                marker.setPosition(location);

                $('#latitude' + '-' + length).val(location.lat());
                $('#longitude' + '-' + length).val(location.lng());
            }

        }
    }

    function addRules(array) {
        array.forEach(function(key) {
            label = $("#" + key).closest('.row').find('.col-form-label').text();
            $('#geofence_submit #' + key).rules("add", {
                required: true,
                messages: {
                    required: 'Enter ' + label.replace(":", " ")
                }
            });
        })

    }
</script>
