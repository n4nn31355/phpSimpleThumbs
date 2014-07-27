<!DOCTYPE html>
<!--
@author: n4nn31355 (artemiev.vyacheslav@gmail.com).
-->
<html>
	<head>
		<meta charset="UTF-8">
		<title>SimpleThumbs Demo</title>
	</head>
	<body>
		
		<?php
			require_once dirname( __FILE__ ) . '/../SimpleThumbs.php';

			$SimpleThumbs = new n4nn31355\SimpleThumbs();

			$directory = 'img/';
			
			/*
			 * Delete old thumbs
			 */
			foreach (scandir($directory) as $file) {
				$originalImagePath = $directory . $file;
				if (is_dir($originalImagePath))	{
					continue;
				}
				if (strpos($file, 'thumb_') !== false){
					unlink($originalImagePath);
				}
			}
			
			/*
			 * Create thumbs for images in dir
			 */
			foreach (scandir($directory) as $file) {
				$originalImagePath = $directory . $file;
				if (is_dir($originalImagePath))	{
					continue;
				}
					$thumbnailImagePath = $directory . 'thumb_' . $file . '.png';
					$SimpleThumbs->createThumbnail($originalImagePath, $thumbnailImagePath, 180, 130);

					echo '<img src="' . $thumbnailImagePath . '" />';
			}
		?>
		
	</body>
</html>
