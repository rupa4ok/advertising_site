<?php

namespace App\Http\Controllers\Adverts;

use App\Http\Controllers\Controller;
use App\Models\Adverts\Advert\Advert;
use App\UseCases\Adverts\FavoriteService;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
	private $service;
	
	public function __construct(FavoriteService $service)
	{
		$this->service = $service;
		$this->middleware('auth');
	}
	
	public function add(Advert $advert)
	{
		try {
			$this->service->add(Auth::id(), $advert->id);
		} catch (\DomainException $e) {
			return back()->with('error', $e->getMessage());
		}
		
		return redirect()->route('adverts.show', $advert)->with('success', 'Advert is added to your favorites.');
	}
	
	public function remove(Advert $advert)
	{
		try {
			$this->service->remove(Auth::id(), $advert->id);
		} catch (\DomainException $e) {
			return back()->with('error', $e->getMessage());
		}
		
		return redirect()->route('adverts.show', $advert);
	}
}
