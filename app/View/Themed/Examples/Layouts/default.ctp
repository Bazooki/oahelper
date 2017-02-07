<!DOCTYPE html>
<html lang="en">
<head>
        <?php echo $this->Html->charset();?>

        <title>
            <?php echo $this->fetch('title'); ?>
        </title>
        <meta name="generator" content="Bootply" />


    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <link rel="shortcut icon" href="favicon.png">


    <?php
    echo $this->Html->meta('icon');

    echo $this->Html->css('isotope');
    echo $this->Html->css('animate');
    echo $this->Html->css('bootstrap');
    echo $this->Html->css('bootstrap-theme');
    echo $this->Html->css('font-awesome');
    echo $this->Html->css('style');
    echo $this->Html->css('overwrite');

    echo $this->fetch('meta');
    echo $this->fetch('css');
    echo $this->fetch('script');

    ?>


    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>
<body>

<?php
    echo $this->fetch('content');
?>

<script src="/theme/examples/js/modernizr-2.6.2-respond-1.1.0.min.js"></script>
<script src="/theme/examples/js/jquery.js"></script>
<script src="/theme/examples/js/jquery.easing.1.3.js"></script>
<script src="/theme/examples/js/bootstrap.min.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyASm3CwaK9qtcZEWYa-iQwHaGi3gcosAJc&sensor=false"></script>
<script src="/theme/examples/js/jquery.isotope.min.js"></script>
<script src="/theme/examples/js/jquery.nicescroll.min.js"></script>
<script src="/theme/examples/js/fancybox/jquery.fancybox.pack.js"></script>
<script src="/theme/examples/js/skrollr.min.js"></script>
<script src="/theme/examples/js/jquery.scrollTo-1.4.3.1-min.js"></script>
<script src="/theme/examples/js/jquery.localscroll-1.2.7-min.js"></script>
<script src="/theme/examples/js/stellar.js"></script>
<script src="/theme/examples/js/jquery.appear.js"></script>
<script src="/theme/examples/js/validate.js"></script>
<script src="/theme/examples/js/main.js"></script>
<script type="text/javascript">
    // When the window has finished loading create our google map below
    google.maps.event.addDomListener(window, 'load', init);

    function init() {
        // Basic options for a simple Google Map
        // For more options see: https://developers.google.com/maps/documentation/javascript/reference#MapOptions
        var mapOptions = {
            // How zoomed in you want the map to start at (always required)
            zoom: 11,

            // The latitude and longitude to center the map (always required)
            center: new google.maps.LatLng(40.6700, -73.9400), // New York

            // How you would like to style the map.
            // This is where you would paste any style found on Snazzy Maps.
            styles: [	{		featureType:"all",		elementType:"all",		stylers:[		{			invert_lightness:true		},		{			saturation:10		},		{			lightness:30		},		{			gamma:0.5		},		{			hue:"#1C705B"		}		]	}	]
        };

        // Get the HTML DOM element that will contain your map
        // We are using a div with id="map" seen below in the <body>
        var mapElement = document.getElementById('map');

        // Create the Google Map using out element and options defined above
        var map = new google.maps.Map(mapElement, mapOptions);
    }
</script>
</body>

</html>