<?php include 'src/header.php'; 
/* ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); */
?>
<title>Gig Table</title></head>
<body>
  <button class="home-button operation-button" style="width:70px; padding:0px;" onclick="window.location.href='index.php';">
    <img class="home-button-img" src="css/home.svg">
  </button>
  <button class="operation-button" onclick="window.location.href='display_patron.php';">Patrons</button>
  <button class="operation-button" onclick="window.location.href='display_musicians.php';">Musicians</button>
  <button class="operation-button" onclick="window.location.href='display_bands.php';">Bands</button>
  <button class="operation-button" onclick="window.location.href='display_venues.php';">Venues</button>
  <button class="operation-button menu-active" onclick="window.location.href='display_gigs.php';">Gigs</button> 
  <div class="vl"></div>  
  <button class="operation-button" id="resetButton">Reset Data</button><br>
  <h1 class="button-title">Gigs</h1> 
  <p class="description">
    The GIG relation uses a composite key comprised of "v_email", "b_email" and "gig_date". It is displayed here with 2 regular JOINs with the BAND and VENUE 
    tables in order to display band and venue names as opposed to email addresses, and with a LEFT JOIN on the BAND_GENRES table in order to display the 
    genres of the gig in the Genres column. Sorted by date, ASC. <br><br>
    The toggles below are dynamically generated based off of a "SELECT bg.b_genre, COUNT(*) AS count 
                                 FROM GIG g 
                                 JOIN BAND_GENRES bg ON g.b_email = bg.b_email 
                                 GROUP BY bg.b_genre 
                                 ORDER BY count DESC" query, and when selected modify the query displayed in the table below.
    The table is immediately updated with the results of the query with an AJAX request.
  </p><?php
  $link = mysqli_connect("localhost", "--------", "---------------------", "--------")
  or die('Could not connect ');

  $genres = mysqli_query($link, "SELECT bg.b_genre, COUNT(*) AS count 
                                 FROM GIG g 
                                 JOIN BAND_GENRES bg ON g.b_email = bg.b_email 
                                 GROUP BY bg.b_genre 
                                 ORDER BY count DESC"); ?>
  <div id='selected_genres'> <?php
    foreach ($genres as $row) {
      $genre = $row['b_genre'];
      $count = $row['count'];
      
      echo '<div id="genre-wrapper"><input type="checkbox" id="' . $genre . '-checkbox" class="genre_checkbox" data-genre="' . $genre . '">';
      echo '<label for="' . $genre . '-checkbox" class="genre_checkbox_label"><span>' . $genre . ' (' . $count . ')' . '</span></label></div>';
    } ?>
    <button class="updateButton" style="display:inline-block; padding-bottom:2px;" id="sweep-filters"><img src="css/sweep.svg" class="button-image"></button>
  </div>

  <div id="gig-table" style="min-height:700px;"></div>

  <script>
    // Get all checkboxes
    var checkboxes = document.querySelectorAll('.genre_checkbox');
    // Add event listener to each checkbox
    checkboxes.forEach(function(checkbox) {
      checkbox.addEventListener('change', function() {
        // Save state of checkbox in local storage
        localStorage.setItem(checkbox.id, checkbox.checked);
        loadTableData();
      });
    });
    // Read local storage and set checkbox state on page load
    document.addEventListener('DOMContentLoaded', function() {
      checkboxes.forEach(function(checkbox) {
        var value = localStorage.getItem(checkbox.id);
        if (value !== null) {
          checkbox.checked = (value === 'true');
        }
      });
      loadTableData();
    });

    const getSelectedGenres = () => {
      const selectedGenres = [];
      const checkboxes = document.querySelectorAll(".genre_checkbox");
      checkboxes.forEach((checkbox) => {
        if (checkbox.checked) {
          selectedGenres.push(checkbox.dataset.genre);
        }
      });
      return selectedGenres;
    };

    //LOAD DATA FROM DB
    const loadTableData = () => {
      const xhr = new XMLHttpRequest();
      const selectedGenres = getSelectedGenres();
      xhr.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
          const table = document.getElementById("gig-table");
          table.innerHTML = this.responseText;
        }
      };
      xhr.open("GET", "update_gigs_query.php?selected_genres=" + encodeURIComponent(JSON.stringify(selectedGenres)), true);
      xhr.send();
    };

    //sweep filters clear
    sweep = document.getElementById("sweep-filters").addEventListener('click', function() {
      const checkboxes = document.querySelectorAll(".genre_checkbox");
      checkboxes.forEach((checkbox) => {
        checkbox.checked = false;
        localStorage.setItem(checkbox.id, checkbox.checked);
      });
      loadTableData();
    });
  </script>

<?php include 'src/scripts.php'; ?>
