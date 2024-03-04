var map;
var mapOverlays = [];
let currentOverlay;
var $mapDataBlock = $('.map_json');
let infoWindow;
var selectedShape;
var lat = 55.74960517959825;
var lng = 37.618900235469134;
let dadataToken = "8c64b85ec3e66906fdb566a2632f096fa693660b";
let $dadataInput = $(".dadata_address");
let $dadataMessage = $(".dadata_message");
let $costBlock = $(".checkout_cost");
let $deliveryCostBlock = $(".checkout_delivery_cost");
let $deliveryCostInput = $("#orderform-delivery_cost");
let $totalCostBlock = $(".checkout_total_cost");

var MY_MAPTYPE_ID = 'custom_style';

function initCheckoutMap() {
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

    map = new google.maps.Map(document.getElementById('checkout_map_place'), mapOptions);
    var styledMapOptions = {
        name: 'Custom Style'
    };

    var customMapType = new google.maps.StyledMapType(featureOpts, styledMapOptions);
    map.mapTypes.set(MY_MAPTYPE_ID, customMapType);


    //set map from DB data
    setMapFromObject();

    function setMapFromObject(){
        let jsonString = $mapDataBlock.html();
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
                    map: map
                });
                poly.setMap(map);
                poly.id = inputData.overlays[i].id;
                poly.mkad = inputData.overlays[i].mkad;
                mapOverlays.push( poly );

                google.maps.event.addListener(poly, 'click', function (e) {
                    mapClickListener(e);
                });
            }
        }
    }

    var pinColor = "ea4335";
    var pinImage = new google.maps.MarkerImage("http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=%E2%80%A2|" + pinColor,
        new google.maps.Size(21, 54),
        new google.maps.Point(0, 0),
        new google.maps.Point(10, 34),
    );

    var marker = new google.maps.Marker({
        map: map,
        // icon: image,
        icon: pinImage,
        title: 'Доставить сюда',
    });

    infoWindow = new google.maps.InfoWindow();

    if ($dadataInput.length) {
        $dadataInput.suggestions({
            token: dadataToken,
            type: "ADDRESS",
            constraints: {
                // ограничиваем поиск Москвой
                locations: { region: "Москва" },
            },
            // в списке подсказок не показываем область
            restrict_value: true,

            /* Вызывается, когда пользователь выбирает одну из подсказок */
            onSelect: function(suggestion) {
                console.log(suggestion);
                currentOverlay = null;
//                runAddressHandler(suggestion); //закомментировано для работы заказов
            },
            onSelectNothing: function(suggestion) {
                console.log(suggestion);
                currentOverlay = null;
                showDadataMessage("Выберите адрес из списка");
            },
        });
    }

    map.addListener("click", function (e) {
        mapClickListener(e);
    });

    function mapClickListener(e) {
        currentOverlay = null;
        reCalcDeliveryCost();
        setMarkerPosition(e.latLng);
        loadAddressFromCoords(e.latLng.lat(), e.latLng.lng());
    }

    function runAddressHandler(suggestion) {
        if (isHouseFound(suggestion)) {
            showDadataHouse(suggestion);
            let coords = getDadataAddressCoords(suggestion);
            let lat = coords.lat;
            let lng = coords.lng;
            setMapToCoords(lat, lng);

            let googleCoords = new google.maps.LatLng(lat, lng);
            let overlay = isAddressInPolygon(googleCoords);
            let message;
            if (overlay) {

                message = "";
            } else {
                message = "К сожалению, нет доставки";
            }
            ajaxGetDeliveryTimeIntervals();
            showDadataMessage(message);

        } else {
            showDadataUnknown(suggestion);
        }
    }

    function isHouseFound(suggestion) {
        return suggestion.data.qc_geo === "0" || suggestion.data.qc_geo === "1";
    }

    function showDadataMessage(message) {
        $dadataMessage.text(message);
    }

    function showDadataUnknown(suggestion) {
        var message;
        if (suggestion.data.house) {
            message = "Координаты дома неизвестны";
        } else {
            message = "Укажите адрес до дома";
        }
        $dadataMessage.text(message);
        $dadataMessage.data("coords", "");
        $dadataMessage.show();

        showDadataMessage(message);
    }

    function showDadataHouse(suggestion) {
        var coords = getDadataAddressCoords(suggestion);
        let lat = coords.lat;
        let lng = coords.lng;

        showDadataMessage("");
    }

    function getDadataAddressCoords(suggestion) {
        return {
            lat: suggestion.data.geo_lat,
            lng: suggestion.data.geo_lon,
        }
    }

    function setMapToCoords(lat, lng) {
        map.panTo(new google.maps.LatLng(lat, lng));
        setMarkerPosition(new google.maps.LatLng(lat, lng));
    }

    function setMarkerPosition(latLng) {
        marker.setPosition(latLng);

        let isDelivery = isAddressInPolygon(latLng);
        let contentString = 'Доставить сюда';
        if (!isDelivery) {
            contentString = 'Нет доставки';
        }
        infoWindow.setContent(contentString);
        infoWindow.open(map, marker);
    }

    function isAddressInPolygon(latLng) {
        currentOverlay = null;
        let res = null;
        for (let i = 0; i < mapOverlays.length; i++) {
            if (google.maps.geometry.poly.containsLocation(latLng, mapOverlays[i])) {
                res = mapOverlays[i];
                break;
            }
        }
        currentOverlay = res;
        return res;
    }

    function loadAddressFromCoords(lat, lon) {
        var promise = geolocate(lat, lon);
        promise
            .done(function(response) {
                if (response.suggestions.length) {
                    //дом найден
                    let suggestion = response.suggestions[0];
                    setAddressFromMap(suggestion);
//                    runAddressHandler(suggestion); //закомментировано для работы заказов
                } else {
                    //дом не найден
                    showDadataMessage("Уточните адрес");
                }
            })
            .fail(function(jqXHR, textStatus, errorThrown) {
                console.log(textStatus);
                console.log(errorThrown);
            });
    }

    function setAddressFromMap(suggestion) {
        var address = suggestion.value;
        $dadataInput.val(address);
        $dadataInput.suggestions().update();
        showDadataMessage("Выберите адрес из списка");
    }

    function geolocate(lat, lon) {
        var serviceUrl = "https://suggestions.dadata.ru/suggestions/api/4_1/rs/geolocate/address";
        var request = {
            "lat": lat,
            "lon": lon
        };
        var params = {
            type: "POST",
            contentType: "application/json",
            headers: {
                "Authorization": "Token " + dadataToken
            },
            data: JSON.stringify(request)
        };
        return $.ajax(serviceUrl, params);
    }

    function isAddressCorrect() {
        let suggestion = $dadataInput.suggestions().selection;
        console.log(suggestion);
        if (!suggestion) {
            return false;
        }
        if (!suggestion.data.house) {
            console.log('no house');
            return false;
        }

        setElementaryAddress(suggestion);

        let coords = getDadataAddressCoords(suggestion);
        let lat = coords.lat;
        let lng = coords.lng;
        let googleCoords = new google.maps.LatLng(lat, lng);
        //закомментировано для работы заказов
//        let overlay = isAddressInPolygon(googleCoords);
//        if (!overlay) {
//            return false;
//        } else {
            return true;
//        }
    }

    function setElementaryAddress(suggestion) {
        let street = suggestion.data.street_with_type;
        let house = '';
        if (suggestion.data.house_type) {
            house += suggestion.data.house_type + ' ';
        }
        house += suggestion.data.house;
        if (suggestion.data.block_type) {
            house += ' ' + suggestion.data.block_type;
        }
        if (suggestion.data.block) {
            house += ' ' + suggestion.data.block;
        }
        $('#orderform-elementary_street').val(street);
        $('#orderform-elementary_house').val(house);
    }

    $(document).on("click", ".checkout_submit_btn", function (event) {

        if (!customFieldsValidation(true)) {
            console.log('custom validation error');
            return false;
        }
        console.log('custom validation success');

        $(this).closest('form').submit();
    });

    function customFieldsValidation(submit = false) {
        let $stepBlock = $('.checkout_step.validating');
        if (submit || $stepBlock.find('#orderform-street').length) {
            if ($('#delivery_method-delivery').prop('checked')) {
                //delivery method
                let isCorrectAddress = isAddressCorrect();
                if (!isCorrectAddress) {
                    $dadataInput.suggestions().update();
                    showDadataMessage("Уточните адрес");
                    $('body,html').animate({scrollTop: $dadataInput.offset().top - 200}, 1000);
                    return false;
                } else {

                }
            } else {
                //self-delivery method
            }
        }
        if (submit || $stepBlock.find('#orderform-delivery_time').length) {
            if (!$('#orderform-delivery_time').val().length) {
                $('body,html').animate({scrollTop: $('#orderform-delivery_time').offset().top - 200}, 1000);
                return false;
            }
        }
        return true;
    }

    $(document).on("change", "input[name='checkout_addresses_switch']", function (event) {
        $dadataInput.suggestions().update();
        showDadataMessage("Уточните адрес");
    });

    $('.check_info_addresses_item').click(function () {
        if ($(this).hasClass('active'))
            return false;

        $dadataInput.suggestions().update();
        showDadataMessage("Уточните адрес");
    });

    $(document).on("change", "#orderform-delivery_date", function (event) {
        showPayMethods();
        clearValues();
    });

    function isSelectedDateIsToday(date) {
        let curDate = new Date();

        let month = curDate.getMonth() + 1;
        let day = curDate.getDate();

        let strDate = (day<10 ? '0' : '') + day + '.'
            + (month<10 ? '0' : '') + month + '.'
            + curDate.getFullYear();

        return (date == strDate);
    }

    $(document).on("change", ".delivery_radio", function (event) {
        clearValues();

        if ($('#delivery_method-pickup').prop('checked')) {
            $('.check_info_addresses').slideUp();
            $('.check_top_delivery_block').slideUp();
            $('.checkout_map_block').slideUp();
            $('.check_top_pickup_block').slideDown();
        } else {
            $('.check_info_addresses').slideDown();
            $('.check_top_delivery_block').slideDown();
            $('.checkout_map_block').slideDown();
            $('.check_top_pickup_block').slideUp();
        }
        showPayMethods();
    });

    function showPayMethods() {
        $('#pay_method-online').prop('checked', true);
        let isToday = isSelectedDateIsToday($('#orderform-delivery_date').val());

        if ($('#delivery_method-pickup').prop('checked')) {
            $('.checkout_pay_input.delivery_pay_method').hide();
            $('.checkout_pay_input.self_pay_method').show({
                start: function() {
                    $(this).css('display', 'inline-flex');
                }
            });
        } else {
            $('.checkout_pay_input.self_pay_method').hide();
            $('.checkout_pay_input.delivery_pay_method').show({
                start: function() {
                    $(this).css('display', 'inline-flex');
                }
            });
        }
        if (isToday) {
            $('.checkout_pay_input.delivery_pay_method').hide();
            $('.checkout_pay_input_today_info').slideDown();
            console.log('today');
        } else {
            $('.checkout_pay_input_today_info').slideUp();
            console.log('not today');
        }
    }

    $(document).on('click', '.delivery_time .select_opt' ,function (event) {
        setDeliveryTime($(this));
        //$('#checkout-form').yiiActiveForm("validate");
        //$('#checkout-form').yiiActiveForm('validateAttribute', 'OrderForm[delivery_time]');
    });

    function setDeliveryTime($optionItem) {
        $('#orderform-delivery_time').val($optionItem.data('interval'));
        $('#orderform-delivery_time').data('cost', $optionItem.data('cost'));
        $('.delivery_time_input_wrap .help-block-error').html('');
        reCalcDeliveryCost();
    }

    $(document).on('click', '.delivery_pickup_block .select_opt' ,function (event) {
        $('#orderform-delivery_pickup_point').val($(this).data('val'));
    });

    function clearValues() {
        /*
        if (!saveDate) {
            $('#orderform-delivery_date').val('');
        }
        */
        $('#orderform-delivery_time').val('');
        $('#orderform-delivery_time').data('cost', '');
        $('#orderform-delivery_quickly').prop('checked', false);
        clearDeliveryCost();
        ajaxGetDeliveryTimeIntervals();
    }

    function clearDeliveryCost() {
        let deliveryCost = $deliveryCostBlock.data('cost');
        let cost = $costBlock.data('cost');
        let totalCost = cost + deliveryCost;
        $deliveryCostInput.val(deliveryCost);
        $deliveryCostBlock.text(deliveryCost + ' ₽');
        $totalCostBlock.text(totalCost + ' ₽');
    }

    function reCalcDeliveryCost() {
        let $currentTimeBlock = $('#orderform-delivery_time');
        let deliveryCost = $currentTimeBlock.data('cost');
        console.log('deliveryCost = ' + deliveryCost);
        if (!deliveryCost) {
            deliveryCost = $deliveryCostBlock.data('cost');
        }
        console.log('deliveryCost = ' + deliveryCost);
        let cost = $costBlock.data('cost');
        let totalCost = cost + deliveryCost;
        $deliveryCostInput.val(deliveryCost);
        $deliveryCostBlock.text(deliveryCost + ' ₽');
        $totalCostBlock.text(totalCost + ' ₽');
    }

    function ajaxGetDeliveryTimeIntervals() {
        let $block = $('.delivery_time');
        let url = $block.data('href');
        let date = $('#orderform-delivery_date').val();
        let delivery_self = ($('#delivery_method-pickup').prop('checked')) ? 1 : 0;
        let shape_id = null;
        let inner_mkad = null;
        if (currentOverlay && currentOverlay.id) {
            shape_id = currentOverlay.id;
        }
        if (currentOverlay && currentOverlay.mkad) {
            inner_mkad = 1;
        }

        let prefix;
        if (date) {
            prefix = (url.indexOf('?') > -1) ? "&" : '?';
            url += prefix + 'date=' + date;
        }
        if (delivery_self) {
            prefix = (url.indexOf('?') > -1) ? "&" : '?';
            url += prefix + 'delivery_self=' + delivery_self;
        }
        if (shape_id) {
            prefix = (url.indexOf('?') > -1) ? "&" : '?';
            url += prefix + 'shape_id=' + shape_id;
        }
        if (inner_mkad) {
            prefix = (url.indexOf('?') > -1) ? "&" : '?';
            url += prefix + 'inner_mkad=' + inner_mkad;
        }
        console.log(url);
        $.ajax({
            url: url,
            type: "GET",
            success: function (res) {
                // if (!res) alert("Ошибка!");
                $block.html($(res).find('.ajax-cont').html());
                let curTimeVal = $('#orderform-delivery_time').val();
                if (curTimeVal.length) {
                    let $optionItem = $block.find('.select_opt[data-interval="' + curTimeVal + '"]');
                    if ($optionItem.length) {
                        $optionItem.click();
                    } else {
                        $optionItem = $block.find('.select_opt').eq(0);
                        if ($optionItem.length) {
                            $optionItem.click();
                        }
                    }
                }
            },
            error: function () {
                // alert('Ошибка AJAX!');
            }
        });
    }

    let stepValidateTimer;

    $('.checkout_step_btn').click(function () {
        let $btn = $(this);
        clearInterval(stepValidateTimer);
        let $form = $(this).closest('form');
        let $stepBlock = $(this).closest('.checkout_step');

        let stepAttributesIdsArr = [];
        $form.data('yiiActiveForm').attributes.forEach(function(item, i, arr) {
            if ($stepBlock.find('#' + item.id).length){
                stepAttributesIdsArr.push('#' + item.id);
            }
        });
        let stepAttributesIds = stepAttributesIdsArr.join(',');

        let $inputs = $stepBlock.find(stepAttributesIds);
        $inputs.addClass('pendingValidate');
        // $form.yiiActiveForm("validate", true);
        $inputs.each(function () {
            $form.yiiActiveForm('validateAttribute', $(this).attr('id'));
            //let attr = $form.yiiActiveForm('find', $(this).attr('id'));
            //console.log(attr.status);
        });
        // console.log($form.data('yiiActiveForm'));
        stepValidateTimer = setInterval(function () {
            if ($stepBlock.find(".pendingValidate").length) {
                console.log('not all fields ready');
            } else {
                console.log('all fields ready');
                if ($stepBlock.find(".has-error").length) {
                    console.log('error validation');
                } else {
                    console.log('success validation');

                    if (!customFieldsValidation()) {
                        console.log('custom validation error');
                    } else {
                        console.log('custom validation success');
                        showNextCheckoutStep($btn.data('next-step'));
                    }
                }
                clearInterval(stepValidateTimer);
            }
        }, 100);
    });

    $(document).on("beforeValidate", "#checkout-form", function (event, messages, deferreds) {
        //console.log('BEFORE VALIDATE TEST');
    }).on("afterValidate", "#checkout-form", function (event, messages, errorAttributes) {
        //console.log('AFTER VALIDATE TEST');
    });

    $(document).on("beforeValidateAttribute", "#checkout-form", function (event, attribute, messages, deferreds) {
        //console.log('BEFORE VALIDATE ATTR TEST');
    }).on("afterValidateAttribute", "#checkout-form", function (event, attribute, messages) {
        //console.log('AFTER VALIDATE ATTR TEST');
        let $input = $(attribute.input);
        $input.removeClass('pendingValidate');
    });

    $('.checkout_step_edit_btn').click(function () {
        showNextCheckoutStep($(this).closest('.checkout_step').data('step'));
    });

    function showNextCheckoutStep(nextStep) {
        let $stepsBlocks = $('.checkout_step');
        let $stepBlock = $('.checkout_step[data-step="' + nextStep + '"]');
        $stepsBlocks.each(function () {
            if (parseInt($(this).data('step')) < nextStep) {
                $(this).addClass('validated')
                    .removeClass('validating');
            } else if (parseInt($(this).data('step')) > nextStep) {
                $(this).removeClass('validated')
                    .removeClass('validating')
                    .slideUp();
            }
        });
        $stepBlock.addClass('validating')
            .removeClass('validated')
            .slideDown();

        setTimeout(function () {
            $('body,html').animate({scrollTop: $stepBlock.offset().top - 200}, 1000);
        }, 400);
    }

    //todo ??
    // setInterval(reCalcDeliveryCost, 1000);


}
