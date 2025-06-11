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
                            {!! Form::text('title', $geoFence->title, [
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
                    <label class="col-form-label col-lg-2">Latitude:</label>
                    <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                        <div class="input-group">
                            {!! Form::text('latitude', $geoFence->latitude, [
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
                    <label class="col-form-label col-lg-2">Longitude:</label>
                    <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                        <div class="input-group">
                            {!! Form::text('longitude', $geoFence->longitude, [
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
                    <label class="col-form-label col-lg-2">Radius(meter):</label>
                    <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                        <div class="input-group">
                            {!! Form::text('radius', $geoFence->radius, [
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

<div class="text-center">
    <button type="submit" class="btn btn-success btn-labeled btn-labeled-left"><b><i class="icon-database-insert"></i></b>{{ $btnType }}</button>
</div>

<script src="https://maps.google.com/maps/api/js?key=AIzaSyArU1pgr2LV8HdlPV0vfuzF-U8wqDBiYPM&libraries=places" type="text/javascript">
</script>
<script>
    $('.select-search1').select2();

    $(document).ready(function() {
        lat = $('#latitude').val();
        long = $('#longitude').val();
        var latlng = new google.maps.LatLng(lat, long);
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
                $('#title').val($('#pac-input').val());
                $('#latitude').val(place.geometry.location.lat());
                $('#longitude').val(place.geometry.location.lng());
                $('#radius').val('');
            });
            map.fitBounds(bounds);
        });

        google.maps.event.addListener(marker, 'dragend', function(event, element) {
            lat = this.getPosition().lat();
            long = this.getPosition().lng();

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

</script>
