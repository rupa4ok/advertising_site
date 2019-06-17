<?php

namespace App\Http\Controllers\Admin\Adverts;

use App\Http\Requests\Admin\RejectRequest;
use App\Http\Requests\Advert\EditRequest;
use App\Http\Requests\Cabinet\Advert\AttributesRequest;
use App\Models\Adverts\Advert\Advert;
use App\Http\Controllers\Controller;
use App\Http\Requests\Adverts\PhotosRequest;
use App\Models\User;
use App\UseCases\Adverts\AdvertService;
use Illuminate\Http\Request;

class AdvertController extends Controller
{
	use AdvertFilter;
	
	private $service;
	
	public function __construct(AdvertService $service)
	{
		$this->service = $service;
		$this->middleware('can:manage-adverts');
	}
	
	public function index(Request $request)
	{
		$query = Advert::orderByDesc('updated_at');
		$this->filter($request, $query);
		$adverts = $query->paginate(20);
		$statuses = Advert::statusesList();
		$roles = User::rolesList();
		
		return view('admin.adverts.adverts.index', compact('adverts', 'statuses', 'roles'));
	}
	
	public function editForm(Advert $advert)
	{
		return view('adverts.edit.advert', compact('advert'));
	}
	
	public function edit(EditRequest $request, Advert $advert)
	{
		try {
			$this->service->edit($advert->id, $request);
		} catch (\DomainException $e) {
			return back()->with('error', $e->getMessage());
		}
		
		return redirect()->route('adverts.show', $advert);
	}
	
	public function attributesForm(Advert $advert)
	{
		return view('adverts.edit.attributes', compact('advert'));
	}
	
	public function attributes(AttributesRequest $request, Advert $advert)
	{
		try {
			$this->service->editAttributes($advert->id, $request);
		} catch (\DomainException $e) {
			return back()->with('error', $e->getMessage());
		}
		
		return redirect()->route('adverts.show', $advert);
	}
	
	public function photosForm(Advert $advert)
	{
		return view('adverts.edit.photos', compact('advert'));
	}
	
	public function photos(PhotosRequest $request, Advert $advert)
	{
		try {
			$this->service->addPhotos($advert->id, $request);
		} catch (\DomainException $e) {
			return back()->with('error', $e->getMessage());
		}
		
		return redirect()->route('adverts.show', $advert);
	}
	
	public function moderate(Advert $advert)
	{
		try {
			$this->service->moderate($advert->id);
		} catch (\DomainException $e) {
			return back()->with('error', $e->getMessage());
		}
		
		return redirect()->route('adverts.show', $advert);
	}
	
	public function rejectForm(Advert $advert)
	{
		return view('admin.adverts.adverts.reject', compact('advert'));
	}
	
	public function reject(RejectRequest $request, Advert $advert)
	{
		try {
			$this->service->reject($advert->id, $request);
		} catch (\DomainException $e) {
			return back()->with('error', $e->getMessage());
		}
		
		return redirect()->route('adverts.show', $advert);
	}
	
	public function destroy(Advert $advert)
	{
		try {
			$this->service->remove($advert->id);
		} catch (\DomainException $e) {
			return back()->with('error', $e->getMessage());
		}
		
		return redirect()->route('admin.adverts.adverts.index');
	}
}
