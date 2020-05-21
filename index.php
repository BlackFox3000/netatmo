<? $test=0 ?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Netatmo project</title>
    <meta name="viewport" content="initial-scale=1,maximum-scale=1,user-scalable=no" />
    <script src="https://api.mapbox.com/mapbox-gl-js/v1.9.1/mapbox-gl.js"></script>
    <link href="https://api.mapbox.com/mapbox-gl-js/v1.9.1/mapbox-gl.css" rel="stylesheet" />
    <style>
        body { margin: 0; padding: 0; }
        #map { position: absolute; bottom: 0; width: 100%;
            height:80%;top: 40%;}
    </style>
    <script src="ajax.js"></script>
</head>
<body>
<div id="resultat"></div>
<!--Info nav-->
<div style="height:10%;width:100%;">
    Coordonnées GSP du centre de la zone: <span id="long"></span> ; <span id="lat"></span>  <br>
    Coordonnées GPS de la zone donnée : <br>
    Lat NE : <div id="lat_ne"></div>
    Lon NE : <div id="lon_ne"></div>
    Lat SW : <div id="lat_sw"></div>
    Lon SW : <div id="lat_se"></div> <br>
    Température moyenne : temp degré<br>
    Altitude moyenne des stations : alt m<br>
    <div id="montest"></div>
    Nombre de stations dans la zone : stations<br>
    En utilisant ce site vous acceptez les cookies utiles à son bon fonctionnement.
    <form>
        <label for="range">Distance <span id="range">50</span> km</label> <br>
        <input type="range" name="range" onchange="document.getElementById('range').innerText=this.value">
    </form>
</div>

<!--map-->
<script src="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v4.4.2/mapbox-gl-geocoder.min.js"></script>
<link
        rel="stylesheet"
        href="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v4.4.2/mapbox-gl-geocoder.css"
        type="text/css"
/>
<!-- Promise polyfill script required to use Mapbox GL Geocoder in IE 11 -->
<div>
    <script src="https://cdn.jsdelivr.net/npm/es6-promise@4/dist/es6-promise.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/es6-promise@4/dist/es6-promise.auto.min.js"></script>
    <div id="map"></div>

</div>


<script>
    mapboxgl.accessToken = 'pk.eyJ1IjoiYmxhY2tmb3gzMDAwIiwiYSI6ImNrMjM5eXd5OTFyNmMzbm12a2pscnhxMW0ifQ.2wfUEW5QjTCHWLzDwIiluQ';
    var map = new mapboxgl.Map({
        container: 'map',
        style: 'mapbox://styles/mapbox/streets-v11',
        center: [-79.4512, 43.6568],
        zoom: 13
    });

    map.addControl( new MapboxGeocoder({
            accessToken: mapboxgl.accessToken,
            mapboxgl: mapboxgl
        }) );

    /**
     *  Get coordonate to search
      */

    // After the map style has loaded on the page,
    // add a source layer and default styling for a single point
    map.on('load', function() {
        map.addSource('single-point', {
            type: 'geojson',
            data: {
                type: 'FeatureCollection',
                features: []
            }
        });

        map.addLayer({
            id: 'point',
            source: 'single-point',
            type: 'circle',
            paint: {
                'circle-radius': 10,
                'circle-color': '#43e431'
            }
        });
        var center=map.getCenter();
        console.log( center);
        document.getElementById('long').innerHTML=center.lng.toFixed(4);
        document.getElementById('lat').innerHTML=center.lat.toFixed(4);
    });

    /**
     * Listener when end move, search of balises start
     */
    map.on('moveend', function() {
        console.log('A moveend event occurred.');
        var center=map.getCenter();
        console.log( center);
        document.getElementById('long').innerHTML=center.lng.toFixed(4);
        document.getElementById('lat').innerHTML=center.lat.toFixed(4);
        createCookie( "lng", center.lng.toFixed(4), "1");
        createCookie( "lat", center.lat.toFixed(4),"1");
        <?php
        include('netatmo.php');
        ?>
    });

    function ajax() {
        //création d'une instance de la clasXMLHtpRequest
        let req=new Xhr();

        //Connaitre le changement d'un état de la liaison
        req.onreadystatechange = function(){
            if(this.readyState === this.DONE /* DONE=4*/){
                //récupération du résultat
                let result=traitementFile(this.responseXML)
                document.getElementById("resultat").innerHTML=result;
            }
        }
        //récupération du fichier réponse.txt ( en mode sychrone => false / true => Asynchrone )
        req.open("GET","test.xml", true);
        req.send(null);
    }

    // Function to create the cookie
    function createCookie(name, value, days) {
        var expires;

        if (days) {
            var date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            expires = "; expires=" + date.toGMTString();
        }
        else {
            expires = "";
        }

        document.cookie = escape(name) + "=" +
            escape(value) + expires + "; path=/";
    }

    function traitementFile(r) {


        let file= r.getElementsByTagName("station");
        let datas = []
        console.log(file.length)
        for(let num_client=0; num_client<file.length ; num_client++){
            console.log(file[num_client])
            for(let child=0; child<file[num_client].childNodes.length; child++){
                console.log(file[num_client].childNodes[child])
            }
        }


        //on fait une boucle sur chaque élément "donnee" trouvé
        var racine = r.getElementsByTagName("tableau")[0].documentElement;
        alert(racine.childNodes.length);
        for(let i=0; i<items.length; i++ )
            alert(items[i].firstChild.data)
    }
</script>

<?php
include('netatmo.php');
?>
</body>
</html>