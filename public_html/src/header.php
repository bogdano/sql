<?php echo '<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="https://unpkg.com/@picocss/pico@1.*/css/pico.min.css">
  <link rel="stylesheet" href="css/custom.css">
  <link rel="icon" href="css/database.svg">'; ?>
  <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
  <label id="switch" class="switch">
    <input type="checkbox" data-theme-switcher="light" id="slider">
    <span class="slider round"></span>
  </label>
  <script>
  document.addEventListener("DOMContentLoaded", (event) => {
    if(window.localStorage.getItem("picoPreferredColorScheme") === "light") {
      document.getElementById("slider").checked = true;
    } else {
      document.getElementById("slider").checked = false;
    }
    themeSwitcher.schemeToLocalStorage()
  });
</script>