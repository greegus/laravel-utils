<?php namespace App\Duxon\MediaManager\Contracts;

interface ManagesMedia {
	public function updateMedia($mediaColumnName, $updateQuery = array());
	public function removeMedia($mediaColumnName);
	public function getMedia($mediaColumnName);
	public function getMedium($mediaColumnName);
	public function getMediaThumbnail($mediaColumnName, $options = array());
	public function getMediumThumbnail($mediaColumnName, $options = array());
}