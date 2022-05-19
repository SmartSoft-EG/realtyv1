<?php

namespace App\Http\Controllers;

use App\Models\AccTransDetail;
use Illuminate\Http\Request;

class AccTransDetailController extends Controller
{
    public function index()
    {
        if (request()->by_account_range) {
            return AccTransDetail::latest()->with('transaction')->where('account_id', request()->account_id)->where('created_at', '>=', request()->start)->where('created_at', '<=', request()->end)->get();
        }
    }
}
