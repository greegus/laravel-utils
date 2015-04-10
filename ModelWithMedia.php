<?php namespace App\Duxon;


use App\Duxon\MediaManager\Contracts\ManagesMedia;
use App\Duxon\MediaManager\Traits\ManagesMediaTrait;
use Illuminate\Database\Eloquent\Model;

abstract class ModelWithMedia extends Model implements ManagesMedia {

	use ManagesMediaTrait;

	protected $media = [];



	public function isMedia($key) {
		return in_array($key, $this->media);
	}



	public function setAttribute($key, $value) {
		if ($this->isMedia($key)) {
			$this->updateMedia($key, $value);

		} else {
			parent::setAttribute($key, $value);
		}

	}



	public function getAttribute($key) {
		if ($this->isMedia($key)) {
			return $this->getMedia($key);

		} else {
			return parent::getAttribute($key);
		}
	}

}