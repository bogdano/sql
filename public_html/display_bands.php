<?php include 'src/header.php'; ?>
<title>Band Table</title></head>
<body>
  <button class="home-button operation-button" style="width:70px; padding:0px;" onclick="window.location.href='index.php';">
    <img class="home-button-img" src="css/home.svg">
  </button>
  <button class="operation-button" onclick="window.location.href='display_patron.php';">Patrons</button>
  <button class="operation-button" onclick="window.location.href='display_musicians.php';">Musicians</button>
  <button class="operation-button menu-active" onclick="window.location.href='display_bands.php';">Bands</button>
  <button class="operation-button" onclick="window.location.href='display_venues.php';">Venues</button>
  <button class="operation-button" onclick="window.location.href='display_gigs.php';">Gigs</button> 
  <div class="vl"></div>  
  <button class="operation-button" id="resetButton">Reset Data</button><br>
  <h1 class="button-title">Bands</h1>
  <p class="description">
    A listing of all BAND entries in the database, joined with the BAND_GENRES and BAND_SOC_MEDIA relations which would be multivalued attributes containing genres played by
    the band, and their social media links. It is also joined with the MANAGER table, showing which manager manages each band.
  </p><?php
  $link = mysqli_connect("localhost", "--------", "---------------------", "--------")
  or die('Could not connect ');

  // query table to show new data inserted
  $query = "SELECT b.b_name, m.mgr_name, GROUP_CONCAT(bg.b_genre SEPARATOR ', ') AS genres_played, bsm.soc_media 
            FROM BAND b
            LEFT JOIN (
                SELECT b_email, GROUP_CONCAT(DISTINCT soc_media SEPARATOR ', ') AS soc_media
                FROM BAND_SOC_MEDIA
                GROUP BY b_email
            ) bsm ON b.b_email = bsm.b_email
            LEFT JOIN MANAGER m ON b.mgr_email = m.mgr_email
            LEFT JOIN BAND_GENRES bg ON b.b_email = bg.b_email
            GROUP BY b.b_email;";

  $result = mysqli_query($link, $query)  
    or die("Query failed");
  $num_results = mysqli_num_rows($result);
  echo '<p><b>Number of rows in table:</b> '.$num_results.'</p>';

  //print column headings
  echo "<table role='grid'>";
  echo "<thead>";
  echo "<tr class='headRow'>";
 
  echo "<th>Band Name</th>";
  echo "<th>Manager</th>";
  echo "<th>Genres</th>";
  echo "<th>Social Media</th>";

  echo "</tr></thead>";
  // show results of table with new data inserted
  echo "<tbody>";
  $i = 0;
  while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
    echo "<tr>"; ?>
    <td><?php echo $row['b_name']; ?></td>
    <td><?php echo $row['mgr_name']; ?></td>
    <td><?php echo $row['genres_played']; ?></td>
    <td><?php 
      $links = explode(', ', $row['soc_media']);
      foreach ($links as $link) {
        if (str_contains($link, "facebook.com")) { 
          echo '<a href="' . $link . '" target="_blank"><img class="little-icon" src="css/facebook.svg"></a>';
        } elseif (str_contains($link, "twitter.com")) {
          echo '<a href="' . $link . '" target="_blank"><img class="little-icon" src="css/twitter.svg"></a>';
        } elseif (str_contains($link, "instagram.com")) {
          echo '<a href="' . $link . '" target="_blank"><img class="little-icon" src="css/instagram.svg"></a>';
        }
      } 
      ?>
    </td>

    <?php $i++;
  }
  echo "</tbody></table>"; ?>

<?php include 'src/scripts.php'; ?>
