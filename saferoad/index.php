<?php
require 'modele.php';
require 'verifCookie.php';
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css">
    <script src="http://code.jquery.com/jquery-1.11.2.min.js"></script>
    <script src="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>
    <script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>
    <script src="./jquery.md5.js"></script>
    <style type="text/css">
        #credibPlus
        {
            background-color:#5cb85c  ;
            color:white;
            font-family: "Agency FB", Calibri, "Californian FB", serif;
        }
        #credibMoins
        {
           background-color:#d9534f  ;
           color:white;
           font-family: "Agency FB", Calibri, "Californian FB", serif;

        }
        #map
        {
            width:100%;
            min-height: 500px;
            height: 100%;
            margin: 0;
        }

        .ui-btn
        {
            text-align: center;
        }
    </style>
    <script type="text/javascript">

        /////////////////////////////VARIABLE GLOBALE////////////////////////////////////////////////////////
        var init;
        var map;
        var pos;
        var bAjoutIncident = 0;
        //AU CHARGEMENT DE LA PAGE
        $(document).ready(function($)
        {
            // --------------------------- Requête Ajax permettant de vérifier si l'utilisateur existe ---------------------------------------------------
            function verifUser() {
                mdp = $.md5($('.connectPassword').val());
                login = $('.connectLogin').val();

                if ($('.checkboxMemoriser').is(":checked"))
                {
                    $mem = 1;
                }
                else
                {
                    $mem = 0;
                }
                $.ajax({
                    type: 'POST',
                    url: './ajax/verifUser.php',
                    data: {l: login, m: mdp ,mem:$mem},
                    success: function (data) {
                        if(data != 0)
                        {
                            chargeParam();
                            window.location.hash = '#pageConnecte' ;
                        }

                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        alert(errorThrown);
                    }
                });
            }
            // --------------------------- Requête ajax mettant a jour les param utilisateurs ---------------------------------------------------

            function changeParam()
            {
                var notif       = 0;
                var idUser      = <?php  echo (isset($_SESSION['idUser']))? intval($_SESSION['idUser']) : 0; ?> ;
                var distance    = $('.paramDistance').val();
                var credibilite = $('.paramCredibilite').val();
                if ($('.paramNotif').is(":checked")){
                    notif = 1 //la case est bien cochée
                }
                $.ajax({
                    type: 'POST',
                    url:  './ajax/changeParam.php',
                    data:{id: idUser, dist: distance, cred: credibilite, not: notif},
                    success: function(data){
                        chargeParam();
                        recuperePos(0);
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        alert(errorThrown);
                    }
                });
            }
            // --------------------------- /Requête ajax mettant a jour les param utilisateurs ---------------------------------------------------

            // --------------------------- Requête ajax recupérant les param utilisateurs ---------------------------------------------------

            function chargeParam()
            {
                $.ajax({
                    type: 'GET',
                    url:  './ajax/chargeParam.php',
                    data:{},
                    success: function(data) {
                        //chaque argument d'incident
                        var param = data.split(",");
                        var enableNotif = param[0];
                        var seuilCredibMax = param[1];
                        var seuilDistanceMax = param[2];
                        $('#pageConnecte').page();
                        $('.paramDistance').val(seuilDistanceMax);
                        $('.paramDistance').slider('refresh');
                        $('.paramCredibilite').val(seuilCredibMax);
                        $('.paramCredibilite').slider('refresh');
                        if (enableNotif == 1)
                        // To check
                        {
                            $('.ui-flipswitch').toggleClass('ui-flipswitch-active')
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        alert(errorThrown);
                    }
                });
            }
            // --------------------------- /Requête ajax recupérant les param utilisateurs ---------------------------------------------------

            // --------------------------- Recupération formulaire de connexion ---------------------------------------------------

            $('#formConnect').submit(function () {
                verifUser();
                return false;
            });
            // --------------------------- /Recupération formulaire de connexion ---------------------------------------------------

            $('#formParam').submit(function () {
                changeParam();
                recuperePos(1);
                return false;
            });
            // --------------------------- /Recupération formulaire de param ---------------------------------------------------

        });
        // --------------------------- Requête ajax recupérant tous les incidents  ---------------------------------------------------

        function recupereIncidents(latitude, longitude) {
            $.ajax({
                type: 'POST',
                url: './ajax/recupereIncidents.php',
                data: {lat: latitude, lng: longitude},
                success: function (data, textStatus, jqXHR) {
                    //Chaque incident
                    var incident = data.split("/");
                    for (var i = incident.length - 1; i >= 0; i--) {
                        //chaque argument d'incident
                        var param = incident[i].split(",");
                        var description = param[0];
                        var lat = param[1];
                        var lng = param[2];
                        var idType = param[3];
                        var nomType = param[4];
                        var idIncident = param[5];
                        var notation = param[6];
                        if (lat != null) {
                            ajoutMarqueur(description, lat, lng, idType,nomType, idIncident, notation);
                            if (typeof param[8] == 'undefined')
                            {
                                insereNotification(idIncident, nomType, description);
                            }
                        }
                    }
                    ;

                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert(errorThrown);
                }
            });
        }
        // --------------------------- /Requête ajax recupérant tous les incidents  ---------------------------------------------------

        // --------------------------- fonction JS recupérant la lattitute et longitude  ---------------------------------------------------

        function recuperePos(bAjoutInc) {
            bAjoutIncident = bAjoutInc;
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    geo_ok,
                    geo_error,
                    {enableHighAccuracy: true, maximumAge: 5000, timeout: 10000}
                );
            } else {
                alert('Erreur : pas de support de la géolocalisation dans votre navigateur');
            }
        }
        // --------------------------- /fonction JS recupérant la lattitute et longitude  ---------------------------------------------------

        function geo_ok(position) {
            var latitude = position.coords.latitude;
            var longitude = position.coords.longitude;
            if (bAjoutIncident == 0) {
                Initialisation(latitude, longitude);
            }
            else {
                ajoutIncident(latitude, longitude);
            }

        }

        function geo_error(error) {
            alert(error.message + " / " + error.code);
        }
        // --------------------------- Initialisation de la carte  ---------------------------------------------------

        function Initialisation(latitude, longitude) {
            pos = new google.maps.LatLng(latitude, longitude);
            var myOptions = {
                zoom: 13,
                center: pos,
                draggable : false,
                disableDoubleClickZoom: true,
                keyboardShortcuts: false,
                overviewMapControl: false,
                rotateControl: false,
                scrollwheel: false,
                streetViewControl: false,
                zoomControl: false,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };
            map = new google.maps.Map(document.getElementById("map"), myOptions);
            ajoutMarqueur();
            recupereIncidents(latitude,longitude);
        }
        // --------------------------- /Initialisation de la carte  ---------------------------------------------------

        // --------------------------- Ajoute UN marqueur et affiche la description de l'incident sur le click  ---------------------------------------------------

        function ajoutMarqueur(desc, lat, lng, idType, libelleIncident, idIncident, notation ) {
            var urlImage;
            if (lat != null) {
                pos = new google.maps.LatLng(lat, lng);
            }

            if (idType == 1) {
                urlImage = "./images/iconeTravaux.png";
            }
            if (idType == 2) {
                urlImage = "./images/iconeRadar.png";
            }

            if (idType == 3) {
                urlImage = "./images/iconePolice.png";
            }
            if (idType == 4) {
                urlImage = "./images/iconeAccident.png";
            }
            if (urlImage != null) {
                var imageMarqueur = {
                    url: urlImage,
                    size: new google.maps.Size(25, 25),
                    anchor: new google.maps.Point(25, 25)
                };
                var optionsMarqueur = {
                    position: pos,
                    map: map,
                    icon: imageMarqueur
                };
            }
            else {
                var optionsMarqueur = {
                    position: pos,
                    map: map
                };
            }

            var marqueur = new google.maps.Marker(optionsMarqueur);
            if (idType == null)
            {
                var contenuInfoBulle = 'Votre position';
            }
            else
            {
                var contenuInfoBulle = "<h2>" + libelleIncident + "</h2> <p> Points: " + notation + "</p><br>" + desc + "<br>"
                    + "<a onclick='noter(1," + idIncident + ")' href='#' id='credibPlus' class='ui-btn'>Créditer</a>"
                    + "<a onclick='noter(0," + idIncident + ")' href='#' id='credibMoins' class='ui-btn'>Décréditer</a>"
            }

            var infoBulle = new google.maps.InfoWindow({
                content: contenuInfoBulle
            })
            google.maps.event.addListener(marqueur, 'click', function () {
                infoBulle.open(map, marqueur);
            });
        }

        // --------------------------- /Ajoute UN marqueur et affiche la description de l'incident sur le click  ---------------------------------------------------

        // --------------------------- Requête Ajax permettant de vérifier si le login n'est pas utlisé et l'inscrit dans la base  ---------------------------------------------------

        		function createUser(login)
        		{
                    var mdp = document.forms['inscription'].newMdp.value;
        			$.ajax
        			({
        				type: 'POST',
            				url:'./ajax/createUser.php',
            				data:{ l:login ,pass: mdp},
            				success: function(data, textStatus, jqXHR)
        				{
        					if(data != "")
            					{
            						alert(data);
        					}
        				},
        				error: function(jqXHR, textStatus, errorThrown)
        				{
        					alert(errorThrown);
        				}
        			});
        		}
        // --------------------------- /Requête Ajax permettant de vérifier si le login n'est pas utlisé et l'inscrit dans la base  ---------------------------------------------------


        // --------------------------- requête ajax permettant d'inserer les notifications  ---------------------------------------------------

        function insereNotification(idNotification, typeIncident, descriptionIncident) {
            var idJQNotif = '#notif_' + idNotification;

            if ($(idJQNotif).length <= 0) {

                $('#notif_0').before('<div class = \"notification\" data-role = \"collapsible" id = \"notif_' + idNotification + '\">' +
                '<h2> ' + typeIncident + ' </h2>' +
                '<p> ' + descriptionIncident + ' </p>' +
                '<button class=\"ui-btn ui-btn-inline ui-mini\" onclick=\"masquerIncident('+  idNotification+ '  )\">Masquer</button>' +
                '</div>');

                $('#main').collapsibleset();
            }
        }
        // --------------------------- /requête ajax permettant d'inserer les notifications  ---------------------------------------------------

        // --------------------------- requête ajax permettant de masquer les notifications  ---------------------------------------------------

        function masquerIncident(idIncident)
        {
            $('#notif_' + idIncident ).hide()
            $.ajax({
                type: 'POST',
                url: './ajax/masquerIncident.php',
                data: {idInc: idIncident },
                success: function (data, textStatus, jqXHR) {
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert(errorThrown);
                }
            });
        }
        // --------------------------- /requête ajax permettant de masquer les notifications  ---------------------------------------------------

        // --------------------------- requête ajax permettant d'ajouter un nouvel incident ---------------------------------------------------


        function ajoutIncident(lat, lng) {
            var description = document.forms['incident'].desc.value;
            var idType = document.forms['incident'].typeIncident.value;
            $.ajax({
                type: 'GET',
                url: './ajax/ajoutIncident.php',
                data: '&d=' + description + '&t=' + idType + '&lat=' + lat + '&lng=' + lng,
                success: function (data, textStatus, jqXHR) {
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert(errorThrown);
                }
            });
        }
        // --------------------------- /requête ajax permettant d'ajouter un nouvel incident ---------------------------------------------------

        // ---------------------------requête ajax permettant d'ajouter un nouvel incident ---------------------------------------------------

        function noter(ajout, idIncident) {
            var idUser = <?php  echo (isset($_SESSION['idUser']))? intval($_SESSION['idUser']) : 0; ?> ;
             $.ajax({
                type: 'POST',
                url: './ajax/noter.php',
                data: {a:ajout, idU:idUser, idI:idIncident},
                success: function (data, textStatus, jqXHR) {
                    //Opération échoué
                    if (data != 1)
                    {
                        alert(data);
                    }
                    else
                    {
                        alert("Merci d'avoir noté cet incident");
                    }
                    //TODO
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert(errorThrown);
                }
            });
        }
        // ---------------------------/requête ajax permettant d'ajouter un nouvel incident ---------------------------------------------------

        $(document).on('pageshow', '#pageConnecte', function(e, data){
            recuperePos(0);
        });
    </script>

