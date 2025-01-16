function initMap() {
  let markers = [];
  let DOMAIN = $("body").data("domain");

  const map = new google.maps.Map(document.getElementById("map"), {
    zoom: 5,
    center: { lat: -15.5455288, lng: -52.9928446 },
  });
  const geocoder = new google.maps.Geocoder();

  $.ajax({
    url: DOMAIN + "/view/components/palestras/cidades.json",
    dataType: "json"
  })
  .done(function(cityData) {
    let done = 0;
    cityData.forEach(function(city) {
      geocoder.geocode({ address: `${city.city}, ${city.state}, Brasil` }, function(results, status) {
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
            imagePath: DOMAIN + "/view/painel/src/js/scripts/map/pins/m",
            gridSize: 50,
            maxZoom: 14,
            minimumClusterSize: 1
          });
        }
      });
    });
  })
  .fail(function() {
    console.error("Erro ao carregar cidades");
  });
}

$(document).ready(initMap);