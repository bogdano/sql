</body>
<?php
echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/cloudinary-core/2.10.0/cloudinary-core-shrinkwrap.js"></script>
<script src="js/modal.js"></script>
<script src="js/minimal-theme-switcher.js"></script>';?>
<script>
  // Get the reset button element
  const resetButton = document.getElementById("resetButton");
  // Add a click event listener to the reset button
  resetButton.addEventListener("click", () => {
    // Send an AJAX request to the reset script
    const xhr = new XMLHttpRequest();
    xhr.open("GET", "reset_database.php");
    xhr.send();
    
    // Reload the page after the request is complete
    xhr.addEventListener("load", () => {
      location.reload();
    });
  });

  //prevent form resubmission on page reloads
  if ( window.history.replaceState ) {
    window.history.replaceState( null, null, window.location.href );
  }
</script>
</html>