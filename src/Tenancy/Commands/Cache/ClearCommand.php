<?php

namespace Boparaiamrit\Tenancy\Commands\Cache;


use Boparaiamrit\Tenancy\Commands\TTenancyCommand;
use Illuminate\Cache\CacheManager;
use Illuminate\Cache\RedisStore;

class ClearCommand extends \Illuminate\Cache\Console\ClearCommand
{
	use TTenancyCommand;
	
	/**
	 * Create a new cache clear command instance.
	 *
	 * @param CacheManager $cache
	 */
	public function __construct(CacheManager $cache)
	{
		parent::__construct($cache);
	}
	
	/**
	 * Fires the command.
	 */
	public function handle()
	{
		$tags = array_filter(explode(',', $this->option('tags')));
		
		/** @var RedisStore $Cache */
		$Cache = $this->cache->store($store = $this->argument('store'));
		
		/** @noinspection PhpUndefinedMethodInspection */
		$this->laravel['events']->fire('cache:clearing', [$store, $tags]);
		
		if (!empty($tags)) {
			$Cache->tags($tags)->flush();
		} else {
			$Host = $this->getHost();
			
			$Redis = $Cache->getRedis()->connection();
			
			$keys  = $Redis->keys($Host->identifier . ':*');
			
			foreach ($keys as $key) {
				$Redis->del($key);
			}
		}
		
		$this->info('Cache cleared successfully.');
		
		/** @noinspection PhpUndefinedMethodInspection */
		$this->laravel['events']->fire('cache:cleared', [$store, $tags]);
	}
}
