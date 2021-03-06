<?php

namespace Boparaiamrit\Webserver\Generators\Webserver;


use Boparaiamrit\Webserver\Generators\FileGenerator;

class Env extends FileGenerator
{
	/**
	 * Generates the view that is written.
	 *
	 * @return \Illuminate\View\View
	 */
	public function generate()
	{
		$machine = config('webserver.machine', 'ubuntu');
		
		$url  = 'http://' . $this->Host->hostname;
		$port = config('webserver.nginx.port.' . $machine);
		
		if ($port != 80) {
			$url .= ':' . $port;
		}
		
		/** @noinspection PhpUndefinedFieldInspection */
		$this->Host->url = $url;
		
		$config = [
			'Host' => $this->Host,
		];
		
		return view('webserver::env.configuration', $config);
	}
	
	/**
	 * Provides the complete path to publish the generated content to.
	 *
	 * @return string
	 */
	protected function publishPath()
	{
		return sprintf('%s.%s.env', config('webserver.env.path'), $this->name());
	}
	
	/**
	 * Reloads service if possible.
	 *
	 * @return bool
	 */
	protected function serviceReload()
	{
		return true;
	}
}
