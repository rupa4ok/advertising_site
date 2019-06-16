<?php

namespace App\Http\Controllers\Cabinet\Adverts;

use App\Http\Controllers\Controller;
use App\Http\Middleware\FilledProfile;
use App\Http\Requests\Adverts\PhotosRequest;
use App\Http\Requests\Cabinet\Advert\AttributesRequest;
use App\Models\Adverts\Advert\Advert;
use App\UseCases\Adverts\AdvertService;
use Gate;


class ManageController extends Controller
{
	private $service;
	
	public function __construct(AdvertService $service)
	{
		$this->service = $service;
		$this->middleware(FilledProfile::class);
	}
	
	public function attributes(Advert $advert)
	{
		$this->checkAccess($advert);
		return view('adverts.edit.attributes', compact('advert'));
	}
	
	public function updateAttributes(AttributesRequest $request, Advert $advert)
	{
		$this->checkAccess($advert);
		try {
			$this->service->editAttributes($advert->id, $request);
		} catch (\DomainException $e) {
			return back()->with('error', $e->getMessage());
		}
	}
	
	public function photos(Advert $advert)
	{
		$this->checkAccess($advert);
		return view('adverts.edit.photos', compact('advert'));
	}
	
	public function updatePhotos(PhotosRequest $request, Advert $advert)
	{
		$this->checkAccess($advert);
		try {
			$this->service->addPhoto($advert->id, $request);
		} catch (\DomainException $e) {
			return back()->with('error', $e->getMessage());
		}
	}
	
	public function destroy(Advert $advert)
	{
		$this->checkAccess($advert);
		try {
			$this->service->remove($advert->id);
		} catch (\DomainException $e) {
			return back()->with('error', $e->getMessage());
		}
	}
	
	private function checkAccess(Advert $advert): void
	{
		if (!Gate::allows('manage-own-advert', $advert)) {
			abort(403);
		}
	}
}