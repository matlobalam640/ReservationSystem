<?php

namespace App\Http\Controllers;

use App\Models\Aircraft;
use App\Services\Operations\ManifestService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ManifestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $aircraft = Aircraft::query()->where('is_active', true)->orderBy('tail_number')->get();

        return view('manifest.index', compact('aircraft'));
    }

    public function download(Request $request, ManifestService $manifestService)
    {
        $data = $request->validate([
            'aircraft_id' => ['required', 'exists:aircraft,id'],
            'date' => ['required', 'date'],
        ]);

        $pdf = $manifestService->generate((int) $data['aircraft_id'], Carbon::parse($data['date']));

        return $pdf->download('manifest-'.$data['date'].'.pdf');
    }
}
