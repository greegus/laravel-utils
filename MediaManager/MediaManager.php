<?php namespace App\Duxon\MediaManager;

class MediaManager {

	/**
	 * @param $media
	 * @return string
	 */
	public static function serialize($media) {
		return json_encode($media);
	}



	/**
	 * @param $media
	 * @return mixed
	 */
	public static function unserialize($media) {
		return json_decode($media);
	}



	/**
	 * @param $media
	 * @param $updateQuery
	 * @return array
	 * @throws \MediaManagerException
	 */
	public static function updateMedia($media, $query) {
		$media = $media ?: [];

		foreach ((array) $query as $medium) {
			if (is_array($medium)) {
				if (!isset($medium["action"]))
					continue;

				switch ($medium["action"]) {
					case "store":
						$media[] = self::storeMedium($medium["url"], isset($medium["name"]) ? $medium["name"] : uniqid());
						break;

					case "remove":
						if (is_array($media)) {
							$index = self::getMediumIndex($media, $medium["url"]);

							if ($index > -1)
								array_splice($media, $index, 1);
						}

						@unlink($medium["url"]);

						break;
				}

			} else {
				$media[] = self::storeMedium($medium, uniqid());
			}
		}

		return $media;
	}



	public static function removeMedia($media) {
		foreach ((array) $media as $medium) {
			if (file_exists($medium->url)) {
				$fileDirPath = dirname($medium->url);

				@unlink($medium->url);

				if (count(scandir($fileDirPath)) == 2)
					@rmdir($fileDirPath);
			}
		}
	}



	/**
	 * @param $medium
	 * @param array $options
	 * @return mixed
	 */
	public static function getThumbnail($medium, array $options = array()) {
		// TODO: generate thumbnail

		return $medium->url;
	}



	/**
	 * @param $media
	 * @param $path
	 * @return int|string
	 */
	protected static function getMediumIndex($media, $path) {
		$index = -1;

		foreach ($media as $mediumIndex => $medium) {
			if ($medium->url == $path) {
				$index = $mediumIndex;
				break;
			}
		}

		return $index;
	}



	/**
	 * @return string
	 */
	protected static function getThumbnailsDirPath() {
		return config("media_manager.thumbnails_path", "public/data/thumbnails");
	}



	/**
	 * @return string
	 */
	protected static function getUploadDirPath() {
		return config("media_manager.upoad_path", "public/data/uploads");
	}



	/**
	 * @param $fileName
	 * @return null|string
	 */
	protected static function getUniqFilePath($fileName) {
		$uniqid = uniqid();

		$fileName = $uniqid . "-" . $fileName;
		$fileDirPath = self::getUploadDirPath() . "/" . substr($uniqid, 0, 1) . "/" . substr($uniqid, 1, 1);
		$filePath = null;

		$fileNameInfo = pathinfo($fileName);

		$postfixIterator = 1;

		while (file_exists(($filePath = $fileDirPath . "/" . $fileNameInfo["filename"] . ($postfixIterator > 1 ? "-" . $postfixIterator : "") . (isset($fileNameInfo["extension"]) ? "." . $fileNameInfo["extension"] : ""))))
			$postfixIterator++;

		return $filePath;
	}



	/**
	 * Process and store file from given URL or DataURL
	 *
	 * @param $urlOrDataUrl - url or dataUrl string
	 * @param null $fileName - desired file name, if not provided,
	 * @return \stdClass
	 * @throws \MediaManagerException
	 */
	protected static function storeMedium($urlOrDataUrl, $fileName = null) {
		if (filter_var($urlOrDataUrl, FILTER_VALIDATE_URL)) {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $urlOrDataUrl);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

			$data = curl_exec($ch);
			$type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);

			$error = curl_error($ch);

			curl_close($ch);

			if ($error)
				throw new MediaManagerException($error);

			if (!$fileName) {
				$fileName = basename($urlOrDataUrl);

				if (($offset = strpos($fileName, "?")) > -1)
					$fileName = substr($fileName, 0, $offset);
			}

			$fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

			if (!$fileExtension)
				$fileName .= "." . explode("/", $type)[1];

		} else if (preg_match("/^(data:(.*?);base64,)/", $urlOrDataUrl, $matches)) {
			$type = $matches[2];
			$data = base64_decode(substr($urlOrDataUrl, strlen($matches[1])));

		} else {
			throw new MediaManagerException("unable to process file from given source: " . (string)$urlOrDataUrl);
		}

		$filePath = self::getUniqFilePath($fileName);
		$fileDirPath = dirname($filePath);

		if (!is_dir($fileDirPath))
			mkdir($fileDirPath, 0755, true);

		file_put_contents($filePath, $data);

		$medium = new \stdClass;
		$medium->name = $fileName;
		$medium->type = $type;
		$medium->url = $filePath;

		return $medium;
	}
}



class MediaManagerException extends \Exception {

}