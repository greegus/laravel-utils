<?php namespace App\Duxon\Respository;


use App\Duxon\Respository\Contracts\Repository as RepositoryContract;

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
		return $this->model->with($with)->all();
	}



	public function find($id, array $with = array()) {
		return $this->model->with($with)->find($id);
	}



	public function updateOrCreate(array $input = array()) {
		$model = $this->model->findOrNew($this->getKeyFromInput($input))->fill($input);

		return $model->save() ? $model : false;
	}



	public function destroy($id) {
		return $this->model->destroy($id);
	}

}