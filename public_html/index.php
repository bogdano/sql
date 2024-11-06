<?php include 'src/header.php';?>
<title>giggle</title></head>
<body>
  <button class="home-button operation-button menu-active" style="width:70px; padding:0px;" onclick="window.location.href='index.php';">
    <img class="home-button-img" src="css/home.svg" style="">
  </button>
  <button class="operation-button" onclick="window.location.href='display_patron.php';">Patrons</button>
  <button class="operation-button" onclick="window.location.href='display_musicians.php';">Musicians</button>
  <button class="operation-button" onclick="window.location.href='display_bands.php';">Bands</button>
  <button class="operation-button" onclick="window.location.href='display_venues.php';">Venues</button>
  <button class="operation-button" onclick="window.location.href='display_gigs.php';">Gigs</button> 
  <div class="vl"></div>  
  <button class="operation-button" id="resetButton">Reset Data</button><br>
  <h1 class="button-title">giggle</h1> 
  <div class="grid">
    <p><br>
      This is a database for a hypothetical app which is intended for discovering local live music events.<br><br>
      The main relations in the database represent the types of users it is intended for, namely: PATRONS looking to go to gigs, VENUES looking to host gigs,
      BANDS looking for places to perform or musicians to recruit, MUSICIANS looking to join a band or jam with new people, and GIGS looking for an audience. <br><br>
      The relations which are beyond the scope of this part of the project and are not represented on this website pertain to mutual reviews and ratings between all of these entity types. <br><br>
      Go ahead and click on any of the links above!
    </p>
    <div style="display:float;width:100%"><img src="css/music.svg" width="400px" style="float:right; margin-top:100px;margin-right:100px;"></div>
  </div>
<?php include 'src/scripts.php'; ?>