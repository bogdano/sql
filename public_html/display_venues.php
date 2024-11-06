<?php include 'src/header.php'; ?>
<title>Venue Table</title></head>
<body>
  <button class="home-button operation-button" style="width:70px; padding:0px;" onclick="window.location.href='index.php';">
    <img class="home-button-img" src="css/home.svg">
  </button>
  <button class="operation-button" onclick="window.location.href='display_patron.php';">Patrons</button>
  <button class="operation-button" onclick="window.location.href='display_musicians.php';">Musicians</button>
  <button class="operation-button" onclick="window.location.href='display_bands.php';">Bands</button>
  <button class="operation-button menu-active" onclick="window.location.href='display_venues.php';">Venues</button>
  <button class="operation-button" onclick="window.location.href='display_gigs.php';">Gigs</button> 
  <div class="vl"></div>  
  <button class="operation-button" id="resetButton">Reset Data</button><br>
  <h1 class="button-title">Venues</h1> 
  <p class="description">
    This is a simple table, showing the complete contents of the VENUE relation.
  </p><?php
  $link = mysqli_connect("localhost", "--------", "---------------------", "--------")
  or die('Could not connect ');

  // query table to show new data inserted
  $query = "SELECT * FROM VENUE";

  $result = mysqli_query($link, $query)  
    or die("Query failed");
  $num_results = mysqli_num_rows($result);
  echo '<p><b>Number of rows in table:</b> '.$num_results.'</p>';

  //print column headings
  echo "<table role='grid'>";
  echo "<thead>";
  echo "<tr class='headRow'>";
  
  echo "<th>Email (key)</th>";
  echo "<th>Name</th>";
  echo "<th>Type</th>";
  echo "<th>Cap.</th>";
  echo "<th>$$$</th>";
  echo "<th>Address</th>";
  echo "<th>#</th>";
  echo "<th>Dance</th>";
  echo "<th>Hours</th>";
  
  echo "</tr></thead>";
  // show results of table with new data inserted
  echo "<tbody style='font-size:13pt;'>";
  $i = 0;
  while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
    echo "<tr>"; ?>
    <td><?php echo $row['v_email']; ?></td>
    <td><?php echo $row['v_name']; ?></td>
    <td><?php echo $row['v_type']; ?></td>
    <td><?php echo $row['capacity']; ?></td>
    <td><?php echo $row['price_range']; ?></td>
    <td><?php echo $row['v_address']; ?></td>
    <td><?php echo $row['v_number']; ?></td>
    <td><?php echo ($row['dancefloor'] == 1) ? 'yes' : ''; ?></td>
    <td><?php echo $row['hours']; ?></td>
    <?php $i++;
  }
  echo "</tbody></table>"; ?>

<?php include 'src/scripts.php'; ?>