</head>
<body>


<div id="main" role="main" data-role="content" class="ui-content">
    <?php

    $LoginMdp   = verifCookie();
    if ($LoginMdp != null)
    {
        $tabLoginMdp = explode($LoginMdp,"/");
        $Login      = $tabLoginMdp[0];
        $Mdp        = $tabLoginMdp[1];
    }


     if (!isset($_SESSION['id'])){?>
        <script>document.location.hash = 'pageNonConnecte';</script>
    <?php }
    else{?>
        <script>chargeParam();</script>
    <?php } ?>

    <!-- -------------------------------------------------------------PAGE CONNECTE ---------------------------------------------------->


    <div data-role="page" id="pageConnecte">
        <!-- ------------------------------------------------------------- PANEL SIGNALER INCIDENT ---------------------------------------------------->

        <div data-role="panel" id="signaler" data-position="right">
            <h2>Signaler un incident</h2>
            <form name = "incident" >
                <select name="typeIncident" id="typeIncident">
                    <optgroup label="Type d'incident">
                        <?php

                        /* modif Geoffrey */
                        $listeIncidents = array();
                        $listeIncidents = getTypesIncident();
                        foreach ($listeIncidents as $ligne) {
                            echo '<option value="' . $ligne['idType'] . '">' . $ligne['nomType'] . '</option>';
                            # code...
                        }
                        ?>
                    </optgroup>
                </select>
