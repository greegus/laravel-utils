<?php namespace App\Duxon\MediaManager\Traits;

use App\Duxon\MediaManager\MediaManager;

trait ManagesMediaTrait {

	/**
	 * @param string $mediaColumnName
	 * @param array $query
	 */
	public function updateMedia($mediaColumnName, $query = array()) {
		$updatedMedia = MediaManager::updateMedia($this->getMedia($mediaColumnName), $query, $mediaColumnName, isset($this->mediaRootPath) ? $this->mediaRootPath : null);
		$this->attributes[$mediaColumnName] = MediaManager::serialize($updatedMedia);
	}



	public function removeMedia($mediaColumnName) {
		MediaManager::removeMedia($this->getMedia($mediaColumnName));
		$this->attributes[$mediaColumnName] = null;
	}



	/**
	 * @param string $mediaColumnName
	 * @return mixed
	 */
	public function getMedia($mediaColumnName) {
		if (array_key_exists($mediaColumnName, $this->attributes))
			return MediaManager::unserialize($this->attributes[$mediaColumnName]);
	}



	/**
	 * @param string $mediaColumnName
	 * @return mixed
	 */
	public function getMedium($mediaColumnName) {
		$media = $this->getMedia($mediaColumnName);

		if (count($media))
			return $media[0];
	}



	/**
	 * @param string $mediaColumnName
	 * @param array $options
	 * @return mixed
	 */
	public function getMediaThumbnail($mediaColumnName, $options = array()) {
		$media = $this->getMedia($mediaColumnName);

		foreach ($media as &$medium)
			$medium->thumbnail_path = MediaManager::getThumbnail($medium->path, $options);

		unset($medium);

		return $media;
	}



	/**
	 * @param string $mediaColumnName
	 * @param array $options
	 * @return mixed
	 */
	public function getMediumThumbnail($mediaColumnName, $options = array()) {
		$medium = $this->getMedium($mediaColumnName);

		if ($medium) {
			$medium->thumbnail_path = MediaManager::getThumbnail($medium->path, $options);

			return $medium;
		}
	}
}