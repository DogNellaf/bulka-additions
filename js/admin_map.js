var map;
var mapOverlays = [];
var $mapDataBlock = $('.map_json');
// let infoWindow;
var selectedShape;
var lat = 55.74960517959825;
var lng = 37.618900235469134;
var lastId = 0;

var MY_MAPTYPE_ID = 'custom_style';

function initAdminMap() {
    var oz = new google.maps.LatLng(lat, lng);
    var newCenter = new google.maps.LatLng(lat, lng);

    var featureOpts = [
        {
            "elementType": "geometry",
            "stylers": [
                {
                    "color": "#f5f5f5"
                }
            ]
        },
        {
            "elementType": "labels.icon",
            "stylers": [
                {
                    "visibility": "off"
                }
            ]
        },
        {
            "elementType": "labels.text.fill",
            "stylers": [
                {
                    "color": "#616161"
                }
            ]
        },
        {
            "elementType": "labels.text.stroke",
            "stylers": [
                {
                    "color": "#f5f5f5"
                }
            ]
        },
        {
            "featureType": "administrative.land_parcel",
            "elementType": "labels.text.fill",
            "stylers": [
                {
                    "color": "#bdbdbd"
                }
            ]
        },
        {
            "featureType": "poi",
            "elementType": "geometry",
            "stylers": [
                {
                    "color": "#eeeeee"
                }
            ]
        },
        {
            "featureType": "poi",
            "elementType": "labels.text.fill",
            "stylers": [
                {
                    "color": "#757575"
                }
            ]
        },
        {
            "featureType": "poi.park",
            "elementType": "geometry",
            "stylers": [
                {
                    "color": "#e5e5e5"
                }
            ]
        },
        {
            "featureType": "poi.park",
            "elementType": "labels.text.fill",
            "stylers": [
                {
                    "color": "#9e9e9e"
                }
            ]
        },
        {
            "featureType": "road",
            "elementType": "geometry",
            "stylers": [
                {
                    "color": "#ffffff"
                }
            ]
        },
        {
            "featureType": "road.arterial",
            "elementType": "labels.text.fill",
            "stylers": [
                {
                    "color": "#757575"
                }
            ]
        },
        {
            "featureType": "road.highway",
            "elementType": "geometry",
            "stylers": [
                {
                    "color": "#dadada"
                }
            ]
        },
        {
            "featureType": "road.highway",
            "elementType": "labels.text.fill",
            "stylers": [
                {
                    "color": "#616161"
                }
            ]
        },
        {
            "featureType": "road.local",
            "elementType": "labels.text.fill",
            "stylers": [
                {
                    "color": "#9e9e9e"
                }
            ]
        },
        {
            "featureType": "transit.line",
            "elementType": "geometry",
            "stylers": [
                {
                    "color": "#e5e5e5"
                }
            ]
        },
        {
            "featureType": "transit.station",
            "elementType": "geometry",
            "stylers": [
                {
                    "color": "#eeeeee"
                }
            ]
        },
        {
            "featureType": "water",
            "elementType": "geometry",
            "stylers": [
                {
                    "color": "#c9c9c9"
                }
            ]
        },
        {
            "featureType": "water",
            "elementType": "labels.text.fill",
            "stylers": [
                {
                    "color": "#9e9e9e"
                }
            ]
        }
    ];

    var mapOptions = {
        zoom: 15,
        center: newCenter,
        mapTypeControlOptions: {
            mapTypeIds: [google.maps.MapTypeId.ROADMAP, MY_MAPTYPE_ID]
        },
        mapTypeId: MY_MAPTYPE_ID
    };

    map = new google.maps.Map(document.getElementById('map_place'), mapOptions);
    var styledMapOptions = {
        name: 'Custom Style'
    };

    var customMapType = new google.maps.StyledMapType(featureOpts, styledMapOptions);
    map.mapTypes.set(MY_MAPTYPE_ID, customMapType);


    //set map from DB data
    setMapFromObject();

    //drawingManager
    const drawingManager = new google.maps.drawing.DrawingManager({
        drawingMode: google.maps.drawing.OverlayType.POLYGON,
        drawingControl: true,
        drawingControlOptions: {
            position: google.maps.ControlPosition.TOP_CENTER,
            drawingModes: [
                // google.maps.drawing.OverlayType.MARKER,
                // google.maps.drawing.OverlayType.CIRCLE,
                google.maps.drawing.OverlayType.POLYGON,
                // google.maps.drawing.OverlayType.POLYLINE,
                // google.maps.drawing.OverlayType.RECTANGLE,
            ],
        },
        polygonOptions: {
            //editable: true,
            draggable: true,
        },
    });
    drawingManager.setMap(map);

    google.maps.event.addListener(drawingManager, 'polygoncomplete', function(polygon) {
        //polygon.addListener("click", showArrays);
        // infoWindow = new google.maps.InfoWindow();
        polygon.id = parseInt(lastId) + 1;
        lastId = parseInt(polygon.id);
        polygon.shapeTitle = "Zone " + lastId;
        polygon.mkad = 0;
        mapOverlays.push(polygon);

        setSelection(polygon);
        google.maps.event.addListener(polygon, 'click', function (e) {
            setSelection(polygon);
        });
    });

    function mapToJSONString(){
        var result = JSON.stringify( mapToObject() );

        if( $mapDataBlock.length ){
            //$('.map_json').value =  result;
            $mapDataBlock.val(result);
        }
        return result;
    }

    function mapToObject(){
        var tmpMap = {};
        var tmpOverlay, paths;
        tmpMap.zoom = map.getZoom();
        tmpMap.center = { lat: map.getCenter().lat(), lng: map.getCenter().lng() };
        tmpMap.overlays = [];

        for( var i=0; i < mapOverlays.length; i++ ){
            if( mapOverlays[i].getMap() == null ){
                continue;
            }
            tmpOverlay = {};
            tmpOverlay.type = mapOverlays[i].type;
            tmpOverlay.title = mapOverlays[i].title;
            tmpOverlay.content = mapOverlays[i].content;

            tmpOverlay.id = mapOverlays[i].id;
            tmpOverlay.shapeTitle = mapOverlays[i].shapeTitle;
            tmpOverlay.mkad = mapOverlays[i].mkad;

            if( mapOverlays[i].fillColor ){
                tmpOverlay.fillColor = mapOverlays[i].fillColor;
            }

            if( mapOverlays[i].fillOpacity ){
                tmpOverlay.fillOpacity = mapOverlays[i].fillOpacity;
            }

            if( mapOverlays[i].strokeColor ){
                tmpOverlay.strokeColor = mapOverlays[i].strokeColor;
            }

            if( mapOverlays[i].strokeOpacity ){
                tmpOverlay.strokeOpacity = mapOverlays[i].strokeOpacity;
            }

            if( mapOverlays[i].strokeWeight ){
                tmpOverlay.strokeWeight = mapOverlays[i].strokeWeight;
            }

            if( mapOverlays[i].icon ){
                tmpOverlay.icon = mapOverlays[i].icon;
            }

            if( mapOverlays[i].flat ){
                tmpOverlay.flat = mapOverlays[i].flat;
            }

            /*
            if( mapOverlays[i].type == "polygon" ){
                tmpOverlay.paths = [];
                paths = mapOverlays[i].getPaths();
                for( var j=0; j < paths.length; j++ ){
                    tmpOverlay.paths[j] = [];
                    for( var k=0; k < paths.getAt(j).length; k++ ){
                        tmpOverlay.paths[j][k] = { lat: paths.getAt(j).getAt(k).lat().toString() , lng: paths.getAt(j).getAt(k).lng().toString() };
                    }
                }

            }
            */

            let vertices = mapOverlays[i].getPath();
            // let contentString =
            //     "<b>Bermuda Triangle polygon</b><br>" +
            //     "Clicked location: <br>" +
            //     event.latLng.lat() +
            //     "," +
            //     event.latLng.lng() +
            //     "<br>";

            tmpOverlay.paths = [];
            // Iterate over the vertices.
            for (let m = 0; m < vertices.getLength(); m++) {
                const xy = vertices.getAt(m);
                tmpOverlay.paths[m] = {};
                tmpOverlay.paths[m].lat = xy.lat();
                tmpOverlay.paths[m].lng = xy.lng();
            }

            tmpMap.overlays.push( tmpOverlay );
        }
        return tmpMap;
    }

    function setMapFromObject(){
        let jsonString = $mapDataBlock.val();
        if( jsonString.length == 0 ){
            return false;
        }
        var inputData = JSON.parse( jsonString );
        if( inputData.zoom ){
            map.setZoom( inputData.zoom );
        }

        if( inputData.center ){
            map.setCenter( new google.maps.LatLng( inputData.center.lat, inputData.center.lng ) );
        }

        if (inputData.overlays.length) {
            for (let i = 0; i < inputData.overlays.length; i++) {
                let polygon = inputData.overlays[i].paths;
                let color = '#FF0000';
                if (inputData.overlays[i].fillColor)
                    color = inputData.overlays[i].fillColor;

                const poly = new google.maps.Polygon({
                    paths: polygon,
                    // strokeColor: '#FF0000',
                    strokeOpacity: 0.8,
                    strokeWeight: 0,
                    fillColor: color,
                    fillOpacity: 0.35,
                    name: 'Полигон',
                    // editable: true,
                    draggable: true,
                    map: map
                });
                poly.setMap(map);
                let overlayId = inputData.overlays[i].id;
                if (overlayId && parseInt(overlayId) > parseInt(lastId)) {
                    lastId = parseInt(overlayId);
                }
                poly.id = inputData.overlays[i].id;
                poly.shapeTitle = inputData.overlays[i].shapeTitle;
                poly.mkad = inputData.overlays[i].mkad;
                mapOverlays.push( poly );

                //poly.addListener("click", showArrays);

                google.maps.event.addListener(poly, 'click', function (e) {
                    setSelection(poly);
                });
                // infoWindow = new google.maps.InfoWindow();
            }
        }
        var tmpOverlay, ovrOptions;
    }

    function clearSelection () {
        if (selectedShape) {
            if (selectedShape.type !== 'marker') {
                selectedShape.setEditable(false);
            }

            selectedShape = null;
        }
    }

    function setSelection (shape) {
        clearSelection();
        shape.setEditable(true);
        setColor(shape.fillColor);
        setId(shape.id);
        setShapeTitle(shape.shapeTitle);
        setMkad(shape.mkad);

        selectedShape = shape;
    }

    google.maps.event.addDomListener(document.getElementById('delete-shape-button'), 'click', deleteSelectedShape);

    function deleteSelectedShape () {
        if (selectedShape) {
            selectedShape.setMap(null);
        }
    }

    $(document).on("change", "input[name=shape-color]", function (event) {
        let color = $(this).val();
        setSelectedShapeColor(color);
    });

    $(document).on("change", "input[name=shape-id]", function (event) {
        let val = $(this).val();
        setSelectedShapeId(val);
    });

    $(document).on("change", "input[name=shape-title]", function (event) {
        let val = $(this).val();
        setSelectedShapeTitle(val);
    });

    $(document).on("change", "input[name=shape-mkad]", function (event) {
        let val = 0;
        if ( $(this).prop( "checked" ) )
            val = 1;
        setSelectedShapeMkad(val);
    });

    function setSelectedShapeColor (color) {
        if (selectedShape && color) {
            selectedShape.set('fillColor', color);
        }
    }

    function setSelectedShapeId (val) {
        if (selectedShape) {
            selectedShape.set('id', val);
        }
    }

    function setSelectedShapeTitle (val) {
        if (selectedShape) {
            selectedShape.set('shapeTitle', val);
        }
    }

    function setSelectedShapeMkad (val) {
        if (selectedShape) {
            selectedShape.set('mkad', val);
        }
    }

    //todo dont work
    function setColor (color) {
        if (color) {
            $('input[name="shape-color"]').val(color);
            // $('input[name="shape-color"]').spectrum("set", color);
            // var t = $('input[name="shape-color"]').spectrum("get");
            // console.log(t);
        }
    }

    function setId (val) {
        $('input[name="shape-id"]').val(val);
    }

    function setShapeTitle (val) {
        $('input[name="shape-title"]').val(val);
    }

    function setMkad (val) {
        console.log(val);
        if (val) {
            val = 1;
        }
        else {
            val = 0;
        }
        $('input[name="shape-mkad"]').prop('checked', val);
    }

    $(document).on("click", ".draw_mode", function (event) {
        event.preventDefault();
        console.log(3);
        drawingManager.setMap(map);
    });

    $(document).on("click", ".no_draw_mode", function (event) {
        event.preventDefault();
        drawingManager.setMap(null);
    });

    $(document).on("click", ".gen_json", function (event) {
        mapToJSONString();
    });

    $(document).on("click", ".gen_from_json", function (event) {
        setMapFromObject();
    });

    $(document).on("click", ".map_submit_btn", function (event) {
        mapToJSONString();
        $(this).closest('form').submit();
    });

    /*
    function showArrays(event) {
        return;

        // Since this polygon has only one path, we can call getPath() to return the
        // MVCArray of LatLngs.
        const polygon = this;
        const vertices = polygon.getPath();
        let contentString =
            "<b>Bermuda Triangle polygon</b><br>" +
            "Clicked location: <br>" +
            event.latLng.lat() +
            "," +
            event.latLng.lng() +
            "<br>";

        // Iterate over the vertices.
        for (let i = 0; i < vertices.getLength(); i++) {
            const xy = vertices.getAt(i);
            contentString +=
                "<br>" + "Coordinate " + i + ":<br>" + xy.lat() + "," + xy.lng();
        }
        // Replace the info window's content and position.
        infoWindow.setContent(contentString);
        infoWindow.setPosition(event.latLng);
        infoWindow.open(map);
    }
    */

    /*
    const poligons = [[{lat: 50.015104, lng: 36.17412}, {lat: 50.0001, lng: 36.16382}
        , {lat: 49.987078, lng: 36.197466}], [{lat: 50.022345, lng: 36.203303},
        {lat: 50.011977, lng: 36.200213}, {lat: 50.014845, lng: 36.219782},
        {lat: 50.021242, lng: 36.220125}, {lat: 50.02411, lng: 36.212229},]];

    const poligon = [{lat: 50.015104, lng: 36.17412}, {lat: 50.0001, lng: 36.16382}, {lat: 49.987078, lng: 36.197466}];

    const poly = new google.maps.Polygon({
        paths: poligon,
        strokeColor: '#FF0000',
        strokeOpacity: 0.8,
        strokeWeight: 1,
        fillColor: '#FF0000',
        fillOpacity: 0.35,
        name: 'Полигон',
        //zIndex:1,
        map: map
    });
    google.maps.event.addListener(poly, 'click', showArrays);
    poly.setMap(map);
    */

}
