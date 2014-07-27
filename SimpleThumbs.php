<?php

/**
 * phpSimpleThumbs - simple thumbnail generator
 * 
 * Uses GD. Create PNG thumbnail without empty space(fill mode) from JPEG, PNG or GIF.
 *
 * @author Artemiev Vyacheslav aka n4nn31355 (artemiev.vyacheslav@gmail.com).
 * @version 1.0
 */

namespace n4nn31355;

class SimpleThumbs {
	/**
	 * Generate thumbnail from file and saves it to disk as PNG
	 * @param string $imagePath Path to source image
	 * @param string $thumbnailPath Target path
	 * @param int $targetWidth Thumbnail width
	 * @param int $targetHeight Thumbnail height
	 * @return
	 */
	public function createThumbnail($imagePath, $thumbnailPath, $targetWidth, $targetHeight)
	{
		$originalImageInfo = $this->getImageInfo($imagePath);

		$trimmedSize = $this->calculateTrimmedSize($originalImageInfo, $targetWidth, $targetHeight);
		$trimmedOrigin = $this->calculateTrimmedOrigin($originalImageInfo, $trimmedSize);
		
		$this->scaleDiskImage($imagePath, $thumbnailPath, $originalImageInfo['Type'], $trimmedSize, $trimmedOrigin, $targetWidth, $targetHeight);
	}
	
	public function recreateThumbnail($param) {
		
	}
	
	/*
	 * Private 
	 */
	/**
	 * Get image size and type
	 * @param string $imagePath Path to image file
	 * @return array associative array(Width, Height, Type)
	 */
	private function getImageInfo($imagePath) {
		list($imageInfo['Width'], $imageInfo['Height'], $imageInfo['Type']) = getimagesize($imagePath);
		return $imageInfo;
	}
	private function calculateTrimmedSize($originalSize, $targetWidth, $targetHeight)
	{
		$originalRatio = $originalSize['Width'] / $originalSize['Height'];
		$targetRatio = $targetWidth / $targetHeight;
		
		if ( $originalRatio >= $targetRatio ) {
			if ( $originalRatio >= 1 ) {
				$trimmedSize['Width'] = $originalSize['Height'] * $targetRatio;
				$trimmedSize['Height'] = $originalSize['Height'];
			} else {
				$trimmedSize['Width'] = $originalSize['Width'] / $originalRatio * $targetRatio;
				$trimmedSize['Height'] = $originalSize['Height'];
			}
		} else {
			if ( $originalRatio >= 1 ) {
				$trimmedSize['Width'] = $originalSize['Width'];
				$trimmedSize['Height'] = $originalSize['Height'] / $targetRatio * $originalRatio;
			} else {
				$trimmedSize['Width'] = $originalSize['Width'];
				$trimmedSize['Height'] = $originalSize['Height'] * $originalRatio / $targetRatio;
			}
		}
		return $trimmedSize;
	}
	private function calculateTrimmedOrigin($originalSize, $trimmedSize)
	{
		$trimmedOrigin = array(
			'X' => ($originalSize['Width'] - $trimmedSize['Width']) / 2,
			'Y' => ($originalSize['Height'] - $trimmedSize['Height']) / 2
		);
		return $trimmedOrigin;
	}
	/**
	 * Create image from file
	 * @param int $imageType
	 * @param string $imagePath
	 * @return resource Image resource on success or FALSE on failure
	 */
	private function imageCreateFromType($imageType, $imagePath)
	{
		switch ($imageType) {
			case 1:
				$imageCreateFromFunction = "imagecreatefromgif";
				break;
			case 2:
				$imageCreateFromFunction = "imagecreatefromjpeg";
				break;
			case 3:
				$imageCreateFromFunction = "imagecreatefrompng";
				break;
			default:
				return false;
		}
		return $imageCreateFromFunction($imagePath);
	}
	/**
	 * Scales the image from disk and saves it to disk as PNG.
	 * @param string $sourceImagePath Path to original image
	 * @param string $targetImagePath Target path
	 * @param int $originalImageType Image type from getimagesize()
	 * @param array $trimmedSize
	 * @param array $trimmedOrigin
	 * @param int $targetWidth
	 * @param int $targetHeight
	 */
	private function scaleDiskImage($sourceImagePath, $targetImagePath, $originalImageType, $trimmedSize, $trimmedOrigin, $targetWidth, $targetHeight)
	{
		$sourceImage = $this->imageCreateFromType($originalImageType, $sourceImagePath);
		$targetImage = imagecreatetruecolor($targetWidth, $targetHeight);
		imagecopyresampled($targetImage, $sourceImage, 0, 0, $trimmedOrigin['X'], $trimmedOrigin['Y'], $targetWidth, $targetHeight, $trimmedSize['Width'], $trimmedSize['Height']);
		imagepng($targetImage, $targetImagePath);
	}
}
