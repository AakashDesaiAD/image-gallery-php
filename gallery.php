<?php 
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

	if (!$_POST) {
		header("location: index.php");
	}
	include 'DbConnection.php';
	$dbconnection = new Dbconnection();
	$conn = $dbconnection->__construct();

	$imgId = [];
	$imageIds = implode(",",$_POST['img']);
	$query = "SELECT * FROM gallery WHERE id IN({$imageIds})";
	$data = $conn->query($query);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Photo Upload</title>
    <link rel="stylesheet" type="text/css" href="morph.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body>
	<?php ?>
	<section class="m-4">
    	<p class="text-center h3 my-4">Selected Image Gallery</p>
	        <div class="d-flex justify-content-center align-items-center flex-wrap">
	        <?php 
	    		while ($img = $data->fetch_assoc()) {
	    	?>
	        	<label class="m-2 position-relative" style="border: 1px dashed black;">
	        		<img src="<?php echo 'uploads/'.$img['name'] ; ?>" style="height: 200px; width: 250px;">
	        	</label>
	        <?php } ?>
	        </div>
        </form>
    </section>
</body>
</html>