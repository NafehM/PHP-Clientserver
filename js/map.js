map = new OpenLayers.Map("map");
map.addLayer(new OpenLayers.Layer.OSM());

epsg4326 =  new OpenLayers.Projection("EPSG:4326"); //WGS 1984 projection
projectTo = map.getProjectionObject(); //The map projection (Spherical Mercator)

var lonLat = new OpenLayers.LonLat( -0.1279688 ,51.5077286 ).transform(epsg4326, projectTo);
var vectorLayer = new OpenLayers.Layer.Vector("Overlay");


var zoom=1;
map.setCenter (lonLat, zoom);
/*if the user is logged in, shows his location on the map*/
if(isLoggedIn){
    showUsersOnMap();

    setInterval(showUsersOnMap, 10000);
    /* Calling the function `getUserLocation()` every 7 seconds. */
    setInterval(updateCurrentUserLocation, 7000);
}
/*shows user's location on the map*/
function showUsersOnMap(){
    /* Removing all the features from the map. */
    removeFeatures();

 xml.open('GET', "./usersMap.php?userId="+loggedInUser+ "&token="+token);
 xml.send();
    /* An event handler that is called when the readyState attribute changes. */
    xml.onreadystatechange = function (){
        /* Checking if the request was successful. */
        if (this.readyState == 4 && this.status == 200) {

            /* Converting the JSON string into a JavaScript object. */
            var users = JSON.parse(this.responseText);

            /* Looping through the array of objects and calling the function `createMarker()` for each
            object. */
            users.forEach(function (user){
                createMarker(user);

            });
            /* It adds the vector layer to the map. */
            addVectorLayer();

        }
    };
}


/* updates the logged in user's location */
function updateCurrentUserLocation(){
    if(!navigator.geolocation){
        /* A function that I created to display a message on the page. */
        let m = document.getElementById('map');
        m.innerText = 'Geolocation is not available';
    }else{
        navigator.geolocation.getCurrentPosition((position)=>{

            updateUserPosition(position.coords.longitude, position.coords.latitude);
        },function (error) {
            errorUserPosUpdate(error);
        });
    }
}

function errorUserPosUpdate (error){
    /* Alerting the user of the error. */
    alert('ERROR('+error.code+'):'+ error.message);
}
/* updates user's location */
function updateUserPosition (longitude,latitude){

    xml.open('GET', "./currentUserMap.php?userId="+loggedInUser+ "&token="+token+ "&longitude="+longitude+ "&latitude="+latitude);
    xml.send();
    /* An event handler that is called when the readyState attribute changes. */
    xml.onreadystatechange = function (){
        /* Checking if the request was successful. */
        if (this.readyState == 4 && this.status == 200) {
            /* Logging the response from the server to the console. */
            console.log(this.responseText);
        }
    }
}

function createMarker(user){
    // Define markers as "features" of the vector layer:
    /* Creating a marker on the map. */
    var feature = new OpenLayers.Feature.Vector(
        new OpenLayers.Geometry.Point( user.longitude, user.latitude ).transform(epsg4326, projectTo),
        {description: '<div class=" mt-2 w-auto d-flex justify-content-center text-danger"><img src="./images/' + user.photo_name + '" alt="Photo" id="imgTag" class=" rounded-3 img-thumbnail" width="100" height="100"/><div class="d-md-flex w-100 h-100"><table class="table align-self-center"> <thead class="fs-6" > <tr><th class="border-0 " scope="col">Name</th><th class="border-0 " scope="col">Email</th></tr></thead><tbody ><tr><td style="word-break:break-all;" class="border-0 text-wrap ">' + user.first_name +' '+user.last_name+ '</td><td style="word-break:break-all;" class="border-0 " id="txtFlow">' + user.email + '</td></tr></tbody></table></div></div>'},
        {externalGraphic: './images/marker.png', graphicHeight: 30, graphicWidth: 30, graphicXOffset:-12, graphicYOffset:-25  }
    );
    /* It adds the marker to the vector layer. */
    vectorLayer.addFeatures(feature);
}

function addVectorLayer(){
    map.addLayer(this.vectorLayer);
}

/* removes the current marker */
function removeFeatures(){
    vectorLayer.removeAllFeatures();
}

//Add a selector control to the vectorLayer with popup functions
var controls = {
    selector: new OpenLayers.Control.SelectFeature(vectorLayer, { onSelect: createPopup, onUnselect: destroyPopup })
};
/* shows user's details when clicked on the marker */
function createPopup(feature) {
    feature.popup = new OpenLayers.Popup.FramedCloud("pop",
        feature.geometry.getBounds().getCenterLonLat(),
        null,
        '<div class="markerContent">'+feature.attributes.description+'</div>',
        null,
        true,
        function() { controls['selector'].unselectAll(); }
    );
    //feature.popup.closeOnMove = true;
    map.addPopup(feature.popup);
}
/* remover the popup */
function destroyPopup(feature) {
    feature.popup.destroy();
    feature.popup = null;
}

map.addControl(controls['selector']);
controls['selector'].activate();