<?php

namespace App\UseCases\Adverts;

use App\Http\Requests\Admin\RejectRequest;
use App\Http\Requests\Adverts\PhotosRequest;
use App\Http\Requests\Cabinet\Advert\AttributesRequest;
use App\Http\Requests\Cabinet\Advert\CreateRequest;
use App\Models\Adverts\Advert\Advert;
use App\Models\Adverts\Category;
use App\Models\Region;
use App\Models\User;
use Carbon\Carbon;
use DB;

class AdvertService
{
	public function create($userId, $categoryId, $regionId, CreateRequest $request): Advert
	{
		/** @var User $user */
		$user = User::findOrFail($userId);
		
		/** @var Category $category */
		$category = Category::findOrFail($categoryId);
		
		/** @var Region $region */
		$region = $regionId ? Region::findOrFail($regionId) : null;
		
		return Db::transaction(function () use ($request, $user, $region, $category) {
			/** @var Advert $advert */
			$advert = Advert::make([
				'title' => $request['title'],
				'content' => $request['content'],
				'price' => $request['price'],
				'address' => $request['address'],
				'status' => Advert::STATUS_DRAFT,
			]);
			
			$advert->user()->associate($user);
			$advert->category()->associate($category);
			$advert->region()->associate($region);
			
			$advert->saveOrFail();
			
			foreach ($category->allAttributes() as $attribute) {
				$value = $request['attributes'][$attribute->id] ?? null;
				if (!empty($value)) {
					$advert->values()->create([
						'attribute_id' => $attribute->id,
						'value' => $value,
					]);
				}
			}
			
			return $advert;
		});
	}
	
	public function addPhoto($id, PhotosRequest $request): void
	{
		$advert = $this->getAdvert($id);
		
		DB::transaction(function () use ($request, $advert) {
			foreach ($request['files'] as $file) {
				$advert->photos()->create([
					'file' => $file->store('adverts')
				]);
			}
		});
	}
	
	public function sendToModeration($id): void
	{
		$advert = $this->getAdvert($id);
		$advert->sendToModeration();
	}
	
	public function moderate($id): void
	{
		$advert = $this->getAdvert($id);
		$advert->moderate(Carbon::now());
	}
	
	public function reject($id, RejectRequest $request): void
	{
		$advert = $this->getAdvert($id);
		$advert->reject($request['reason']);
	}
	
	public function editAttributes($id, AttributesRequest $request): void
	{
		$advert = $this->getAdvert($id);
		
		DB::transaction(function () use ($request, $advert) {
			$advert->values()->delete();
			foreach ($advert->category->allAttributes() as $attribute) {
				$value = $request['attributes'][$attribute->id] ?? null;
				if (!empty($value)) {
					$advert->values()->create([
						'attribute_id' => $attribute->id,
						'value' => $value
					]);
				}
			}
		});
	}
	
	public function remove($id): void
	{
		$advert = $this->getAdvert($id);
		$advert->delete();
	}
	
	public function getAdvert($id): Advert
	{
		return Advert::findOrFail($id);
	}
}