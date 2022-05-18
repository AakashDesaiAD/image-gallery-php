<?php
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

	include 'DbConnection.php';
	$dbconnection = new Dbconnection();
	$conn = $dbconnection->__construct();
	$log = [];

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  	
	$mainArray = [];
	$allPhotos = $_FILES['photos'];

	// fetch name
	foreach ($allPhotos['name'] as $key => $value) {
		$mainArray[$key]['name'] = $value;
		$tmp = explode('.', $value);
		$ext = end($tmp);
		$mainArray[$key]['ext'] = $ext;
	}

	//fetch tmp_name
	foreach ($allPhotos['tmp_name'] as $key => $value) {
		$mainArray[$key]['tmp_name'] = $value;
	}

	foreach ($mainArray as $key => $image) {
		try {
			$realName = $image['name'];
			$imgName = "uploads/".$image['name'];
			$status = move_uploaded_file($image['tmp_name'], $imgName);

			if ($status) {
				$uploadStatus = $conn->query("INSERT INTO gallery (id, name) VALUES (NULL, '{$realName}')");
				if ($uploadStatus) {
					$log['success'][$key] = $image['name'];
				}
			} else {
				$log['failed'][$key] = $image['name'];
			}
		} catch (Exception $e) {
			$log['failed'][$key] = $image['name'];
		}
	}
  }
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
    <p class="text-center h2 my-3">Upload photos</p>
    <div class="d-flex justify-content-center d-flex">
        <form method="POST" action="<?=$_SERVER['PHP_SELF'];?>" enctype="multipart/form-data">
            <div class="form-group my-3">
                <input class="form-control" type="file" name="photos[]" accept="image/*" multiple required>
            </div>
            <button type="submit" class="btn btn-primary w-100 my-3 active">Upload</button>
            <div class="my-3">
                <?php 
				if (array_key_exists("success",$log)) {
					echo "<p>Files Uploaded Successfully</p>";
				?>
                <ul class="list-group">
                    <?php
					foreach ($log['success'] as $key => $uploaded) {
						?>
                    <li class="list-group-item d-flex justify-content-between align-items-center bg-success">
                        <?php echo $uploaded ?></li>
                    <?php } ?>
                </ul>
                <?php } ?>
            </div>

            <div class="my-1">
                <?php 
				if (array_key_exists("failed",$log)) {
					echo "<p>Files Uploaded Failed</p>";
				?>
                <ul class="list-group">
                    <?php

					foreach ($log['failed'] as $key => $uploaded) {
						?>
                    <li class="list-group-item d-flex justify-content-between align-items-center bg-danger">
                        <?php echo $uploaded ?></li>
                    <?php } ?>
                </ul>
                <?php } ?>
            </div>
        </form>
    </div>

    <section class="mx-4">
    	<p class="text-center h3">Image Gallery</p>
    	<form action="gallery.php" method="POST">
	        <div class="d-flex justify-content-center align-items-center flex-wrap">
	        <?php 
	    		$gallery = $conn->query("SELECT * FROM gallery");
	    		while ($img = $gallery->fetch_assoc()) {
	    	?>
	        	<label class="m-2 position-relative" style="border: 1px dashed black;">
	        		<input type="checkbox" name="img[]" value="<?php echo $img['id'] ?>" class="fixed-top position-absolute m-2">
	        		<img src="<?php echo 'uploads/'.$img['name'] ; ?>" style="height: 200px; width: 250px;">
	        	</label>
	        <?php } ?>
	        </div>
	        <div class="d-flex justify-content-center align-items-center m-5">
	        	<button class="btn btn-primary active text-center">View selected images</button>
	        </div>
        </form>
    </section>
</body>

</html>