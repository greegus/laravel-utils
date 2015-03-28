<?php namespace App\Duxon\Respository\Contracts;

interface Repository {

	/**
	 * Get all models
	 *
	 * @param array $with
	 * @return mixed
	 */
	public function all(array $with = array());



	/**
	 * Find model by its Id
	 *
	 * @param $id
	 * @param array $with
	 * @return mixed
	 */
	public function find($id, array $with = array());



	/**
	 * Create or update model byt input
	 *
	 * @param array $input
	 * @return mixed
	 */
	public function updateOrCreate(array $input = array());



	/**
	 * Finds and remove model by given Id
	 *
	 * @param $id
	 * @return mixed
	 */
	public function destroy($id);

}