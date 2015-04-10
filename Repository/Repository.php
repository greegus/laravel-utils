<?php namespace App\Duxon\Respository;


use App\Duxon\Respository\Contracts\BasicRepository as RepositoryContract;

abstract class Repository implements RepositoryContract {

	/**
	 * @var Model $model
	 */
	protected $model;



	/**
	 * Get the key value from the input array, if present
	 *
	 * @param array $input
	 * @return int|null
	 */
	protected function getKeyFromInput(array $input = array()) {
		$key = $this->model->getKeyName();
		return array_key_exists($key, $input) ? $input[$key] : null;
	}



	public function all(array $with = array()) {
		return $this->model->with($with)->get();
	}



	public function find($id, array $with = array()) {
		return $this->model->with($with)->find($id);
	}



	protected function beforeStore($model) {

	}



	public function store(array $input = array()) {
		$model = $this->model->findOrNew($this->getKeyFromInput($input))->fill($input);

		$this->beforeStore($model);

		return $model->save() ? $model : false;
	}



	protected function beforeDestroy($model) {

	}



	public function destroy($id) {
		$model = $this->find($id);

		$this->beforeDestroy($model);

		return $model->delete();
	}

}