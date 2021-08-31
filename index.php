<?php
//your site link
$site_link = 'http://localhost/Link-Shortner/';

$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$url = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

require_once('connection.php');
$user='';


$sql = "SELECT * FROM sharesite";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
	while ($row = $result->fetch_assoc()) {
		$fb=strpos($url, $row["share_site_id"]);
		if($fb==false)
			$user = substr($url, -6);
		else{
			$sub=substr($url, -$row["share_count"]);
			$user = substr($sub, 0,  6);
		}
	}
}	


$sql = "SELECT main_link FROM linkstore where short_id='$user'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
	// output data of each row
	while ($row = $result->fetch_assoc()) {
		header("Location: " . $row["main_link"] . "");
	}
}

function getName($n)
{
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$randomString = '';

	for ($i = 0; $i < $n; $i++) {
		$index = rand(0, strlen($characters) - 1);
		$randomString .= $characters[$index];
	}

	return $randomString;
}
$short_id = '';
if (isset($_POST['short'])) {
	$main_link = $_POST['main_link'];
	$short_id = getName(6);

	$sql = "INSERT INTO linkstore (main_link, short_id)VALUES ('$main_link', '$short_id')";

	if ($conn->query($sql) === TRUE) {
	} else {
	}
}

?>
<html>

<head>
	<title>Link Shortner</title>
	<link href="vendors/favicon/fav.png" rel="shortcut icon">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="vendors/cdn.datatables.net/v/bs4/dt-1.10.18/fc-3.2.5/r-2.2.2/datatables.min.css" />

	<head>

	<body>
		<div class="container mt-5">
			<div class="card">
				<form action="" method="post" class="p-4">
					<div class="form-group">
						<label for="exampleInputEmail1">Link</label>
						<input type="text" class="form-control" id="exampleInputEmail1" name="main_link" aria-describedby="emailHelp">
						<small id="emailHelp" class="form-text text-muted">We'll never share your link with anyone else.</small>
					</div>
					<button type="submit" name="short" class="btn btn-primary">Short Your Link</button>
				</form>

			</div>
			<div class="card">
				<div class="row p-4">
					<?php
					if ($short_id != '') {
						echo $site_link . $short_id;
					?>
				</div>
				<div class="row p-4">
					<br>
					<a href="<?php echo $site_link . $short_id; ?>" class="btn btn-primary">Go This Link</a>
				<?php
					}
				?>
				</div>
			</div>
			<div class="card">
				<div class="row p-5 table-responsive">
					<table id="example" class="table table-hover table-bordered" width="100%">
						<thead>
							<tr>
								<th>SL</th>
								<th>Date</th>
								<th>Main Link</th>
								<th>Short Link</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$sl = (int)1;
							$sql = "SELECT * FROM linkstore order by date desc";
							$result = $conn->query($sql);

							if ($result->num_rows > 0) {
								// output data of each row
								while ($row = $result->fetch_assoc()) {
							?>
									<tr>
										<td><?php echo $sl; ?></td>
										<td><?php echo date_format(date_create_from_format('Y-m-d H:i:s', $row["date"]), 'd-m-Y  H:i:s'); ?></td>
										<td><?php echo $row["main_link"]; ?></td>
										<td><?php echo $site_link . $row["short_id"]; ?></td>
									</tr>
							<?php
									$sl = $sl + 1;
								}
							}
							$conn->close();
							?>
						</tbody>
					</table>
				</div>
			</div>
		</div>

		<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
		<script type="text/javascript" src="vendors/cdn.datatables.net/v/bs4/dt-1.10.18/fc-3.2.5/r-2.2.2/datatables.min.js"></script>
		<script src="vendors/editable-table/mindmup-editabletable.js"></script>
		<script>
			;
			(function($) {
				'use strict'
				$(function() {
					$('#example').DataTable({
						responsive: true,
					})
				})
			})(jQuery)
		</script>
	</body>

</html>