<!--                <script type="text/javascript">-->
<!--                    setInterval("recuperePos(0)", 10000);-->
<!--                    setInterval("recuperePos(1)", 11000);-->
<!--                </script>-->
                <label for="info">description</label>
                <textarea name="desc" id="description" style="height:40%"></textarea>
            </form>
            <a href="#myPopup" data-rel="popup" onclick = "recuperePos(1);"  class="ui-btn ui-corner-all ui-shadow" style="margin: auto; ">Envoyer</a>
            <a href="#pageConnecte" class="ui-btn ui-corner-all ui-shadow" data-rel="close" style="margin: auto; margin-top: 50px;">retour</a>
        </div>
        <!-- ------------------------------------------------------------- /PANEL SIGNALER INCIDENT ---------------------------------------------------->

        <!-- ------------------------------------------------------------- PANEL PARAM ---------------------------------------------------->

        <div data-role="panel" id="param">
            <h2>Modifier les paramètres utilisateurs</h2>
            <form id = formParam  method="POST">
                <p>Seuil de crédibilité minimum</p>
                <input class="paramCredibilite" type="range" name="points" id="cred" value="20" min="-20" max="100" data-show-value="true">
                <p>Seuil de distance maximum des incidents </p>
                <input class="paramDistance" type="range" name="Km" id="km" value="20" min="5" max="100" data-show-value="true">
                <p>Notifications </p>
                <input class="paramNotif" type="checkbox" data-role="flipswitch" name="switch" id="switch">
                <input type="submit" data-inline="true" name="param" value="Sauvegarder">
            </form>
            <a href="#pageConnecte" class="ui-btn ui-btn-inline ui-corner-all ui-shadow"  data-inline="true" data-rel="close" style="margin: auto; ">retour</a>
        </div>
        <!-- ------------------------------------------------------------- /PANEL PARAM---------------------------------------------------->

        <div data-role="popup" id="myPopup">
            <p>Alerte ajoutée</p>
        </div>

        <div id="main" role="main" data-role="content" class="ui-content">
            <p>SafeRoad<p>
            <p>Parce que nous qu'on aime bien les routes sures<p>

            <div class = "notification" data-role = "collapsible" id = "notif_0" hidden>
                <h2> vide </h2>
                <p> vide </p>
                <button class="ui-btn ui-btn-inline ui-mini" onclick="$('#notif_0').hide()">Masquer</button>
            </div>

            <div id="map"></div>
            <!-- ------------------------------------------------------------- FOOTER ---------------------------------------------------->
            <div data-role="footer" data-position="fixed"  data-tap-toggle="false"  data-visible-on-page-show="false">
                <div data-role="navbar">
                    <ul>
                        <li><a href="#param" data-role="button" data-icon="user">Paramètres</a></li>
                        <li><a href="#pageNonConnecte" data-role="button" data-icon="delete">Deconnexion</a></li>
                        <li><a href="#signaler" data-role="button" data-icon="location">Signaler incident</a></li>
                    </ul>
                </div>
            </div>
            <!-- ------------------------------------------------------------- /FOOTER ---------------------------------------------------->
        </div>
    </div>

    <!-- -------------------------------------------------------------/PAGE CONNECTE ---------------------------------------------------->

    <!-- -------------------------------------------------------------PAGE INSCRIPTION ---------------------------------------------------->
    			<div data-role="page" id="pageInscription">
        				<div data-role="content" class="ui-content">
            					<p>Inscription nouvel utilisateur</p>
            					<form name = "inscription">

                                        <!-- partie pseudo -->
                                        <label for="info">Entrez votre email, qui sera aussi votre login :</label>
                                        <input name="newLogin" id="newPseudo"></input>


                                        <!-- partie mot de passe -->
                                        <label for="info">Entrez votre mot de passe :</label>
                                        <input type="password" name="newMdp" id="newPassword">


                                        <button onclick = "createUser(document.forms['inscription'].newLogin.value)" class="ui-btn ui-icon-check ui-btn-icon-left">Valider</button>
                                    </form>
                            </div>
                    </div>
    <!-- -------------------------------------------------------------/PAGE INSCRIPTION ---------------------------------------------------->

    <!-- -------------------------------------------------------------/PAGE CONNECTE ---------------------------------------------------->

    <!-- page non connecté -->
    <div data-role="page" id="pageNonConnecte">
        <div data-role="content" class="ui-content">
            <p>SafeRoad<p>
            <form  id="formConnect" name="connexion" method="POST">
                <label for="info">Email:</label>
                <input name="login" class="connectLogin" required="required" id="login">

                <label for="info">Mot de passe:</label>
                <input type="password"  required="required"  class="connectPassword" name="mdp" id="password">

                <div class="ui-checkbox">
                    <label for="checkbox-enhanced" class="ui-btn ui-corner-all ui-btn-inherit ui-btn-icon-left ui-checkbox-off">Mémoriser les identifiants</label>
                    <input class="checkboxMemoriser"  type="checkbox" name="checkboxMemoriser" id="checkbox-enhanced" data-enhanced="true">
                </div>
                <input class="ui-btn ui-icon-check ui-icon-left" type="submit" name="connexion" value="Connexion">
            </form>
            <a href="#pageInscription" class="ui-btn" name="inscription" style="border-radius: .3125em; box-shadow: 0 2px 3px rgba(0,0,0,.15);">Inscription</a>
        </div>
    </div>
</body>
</html>
