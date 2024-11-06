<?php include 'src/header.php';
/* ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); */
?>
<title>Patron Table</title></head>
<body>
  <button class="home-button operation-button" style="width:70px; padding:0px;" onclick="window.location.href='index.php';">
    <img class="home-button-img" src="css/home.svg">
  </button>
  <button class="operation-button menu-active" onclick="window.location.href='display_patron.php';">Patrons</button>
  <button class="operation-button" onclick="window.location.href='display_musicians.php';">Musicians</button>
  <button class="operation-button" onclick="window.location.href='display_bands.php';">Bands</button>
  <button class="operation-button" onclick="window.location.href='display_venues.php';">Venues</button>
  <button class="operation-button" onclick="window.location.href='display_gigs.php';">Gigs</button>
  <div class="vl"></div>  
  <button class="operation-button" id="resetButton">Reset Data</button><br>
  <h1 class="button-title">Patrons</h1><button class="title-button" data-target="insert-modal" onClick="toggleModal(event)"><img src="css/insert.svg" class="button-image"> Insert New</button>
  <p class="description">
    A listing of all PATRON entries in the database, joined with the PATRON_GENRES relation which would be a multivalued attribute. Any tuple may be deleted or updated using the buttons to the right of the table.
    New entries can be added by clicking on the "Insert" button above, next to the heading. <br><br>
    The "genres" multiple select box is populated by a "SELECT DISTINCT p_genre FROM PATRON_GENRES" query, and also allows for adding new genres to the database. It has checks on the 
    frontend to prevent duplicate entries.
  </p><?php 
  // connect to database
  $link = mysqli_connect("localhost", "--------", "---------------------", "--------")
  or die('Could not connect ');

  // names for insert form
  $email=mysqli_real_escape_string($link, $_POST["email"]);
  $name=mysqli_real_escape_string($link, $_POST["name"]);
  $birthdate=$_POST["birthdate"];
  $profile_picture=$_POST["profile_picture_url"];
  $p_genres=$_POST["select-words-unique"];
  // names for update form
  $update_email=$_POST["update_email"];
  $update_name=mysqli_real_escape_string($link, $_POST["update_name"]);
  $update_birthdate=$_POST["update_birthdate"];
  $update_profile_picture=$_POST["update_profile_picture_url"];
  $update_p_genres=$_POST["update_select-words-unique"];

  //use trim function to strip whitespace inadvertently entered 
  $email= trim($email);
  $name= trim($name);
  $birthdate= date('Y-m-d', strtotime($birthdate));

  $update_email= trim($update_email);
  $update_name= trim($update_name);
  $update_birthdate= date('Y-m-d', strtotime($update_birthdate));

  if (($email)) {
    echo '<h4>Inserted Tuple:</h4>';
    echo nl2br("--------------------------------------------------------------\n");
    echo nl2br("<b>Email (unique):</b> $email\n");
    echo nl2br("<b>Name:</b> $name\n");
    echo nl2br("<b>Birthdate:</b> $birthdate\n");
    echo nl2br("<b>Picture URL:</b> $profile_picture\n");
    if($p_genres) {
      echo nl2br("<b>Genres:</b> " . implode (", ", $p_genres) . "\n");
    } else {
      echo nl2br("<b>Genres:</b> none\n");
    }
    echo nl2br("--------------------------------------------------------------\n");
    // insert new data into table
    mysqli_report(MYSQLI_REPORT_ERROR); 
    $result = mysqli_query($link,"INSERT INTO PATRON (p_email, p_name, p_birthdate, profile_picture) values ('$email','$name','$birthdate', '$profile_picture')" );	 
    if ($result === false) {
      echo "<script>alert('INSERT operation failed. Perhaps you used a non-unique email address?');</script>";
    } 
    //INSERT NEW P_GENRE VALUES INTO DATABASE
    if ($p_genres) {
      foreach ($p_genres as $p_genre) {
        $p_genre = mysqli_real_escape_string($link, $p_genre);
        mysqli_query($link, "INSERT INTO PATRON_GENRES (p_email, p_genre) values ('$email', '$p_genre')");
      }
    }

  } elseif (($update_email)) {
    echo '<h4>Updated Tuple:</h4>';
    echo nl2br("--------------------------------------------------------------\n");
    echo nl2br("<b>Email (unique):</b> $update_email\n");
    echo nl2br("<b>Name:</b> $update_name\n");
    echo nl2br("<b>Birthdate:</b> $update_birthdate\n");
    echo nl2br("<b>Picture URL:</b> $update_profile_picture\n");
    if (is_array($update_p_genres)) {
      echo nl2br("<b>Genres:</b> " . implode (", ", $update_p_genres) . "\n");
    } else {
      echo nl2br("<b>Genres:</b> none\n");
    }
    
    echo nl2br("--------------------------------------------------------------\n");
    // insert new data into table
    if (($update_profile_picture)) {
      $result = mysqli_query($link, "UPDATE PATRON SET p_name='$update_name', p_birthdate='$update_birthdate', profile_picture='$update_profile_picture' WHERE p_email='$update_email'");
    } else {
      $result = mysqli_query($link, "UPDATE PATRON SET p_name='$update_name', p_birthdate='$update_birthdate' WHERE p_email='$update_email'");
    } if ($result === false) {
      echo "<script>alert('UPDATE operation failed.');</script>";
    } 
    if ($update_p_genres) {
      mysqli_query($link, "DELETE FROM PATRON_GENRES WHERE p_email='$update_email'");
      foreach ($update_p_genres as $p_genre) {
        $p_genre = mysqli_real_escape_string($link, $p_genre);
        mysqli_query($link, "REPLACE INTO PATRON_GENRES (p_email, p_genre) VALUES ('$update_email', '$p_genre')");
      }
    }
  }

  // query table to show new data inserted
  $query = "SELECT p.*, GROUP_CONCAT(pg.p_genre SEPARATOR ', ') AS patron_genres
            FROM PATRON p
            LEFT JOIN PATRON_GENRES pg ON p.p_email = pg.p_email
            GROUP BY p.p_email";
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
  echo "<th>Age</th>";
  echo "<th>Photo</th>";
  echo "<th>Genre Preferences</th>";
  echo "<th>Update</th>";
  echo "<th>Delete</th>";
  echo "</tr></thead>";
  // show results of table with new data inserted
  echo "<tbody>";
  $i = 0;
  while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
    if ($row['p_email'] === $email || $row['p_email'] === $update_email) {
      echo "<tr class='activeRow'>";
    } else {
      echo "<tr>";
    }	 ?>
    <td><?php echo $row['p_email']; ?></td>
    <td><?php echo $row['p_name']; ?></td> <?php
      $bday = new DateTime($row['p_birthdate']); // Your date of birth
      $today = new Datetime(date('y-m-d'));
      $diff = $today->diff($bday); ?>
    <td data-age="<?php echo $row['p_birthdate']; ?>"><?php echo $diff->y; ?></td>
    <td>
      <?php if ($row['profile_picture']) { ?>
        <img src="<?php print_r($row['profile_picture']); ?>" width="105" height="105" class="profile-picture" loading="lazy">
      <?php } ?>
    </td>
    <td><?php echo $row['patron_genres']; ?></td>
    <td><button type="button" class="updateButton" data-target="update-modal" onClick="updateModal(event)" data-id="<?php echo $row['p_email']; ?>"><img src="css/update.svg" class="button-image"></button></td>
    <td><button type="button" class="deleteButton" data-id="<?php echo $row['p_email']; ?>"><img src="css/delete.svg" class="button-image"></button></td></tr>
    <?php $i++;
  } ?>
  </tbody></table>
  <?php 
    // GETTING THE LIST OF UNIQUE GENRES FROM THE DATABASE, TO POPULATE THE SELECTS IN THE INPUT AND UPDATE FORMS
    $result = mysqli_query($link, "SELECT DISTINCT p_genre FROM PATRON_GENRES"); 
    while($row = $result->fetch_row()) {
      $genres[]=$row[0];
    }
    $genres = array_map(function($genre) {
      return array('id' => strtolower($genre), 'title' => $genre);
    }, $genres);
  
  ?>
  <dialog id="insert-modal" style="width:700px;">
    <article>
      <header style="margin-bottom:30px">
        <a href="#close"
          aria-label="Close"
          class="close"
          data-target="insert-modal"
          onClick="toggleModal(event)">
        </a>
        <b>Insert New Patron</b>
      </header>       
      <form action="display_patron.php" id="insert_patron_form" method="POST" enctype="multipart/form-data">    
        <div class="grid">
          <label for="email">
            <span class='red'>*</span>Email (unique):<br />
            <input name="email" type="email" placeholder="example@email.com" required>
          </label>
          <label for="name">
            Name:
            <input name="name" type="text" placeholder="first last">
          </label>
        </div>
        <div class="grid">
          <label for="birthdate">
            Birthdate:
            <input name="birthdate" type="date" min="1900-01-01" class="date-input">
          </label>
          <label for="select-words-unique">
            Genres:
            <select id="select-words-unique" name="select-words-unique[]" multiple placeholder="+ add genres"></select>
          </label>
        </div>
        <div class="grid" style="justify-items:center;">
          <img id="insert-photo" style="display:none" width="135">
          <label for="profile_picture" style="vertical-align:bottom;" class="file-input">
            Profile Picture:
            <input type="file" name="profile_picture" id="profile_picture">
          </label>
        </div>
        <input type="hidden" name="profile_picture_url" id="profile_picture_url">
      </form>
      <footer style="margin-top:0px;">
        <button type="submit" id="submit_button" form="insert_patron_form" aria-disabled="false" aria-busy="false">Insert</button>
      </footer>
    </article>
  </dialog>
  
  <dialog id="update-modal">
    <article>
      <header style="margin-bottom:30px">
        <a href="#close"
          aria-label="Close"
          class="close"
          data-target="update-modal"
          onClick="toggleModal(event)">
        </a>
        <b>Update Patron</b>
      </header>       
      <form action="display_patron.php" id="update_patron_form" method="POST" enctype="multipart/form-data"> 
        <div class="grid">   
          <label for="update_email">
            <span class='red'>*</span>Email (readonly):<br />
            <input name="update_email" type="email" placeholder="example@email.com" readonly required style="color:gray">
          </label>
          <label for="update_name">
            Name:
            <input name="update_name" type="text">
          </label>
        </div>
        <div class="grid">
          <label for="update_birthdate">
            Birthdate:
            <input name="update_birthdate" type="date" min="1900-01-01" class="date-input">
          </label>
          <label for="update_select-words-unique">
            Genres:
            <select id="update_select-words-unique" name="update_select-words-unique[]" multiple placeholder="+ add genres" style="min-height:40px"></select>
          </label>
        </div>
        <div class="grid" style="justify-items:center;">
          <img id="profile_picture_current" width="135" loading="lazy">
          <label for="update_profile_picture" class="file-input">
            Profile Picture:
            <input type="file" name="update_profile_picture" id="update_profile_picture">
          </label>
        </div>
        <input type="hidden" name="update_profile_picture_url" id="update_profile_picture_url">
      </form>
      <footer style="margin-top:0px;">
        <button type="submit" form="update_patron_form" id="update_submit_button" aria-disabled="false" aria-busy="false" >Update</button>
      </footer>
    </article>
  </dialog>

  <?php
  //Free result set
  mysqli_free_result($result);
  //close connection
  mysqli_close($link); ?>

  <script>
    //Tom Select js
    var unique = new TomSelect('#select-words-unique',{
      create: true,
      maxItems: null,
      valueField: 'id',
      labelField: 'title',
      searchField: 'title',
      options: <?php echo json_encode($genres); ?>,
      highlight: true,
      hidePlaceholder: true,
      plugins: {
        remove_button:{
          title:'Remove this item',
        }
      },
      createFilter: function(input) {
        input = input.toLowerCase();
        return !(input in this.options);
      }
    });
    //TOM SELECT
    var update_unique = new TomSelect('#update_select-words-unique',{
      create: true,
      maxItems: null,
      valueField: 'id',
      labelField: 'title',
      searchField: 'title',
      options: <?php echo json_encode($genres); ?>,
      items: [],
      highlight: true,
      hidePlaceholder: true,
      plugins: {
        remove_button:{
          title:'Remove this item',
        }
      },
      createFilter: function(input) {
        input = input.toLowerCase();
        return !(input in this.options);
      }
    });

    //gets all the deletebuttons, adds event listeners for clicks
    const deleteButtons = document.querySelectorAll('.deleteButton');
    deleteButtons.forEach(button => {
      button.addEventListener('click', function() {
        //get the primary key for extermination
        const deleteEmail = this.getAttribute('data-id');
        //get the HTML table row where the entry is located, for later removal
        const row = this.closest('tr');
        const xhr = new XMLHttpRequest();
        //run the backend code
        xhr.open('POST', 'delete_patron.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.send(`deleteEmail=${deleteEmail}`);
        //remove the row once the delete request is sent
        row.remove();
      });
    });

    const updateModal = (event) => {
      event.preventDefault();
      const row = event.target.closest('tr');;
      const modal = document.getElementById(event.currentTarget.getAttribute("data-target"));

      // get the values of the cells in the clicked row
      const email = row.cells[0].textContent;
      const name = row.cells[1].textContent;
      const birthdate = row.cells[2].getAttribute('data-age');
      if (row.cells[3].children.src != "") {
        const url = row.cells[3].children[0].src;
        modal.querySelector('img[id="profile_picture_current"').src = url;
      }
      const current_genres = row.cells[4].textContent.toLowerCase().split(', ');

      // populate the form fields with the values from the clicked row
      modal.querySelector('input[name="update_email"]').value = email;
      modal.querySelector('input[name="update_name"]').value = name;
      modal.querySelector('input[name="update_birthdate"]').value = birthdate;
      update_unique.clear();
      update_unique.addItems(current_genres);
      
      openModal(modal);
    }

    //LOGIC FOR THE UPDATE FORM
    let isImageUploaded = true;
    // event listener for the form's submit button
    const update_input = document.getElementById('update_profile_picture');
    update_input.addEventListener('change', () => {
      isImageUploaded = false;
      document.getElementById('update_submit_button').setAttribute('aria-disabled', true);
      document.getElementById('update_submit_button').setAttribute('aria-busy', true);
      const file = update_input.files[0];
      const formData = new FormData();
      formData.append('file', file);
      formData.append('upload_preset', 'gj4yeadt');
      const xhr = new XMLHttpRequest();
      xhr.open('POST', 'https://api.cloudinary.com/v1_1/damsyjmve/image/upload');
      xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
          try {
            const response = JSON.parse(xhr.responseText);
            const imageUrl = response.secure_url;
            document.getElementById('update_profile_picture_url').value = imageUrl;
            isImageUploaded = true;
            document.getElementById('update_submit_button').setAttribute('aria-disabled', false);
            document.getElementById('update_submit_button').setAttribute('aria-busy', false);
            document.getElementById('profile_picture_current').setAttribute('src', imageUrl);
          } catch (error) {
            console.log('Error parsing JSON response', error);
          }
        }
      };
      xhr.send(formData);
    });
    document.getElementById('update_submit_button').addEventListener('click', function(event) {
      // prevent the form from being submitted if the image hasn't been uploaded yet
      if (!isImageUploaded) {
        event.preventDefault();
      }
    });

    //LOGIC FOR THE INSERT FORM
    isImageUploaded = true;
    // event listener for the form's submit button
    const input = document.getElementById('profile_picture');
    input.addEventListener('change', () => {
      isImageUploaded = false;
      document.getElementById('submit_button').setAttribute('aria-disabled', true);
      document.getElementById('submit_button').setAttribute('aria-busy', true);
      const file = input.files[0];
      const formData = new FormData();
      formData.append('file', file);
      formData.append('upload_preset', 'gj4yeadt');
      const xhr = new XMLHttpRequest();
      xhr.open('POST', 'https://api.cloudinary.com/v1_1/damsyjmve/image/upload');
      xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
          try {
            const response = JSON.parse(xhr.responseText);
            const imageUrl = response.secure_url;
            document.getElementById('profile_picture_url').value = imageUrl;
            isImageUploaded = true;
            document.getElementById('insert-photo').setAttribute('src', imageUrl);
            document.getElementById('insert-photo').setAttribute('style', "display:inline-block");
            document.getElementById('submit_button').setAttribute('aria-disabled', false);
            document.getElementById('submit_button').setAttribute('aria-busy', false);
          } catch (error) {
            console.log('Error parsing JSON response', error);
          }
        }
      };
      xhr.send(formData);
    });

    // event listener for the form's submit button
    document.getElementById('submit_button').addEventListener('click', function(event) {
      // prevent the form from being submitted if the image hasn't been uploaded yet
      if (!isImageUploaded) {
        event.preventDefault();
      }
    });

  </script>
</body>
</html>

<?php include 'src/scripts.php'; ?>
