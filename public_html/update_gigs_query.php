<?php
/* ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); */
$link = mysqli_connect("localhost", "--------", "---------------------", "--------")
or die('Could not connect ');
// Get the selected genres from the AJAX request
$selected_genres = json_decode($_GET['selected_genres']);
// Build the WHERE clause based on the selected genres
$where_clause = '';
if (!empty($selected_genres)) {
  $s_genres = implode("', '", $selected_genres);
  $where_clause = "WHERE EXISTS (SELECT 1 FROM BAND_GENRES WHERE b_email = b.b_email AND b_genre IN ('$s_genres'))";
}
// Modify the SQL query to include the WHERE clause
$query = "SELECT b.b_name AS 'Band',
            v.v_name AS 'Venue',
            DATE_FORMAT(g.gig_date, '%m/%d') AS 'Date',
            TIME_FORMAT(g.gig_time, '%h:%i %p') AS 'Time',
            GROUP_CONCAT(bg.b_genre SEPARATOR ', ') AS 'Genres',
            g.price AS '$',
            g.overage AS '21+'
          FROM GIG g
          JOIN BAND b ON b.b_email = g.b_email
          JOIN VENUE v ON v.v_email = g.v_email
          LEFT JOIN BAND_GENRES bg ON bg.b_email = b.b_email
          $where_clause
          GROUP BY g.gig_date, g.v_email, g.b_email
          ORDER BY g.gig_date ASC";
          
$result = mysqli_query($link, $query)  
  or die("Query failed");

$num_results = mysqli_num_rows($result);
echo '<p style="margin-top:20px;"><b>Number of rows in table:</b> '.$num_results.'</p>';

//print column headings
echo "<table role='grid'>"; 
echo "<thead>";
echo "<tr class='headRow'>";
while ($fieldinfo = $result -> fetch_field()) {
  echo "<th>$fieldinfo->name</th>";
}
echo "</tr></thead>";
// show results of table with new data inserted
echo "<tbody >";
$i = 0;
while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
  echo "<tr>"; ?>
  <td><?php echo $row['Band']; ?></td>
  <td><?php echo $row['Venue']; ?></td>
  <td><?php echo $row['Date']; ?></td>
  <td><?php echo $row['Time']; ?></td>
  <td><?php echo $row['Genres']; ?></td>
  <td><?php echo '$' . $row['$']; ?></td>
  <td><?php echo ($row['21+'] == 1 ? 'yes' : ''); ?></td>
  <?php $i++;
}
echo "</tbody></table>"; ?>