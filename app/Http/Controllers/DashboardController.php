<?php

namespace App\Http\Controllers;

use App\Projects;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function show()
    {
        $hacktoberfest = (bool) Carbon::now()->isSameMonth(Carbon::parse('October'));

        if (! Cache::has('projects')) {
            return response('Temporarily unavailable.');
        }

        return view('dashboard', [
            'projects' => (new Projects)->all()->filter->debtScore(),
            'hacktoberfest' => $hacktoberfest,
        ]);
    }
}
