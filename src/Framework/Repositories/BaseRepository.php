<?php

namespace Boparaiamrit\Framework\Repositories;


use Closure;
use Jenssegers\Mongodb\Eloquent\Model;

abstract class BaseRepository
{
	/**
	 * @var Model Model
	 */
	protected $Model;
	
	public function __construct()
	{
		$args = func_get_args();
		
		foreach ($args as $i => $argument) {
			if ($argument instanceof Model) {
				$className = class_basename($argument);
				$className = strtolower($className);
				
				$this->{$className} = $argument;
				if ($i == 0) {
					$this->Model = $argument;
				}
			}
		}
	}
	
	/**
	 * Creates and optionally saves an object.
	 *
	 * @param array $attributes
	 * @param bool  $save
	 *
	 * @return \Illuminate\Database\Eloquent\Model
	 */
	public function create(array $attributes, $save = true)
	{
		$instance = $this->newInstance();
		
		$instance->unguard();
		$instance->fill($attributes);
		$instance->reguard();
		
		if ($save) {
			$instance->save();
		}
		
		return $instance;
	}
	
	/**
	 * @param string $type
	 *
	 * @return \Illuminate\Database\Eloquent\Model
	 */
	public function newInstance($type = null)
	{
		if (is_null($type)) {
			return $this->Model->newInstance();
		}
		
		return $this->{$type}->newInstance();
	}
	
	/**
	 * Query results for ajax.
	 *
	 * @param              $name
	 * @param null         $type
	 * @param Closure|null $additionalWhere
	 *
	 * @return mixed
	 */
	public function ajaxQuery($name, $type = null, Closure $additionalWhere = null)
	{
		$query = $this->queryBuilder($type);
		
		$search = (string)request()->get('query');
		
		// modifies query builder with additional where
		if (!is_null($additionalWhere)) {
			$query = $additionalWhere($query, $search);
		}
		
		/** @noinspection PhpUndefinedMethodInspection */
		$items = $query->where($name, 'like', "%{$search}%")->orderBy($name)->take(10)->lists($name, 'id');
		
		$results = [];
		
		foreach ($items as $id => $text) {
			$results[] = compact('id', 'text');
		}
		
		return $results;
	}
	
	/**
	 * Starts a querybuilder.
	 *
	 * @param $type
	 *
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function queryBuilder($type = null)
	{
		if (is_null($type)) {
			return $this->Model->query();
		}
		
		return $this->{$type}->query();
	}
	
	/**
	 * Finds an object by Id.
	 *
	 * @param int  $id
	 * @param bool $softDeleted
	 *
	 * @return \Illuminate\Support\Collection|null|void|static
	 */
	public function findById($id, $softDeleted = true)
	{
		if ($softDeleted && method_exists($this->Model, 'withTrashed')) {
			return $this->Model->withTrashed()->find($id);
		}
		
		/** @noinspection PhpUndefinedMethodInspection */
		return $this->Model->find($id);
	}
	
	/**
	 * Create a pagination object.
	 *
	 * @param int $per_page
	 *
	 * @return mixed
	 */
	public function paginated($per_page = 20)
	{
		/** @noinspection PhpUndefinedMethodInspection */
		return $this->Model->paginate($per_page);
	}
	
	/**
	 * Get all results from database.
	 *
	 * @return \Illuminate\Database\Eloquent\Collection
	 */
	public function all()
	{
		return $this->Model->all();
	}
}
