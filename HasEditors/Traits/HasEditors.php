<?php namespace App\Duxon\HasEditors\Traits;

trait HasEditors {

	public function createdBy() {
		return $this->belongsTo('App\User', 'created_by');
	}


	public function updatedBy() {
		return $this->belongsTo('App\User', 'updated_by');
	}

}