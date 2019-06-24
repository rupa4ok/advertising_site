<?php

namespace App\Providers;

use App\Models\Adverts\Category;
use App\Models\Region;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class CacheServiceProvider extends ServiceProvider
{
	private $classes = [
		Region::class,
		Category::class
	];
	
	public function boot(): void
	{
		foreach ($this->classes as $class) {
			$this->registerFlusher($class);
		}
	}
	
	private function registerFlusher($class): void
	{
		$flush = function() use ($class) {
			Cache::tags($class)->flush();
		};
		
		/** @var Model $class */
		$class::created($flush);
		$class::saved($flush);
		$class::updated($flush);
		$class::deleted($flush);
	}
}
