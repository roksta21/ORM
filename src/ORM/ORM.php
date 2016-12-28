<?php

namespace ORM;

/**
* This is the ORM
*/
abstract class ORM
{	
	function __construct($id = null)
	{
		if ($id != null) {
			$this->id = $id;
		}

		static::PRIMARY != null ? $this->primary_key = static::PRIMARY : $this->primary_key = 'id';
		$this->{$this->primary_key} = $this->generateId();
	}

	public function save()
	{
		file_put_contents('database/' . str_replace('\\', '_', static::class) . '_' . $this->primary_key . '.json', json_encode($this));
	}

	public function generateID()
	{
		$last_file_name = collect(array_slice(scandir('database'),2))
			->filter(function($file) {
				return startsWith($file, str_replace('\\', '_', static::class));
			})
			->reverse()
			->first();
		if (is_null($last_file_name)) {
			$id = 1;
		} else {
			$last_file = json_decode(file_get_contents('database/' . $last_file_name));
			$id = $last_file->{$this->primary_key} + 1;
		}

		return $id;
	}

	public static function find($id)
	{
		static::PRIMARY != null ? $primary_key = static::PRIMARY : $primary_key = 'id';
		$file_name =  collect(array_slice(scandir('database'),2))
			->filter(function($file) use ($id, $primary_key) {
				$file_data = json_decode(file_get_contents('database/' . $file));
				if (isset($file_data->$primary_key)) {
					return $file_data->$primary_key == $id;
				}
			})
			->first();
		if ($file_name == null) {
			return null;
		}

		$file = json_decode(file_get_contents('database/' . $file_name));

		return recast(static::class, $file);
	}

	public static function search(Array $params)
	{
		return collect(array_slice(scandir('database'),2))
			->map(function($file) use ($params) {
				$file_data = json_decode(file_get_contents('database/' . $file));

				$result = collect($params)->filter(function($value, $key) use ($file_data) { 
					if (isset($file_data->$key)) {;
						return strcasecmp($file_data->$key, $value) == 0;
					}
				});

				if ($result->count() > 0) {
					return $file_data;
				}
			})->reject(function($value) {
				return $value == null;
			})->map(function($value) {
				return recast(static::class, $value);
			});
	}

	public static function create(Array $params)
	{
		$data = new static;

		collect($params)->each(function($value, $key) use ($data) {
			$data->$key = $value;
		});

		$data->save();

		return $data;
	}

	public function update(Array $params)
	{
		collect($params)->each(function($value, $key) {
			$this->$key = $value;
		});

		file_put_contents('database/' . str_replace("\\", "_", static::class) . '_' . $this->id . '.json', json_encode($this));

		return $this;
	}

	public function delete()
	{
		unlink('database/' . str_replace("\\", "_", static::class) . '_' . $this->primary_key . '.json');
	}

}