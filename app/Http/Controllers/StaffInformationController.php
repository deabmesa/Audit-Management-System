<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StaffInformationController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->string('keyword')->toString();

        $staff = DB::connection('external_pgsql')
            ->table('staff_profiles')
            ->select(['staff_id', 'full_name', 'department_name', 'branch_name', 'email'])
            ->when($keyword, fn ($query) => $query->where('full_name', 'ilike', "%{$keyword}%"))
            ->orderBy('full_name')
            ->paginate(15);

        return view('staff.index', compact('staff', 'keyword'));
    }
}
