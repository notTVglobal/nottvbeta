<?php

namespace App\Http\Controllers;

use App\Models\NewsFederalElectoralDistrict;
use App\Models\NewsPerson;
use App\Models\NewsProvince;
use App\Models\NewsSubnationalElectoralDistrict;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class NewsFederalElectoralDistrictController extends Controller
{
  public function index() {
    $user = Auth::user();

    $federalDistricts = NewsFederalElectoralDistrict::with(['province:id,name,slug'])
        ->get(['id', 'name', 'slug', 'description', 'province_id']);
    $subnationalDistricts = NewsSubnationalElectoralDistrict::with(['province:id,name,slug'])
        ->get(['id', 'name', 'slug', 'description', 'province_id']);
    $provinces = NewsProvince::select(['id', 'name', 'slug', 'description', 'abbreviation'])->get();


    return Inertia::render('NewsDistricts/Index', [
        'federalDistricts' => $federalDistricts,
        'subnationalDistricts' => $subnationalDistricts,
        'provinces' => $provinces,
        'filters'     => \Illuminate\Support\Facades\Request::only(['search']),
        'can'         => [
            'viewNewsroom' => optional($user)->can('viewAny', NewsPerson::class) ?: false,
        ]
    ]);
  }
}
