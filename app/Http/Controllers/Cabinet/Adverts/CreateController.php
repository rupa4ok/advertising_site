<?php

namespace App\Http\Controllers\Cabinet\Adverts;

use App\Models\Region;
use App\Models\Adverts\Category;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\FilledProfile;
use App\UseCases\Adverts\AdvertService;
use App\Http\Requests\Cabinet\Advert\CreateRequest;

class CreateController extends Controller
{
    private $service;

    public function __construct(AdvertService $service)
    {
        $this->service = $service;
        $this->middleware(FilledProfile::class);
    }

    public function category()
    {
        $categories = Category::defaultOrder()->withDepth()->get()->toTree();

        return view('cabinet.adverts.create.category', compact('categories'));
    }

    public function region(Category $category, Region $region = null)
    {
        $regions = Region::where('parent_id', $region ? $region->id : null)->orderBy('name')->get();

        return view('cabinet.adverts.create.region', compact('category', 'region', 'region'));
    }

    public function advert(Category $category, Region $region = null)
    {
        return view('cabinet.adverts.create.adverts', compact('category', 'region'));
    }

    public function store(CreateRequest $request, $category, Region $region = null)
    {
        try {
            $adverts = $this->service->create(
            Auth::id(),
            $category->id,
            $region ? $region->id : null,
            $request
            );
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('adverts.show', $adverts);
    }
}
