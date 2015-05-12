<script>
point = new google.maps.LatLng(47.5,49.4);
marker = new google.maps.Marker({ position:point , title:'mon point'});
marker.setMap(carte);
carte.panTo(point);
</script>