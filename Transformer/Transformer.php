<?php namespace App\Duxon\Transformer;

abstract class Transformer {

	private $model;

	private $collection;



	public function from(Model $model) {
		$this->model = $model;
		$this->collection = null;

		return $this;
	}



	public function fromCollection(Collection $collection) {
		$this->collection = $collection;
		$this->model = null;

		return $this;
	}



	public function transform(\Closure $closure) {
		if ($this->model) {
			return $closure($this->model);

		} elseif ($this->collection) {
			$transformedCollection = [];

			foreach ($this->collection as $item)
				$transformedCollection[] = $closure($item);

			return $transformedCollection;
		}
	}

}