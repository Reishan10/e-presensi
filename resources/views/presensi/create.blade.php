@extends('layouts.main')
@section('title', 'Buat Presensi')
@section('header')
    <style>
        .webcam-capture,
        .webcam-capture video {
            display: inline-block;
            width: 100% !important;
            margin: auto;
            height: auto !important;
            border-radius: 15px;
        }

        #map {
            height: 200px;
        }
    </style>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css">
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>

    <div class="appHeader bg-primary text-light">
        <div class="left">
            <a href="javascript:;" class="headerButton goBack">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">E-Presensi</div>
        <div class="right"></div>
    </div>
@endsection

@section('content')
    <div class="section full" style="margin-top: 70px;">
        @if ($cek > 0)
            <div class="section-title">Absen Pulang</div>
        @else
            <div class="section-title">Absen Masuk</div>
        @endif
        <div class="wide-block pt-2 pb-2">
            <input type="hidden" name="lokasi" id="lokasi">
            <div class="webcam-capture"></div>
        </div>

        <div class="row mt-2">
            <div class="col">
                @if ($cek > 0)
                    <button class="btn btn-danger btn-block" id="takeAbsen">
                        <ion-icon name="camera-outline"></ion-icon> Absen Pulang
                    </button>
                @else
                    <button class="btn btn-primary btn-block" id="takeAbsen">
                        <ion-icon name="camera-outline"></ion-icon> Absen Masuk
                    </button>
                @endif
            </div>
        </div>

        <div class="row mt-2">
            <div class="col">
                <div id="map"></div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.26/webcam.min.js"
        integrity="sha512-dQIiHSl2hr3NWKKLycPndtpbh5iaHLo6MwrXm7F0FM5e+kL2U16oE9uIwPHUl6fQBeCthiEuV/rzP3MiAB8Vfw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        Webcam.set({
            height: 480,
            width: 640,
            image_format: 'jpeg',
            jpeg_quality: 80
        });

        Webcam.attach('.webcam-capture');

        let lokasi = document.getElementById('lokasi');

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(successCallback, errorCallback);
        }

        function successCallback(position) {
            lokasi.value = position.coords.latitude + ',' + position.coords.longitude;
            var map = L.map('map').setView([position.coords.latitude, position.coords.longitude], 18);

            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(map);

            var marker = L.marker([position.coords.latitude, position.coords.longitude]).addTo(map);

            var circle = L.circle([position.coords.latitude, position.coords.longitude], {
                color: 'red',
                fillColor: '#f03',
                fillOpacity: 0.5,
                radius: 20
            }).addTo(map);
        }

        function errorCallback() {}

        $('#takeAbsen').click(function(e) {
            Webcam.snap(function(uri) {
                image = uri;
            });

            let lokasi = $('#lokasi').val();

            $.ajax({
                type: "POST",
                url: "{{ route('presensi.store') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    image: image,
                    lokasi: lokasi
                },
                cache: false,
                success: function(response) {
                    if (response == 1) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: 'Terimakasih, Selamat Bekerja...!',
                        })
                        setTimeout('location.href="/dashboard"', 3000);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Maaf Gagal Abasen!',
                        })
                    }
                }
            });
        });
    </script>

@endsection
