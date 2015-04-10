<?php namespace App\Duxon\Transformers;


use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

abstract class Transformer {

	private $source;



	public static function from($source) {
		return new static($source);
	}



	public function __construct($source) {
		$this->source = $source;
	}



	public function transform(\Closure $closure) {
		if ($this->source instanceof Model) {
			return $closure($this->source);

		} elseif ($this->source instanceof Collection) {
			$transformedCollection = [];

			foreach ($this->source as $source)
				$transformedCollection[] = $closure($source);

			return $transformedCollection;
		}
	}

}