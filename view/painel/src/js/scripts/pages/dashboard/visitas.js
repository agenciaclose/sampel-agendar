let markers = [];


function initMap() {

  var DOMAIN = $('body').data('domain');

  const map = new google.maps.Map(document.getElementById("map"), {
    zoom: 8,
    center: { lat: -23.6824124, lng: -46.5952992 },
  });
  const geocoder = new google.maps.Geocoder();
  const markers = [];
  let done = 0;

  cityData.forEach(city => {
    geocoder.geocode({ address: `${city.city}, ${city.state}, Brasil` }, (results, status) => {
      if (status === "OK") {
        markers.push(new google.maps.Marker({
          position: results[0].geometry.location,
          map,
          title: `${city.city} - ${city.state}`
        }));
      }
      done++;
      if (done === cityData.length) {
        new MarkerClusterer(map, markers, {
          imagePath: DOMAIN + '/view/painel/src/js/scripts/map/pins/m',
          gridSize: 50,
          maxZoom: 14,
          minimumClusterSize: 1
        });
      }
    });
  });
}


initMap();