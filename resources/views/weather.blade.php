@extends('app')

@section('content')

    <style>
        #map {
            height: 400px;
            margin-top: 25px;
            width: 500px
        }
        #cities {
            margin-left: 30%;
            margin-top: -19%;
        }
        .list {
            margin-left: 30%;
            margin-top: -22%;
        }
        .city {
            width: 146%;
            margin-top: 15px;
        }
        .latitude {
            width: 146%;
            margin-top: 15px;
        }
        .longitude {
            width: 146%;
            margin-top: 35px;
        }
        .or {
            color: #000000;
            font-size: 20px;
            margin-top: 30px;
            margin-left: -90px;
        }
        .example {
            color: #808080;
        }
        .see {
            margin-top: 35px;
            margin-left: -90px;
        }
    </style>

    <div class="card-body">
        <div id="weather-result">

        </div>
        <div class="row">
            <div>Введите данные для получения погоды</div>
            <input class="city" name="city" placeholder="Название города">
            <div class="example">Пример: Минск</div>
            <div class="or">ИЛИ</div>
            <input class="latitude" name="latitude" placeholder="Широта">
            <div class="example">Пример: 53.07</div>
            <input class="longitude" name="longitude" placeholder="Долгота">
            <div class="example">Пример: 26.64</div>
            <input type="hidden" name="_token" value="{{csrf_token()}}">
            <button id="handle-search-weather" class="see">Посмотреть погоду</button>
        </div>
        <div id="map"></div>
        <button id="latest-cities-list" class="list">Показать последние вводимые города</button>
        <div id="cities">

        </div>

    </div>

    <script>
        window.onload = function() {
            $('#handle-search-weather').on('click', function () {
                $.ajax({
                    url: '/search-weather',
                    data: {
                        name: $("input[name=city]").val(),
                        latitude: $("input[name=latitude]").val(),
                        longitude: $("input[name=longitude]").val()
                    },
                    success: (response) => {
                        response.data === null
                            ? $("#weather-result").html(`<p>Ничего не найдено</p>`)
                            : $("#weather-result").html(
                                `<p>Погода: ${response.data.weather[0].description}, Скорость ветра: ${response.data.wind.speed}, Температура: ${response.data.main.temp}</p>`
                            );
                    },
                    error: (response, status, errorThrown) => {
                        let error = '';
                        for (let errorField in response.responseJSON.errors) {
                            error = `<p style="color: red">-${response.responseJSON.errors[errorField][0]}</p>`
                        }
                        $("#weather-result").html(`
                            <p style="color: red">${response.responseJSON.message}:</p>
                            ${error}
                        `);
                    },
                    type: 'post',
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('input[name="_token"]').val()
                    }
                });
            });

            $('#latest-cities-list').on('click', function () {
                $.ajax({
                    url: '/get-list',
                    data: {},
                    success: (response) => {
                        response.data === null
                            ? $("#cities").html(`<p>Ничего не найдено</p>`)
                            : $.each(response.data,function(index,value){
                                $("#cities").append(`<li>${value}</li>`);
                            });
                    },
                    error: (response, status, errorThrown) => {
                        return false
                    },
                    type: 'post',
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('input[name="_token"]').val()
                    }
                });
            });

            const map = new google.maps.Map(document.getElementById('map'));

            google.maps.event.addListener(map, 'dblclick', (event) => {
                const latlng = {lat: event.latLng.lat(), lng: event.latLng.lng()};
                const geocoder = new google.maps.Geocoder;
                geocoder.geocode({'location': latlng}, (results, status) => {
                    if (status === google.maps.GeocoderStatus.OK) {
                        if (results[0]) {
                            $("input[name=city]").val('');
                            $("input[name=latitude]").val(event.latLng.lat);
                            $("input[name=longitude]").val(event.latLng.lng);
                        }
                    }
                });
            });
        }
    </script>