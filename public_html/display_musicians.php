<?php include 'src/header.php'; ?>
<title>Musician Table</title></head>
<body>
  <button class="home-button operation-button" style="width:70px; padding:0px;" onclick="window.location.href='index.php';">
    <img class="home-button-img" src="css/home.svg">
  </button>
  <button class="operation-button" onclick="window.location.href='display_patron.php';">Patrons</button>
  <button class="operation-button menu-active" onclick="window.location.href='display_musicians.php';">Musicians</button>
  <button class="operation-button" onclick="window.location.href='display_bands.php';">Bands</button>
  <button class="operation-button" onclick="window.location.href='display_venues.php';">Venues</button>
  <button class="operation-button" onclick="window.location.href='display_gigs.php';">Gigs</button> 
  <div class="vl"></div>  
  <button class="operation-button" id="resetButton">Reset Data</button><br>
  <h1 class="button-title">Musicians</h1> 
  <p class="description">
    A listing of all MUSICIAN entries in the database, joined with the M_GENRES and M_INSTRUMENTS relations which would be multivalued attributes.
  </p><?php
  $link = mysqli_connect("localhost", "--------", "---------------------", "--------")
  or die('Could not connect ');

  // query table to show new data inserted
  $query = "SELECT m.m_name AS 'Name', GROUP_CONCAT(DISTINCT mi.m_instrument SEPARATOR ', ') AS 'Talents', GROUP_CONCAT(DISTINCT mg.m_genre SEPARATOR ', ') AS 'Genres' 
            FROM MUSICIAN m 
            LEFT JOIN M_INSTRUMENTS mi ON m.m_email = mi.m_email 
            LEFT JOIN M_GENRES mg ON m.m_email = mg.m_email 
            GROUP BY m.m_email, m.m_name;";

  $result = mysqli_query($link, $query)  
    or die("Query failed");
  $num_results = mysqli_num_rows($result);
  echo '<p><b>Number of rows in table:</b> '.$num_results.'</p>';

  //print column headings
  echo "<table role='grid'>";
  echo "<thead>";
  echo "<tr class='headRow'>";
  while ($fieldinfo = $result -> fetch_field()) {
    echo "<th>$fieldinfo->name</th>";
  }
  echo "</tr></thead>";
  // show results of table with new data inserted
  echo "<tbody>";
  $i = 0;
  while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
    echo "<tr>"; ?>
    <td><?php echo $row['Name']; ?></td>
    <td><?php echo $row['Talents']; ?></td>
    <td><?php echo $row['Genres']; ?></td>
    <?php $i++;
  }
  echo "</tbody></table>"; ?>

<?php include 'src/scripts.php'; ?>
