<?php
session_start();
header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
require('php/config.php');

if (isset($_POST['search'])) {
    $region = mysqli_real_escape_string($mysqli, $_POST['region']);
    $district = mysqli_real_escape_string($mysqli, $_POST['district']);
    $facility = mysqli_real_escape_string($mysqli, $_POST['facility']);
    $date_from = mysqli_real_escape_string($mysqli, $_POST['date_from']);
    $date_to = mysqli_real_escape_string($mysqli, $_POST['date_to']);

    // Build your SQL query based on filters
$query = "SELECT * from satisfaction WHERE 1=1";

// Apply filters if set
if (!empty($filters['region'])) {
    $region = $mysqli->real_escape_string($filters['region']);
    $query .= " AND region = '$region'";
}
if (!empty($filters['district'])) {
    $district = $mysqli->real_escape_string($filters['district']);
    $query .= " AND district = '$district'";
}
if (!empty($filters['facility'])) {
    $facility = $mysqli->real_escape_string($filters['facility']);
    $query .= " AND facility = '$facility'";
}
if (!empty($filters['date_from']) && !empty($filters['date_to'])) {
    $date_from = $mysqli->real_escape_string($filters['date_from']);
    $date_to = $mysqli->real_escape_string($filters['date_to']);
    $query .= " AND date BETWEEN '$date_from' AND '$date_to'";
}

$query .= " GROUP BY region";

$result = $mysqli->query($query);

}

?>
<form class="d-none d-sm-inline-block me-auto ms-md-3 my-2 my-md-0 mw-100 navbar-search" method="POST" action="#" id="filterForm">
<div class="input-group me-5 pe-0" style="width: 1084px;">
    <input class="bg-light form-control border-0 small" type="text" placeholder="Filter By Region" style="width: 159px;margin: 5px;" id="region" name="region">
    <input class="bg-light form-control border-0 small" type="text" placeholder="Filter By District" style="width: 159px;margin: 5px;" id="district" name="district">
    <input class="bg-light form-control border-0 small" type="text" placeholder="Filter By Facility" style="width: 159px;margin: 10px;" id="facility" name="facility">
    <input class="bg-light form-control border-0 small" type="text" placeholder="Filter By Date From" style="width: 159px;margin: 5px;" id="date_from" name="date_from">
    <input class="bg-light form-control border-0 small" type="text" placeholder="Filter By Date To" style="width: 159px;margin: 5px;" id="date_to" name="date_to">
    <button class="btn btn-primary py-0" type="button" name="search"><i class="fas fa-search"></i>Search</button><button id="clearFilters" class="btn btn-warning py-0 fa-sm" type="button">Clear Filters</button>
</div>
</form>
<script type="text/javascript">
    loadData();
   document.getElementById('clearFilters').addEventListener('click', function () {
    fetch('php/includes/clear_filters.php', {
        method: 'GET',
        credentials: 'same-origin'
    })
    .then(response => response.json())
    .then(() => {
        // Reset filter UI elements
        document.getElementById('region').value = '';
        document.getElementById('district').value = '';
        document.getElementById('facility').value = '';
        document.getElementById('date_from').value = '';
        document.getElementById('date_to').value = '';
        // Reload data
        loadData();
    });
});

</script>