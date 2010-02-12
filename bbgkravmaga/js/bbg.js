
/*
 * This function loads the Google map.
 */
function loadGoogleMap() {
  // The point on the map where BBG is located
  var latlng = new google.maps.LatLng(59.93646,  10.87467)
  
  // Creating an option object for the map 
  var myOptions = {
    zoom: 14,
    center: latlng,
    mapTypeId: google.maps.MapTypeId.ROADMAP,
    mapTypeControl: true,
    mapTypeControlOptions: {  
      style: google.maps.MapTypeControlStyle.DROPDOWN_MENU  
    },
    navigationControl: true,
    navigationControlOptions: {  
      style: google.maps.NavigationControlStyle.DEFAULT //the control will vary according the map size and other factors  
    },
    scaleControl: true
  }
  // Initializing the map  
  var map = new google.maps.Map(document.getElementById("bbg_google_map"), myOptions);
  
  // Creating a marker  
  var marker = new google.maps.Marker({  
    position: latlng,
    map: map,
    title: 'BBG Oslo',
    icon: "http://google-maps-icons.googlecode.com/files/gym.png"
  });
  
  // Creating an InfoWindow object  
  var infowindow = new google.maps.InfoWindow({  
    content: "<img src='/images/bbg-logo-small.jpg' width='50' style='float:left; margin:0px 5px 5px 0px;'/>" +
             "<div style='text-align:left; font-size:0.8em; width:250px;'/>" +
             "Vi ligger sentralt til ikke langt unna IKEA. " +
             "Kj&oslash;r inn Tevlingveien, s&aring; finner du oss p&aring; h&oslash;yre side." +
             "<br/><br/>Godt om gratis parkeringsplasser!" +
             "</div>"
  });
  //infowindow.open(map, marker);
  
  // Adding a click event to open the info window
  google.maps.event.addListener(marker, 'click', function() {  
    infowindow.open(map, marker);  
  });
  
}
