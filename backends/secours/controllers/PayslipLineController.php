<?php

namespace App\Http\Controllers;

use App\Models\PayslipLine;

class PayslipLineController extends Controller
{
    public function show($id)
    {
        $line = PayslipLine::with('payrollLineType')->find($id);
        return $line ? response()->json($line, 200) : response()->json(['message' => 'Ligne introuvable'], 404);
    }
}
