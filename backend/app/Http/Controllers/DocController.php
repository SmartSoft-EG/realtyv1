<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocController extends Controller
{
    public function store()
    {
        //  return request();

        $path = request()->file->store('/files', ['disk' => 'public']);
        return ['url' => url('/storage') . '/' . $path];
    }
}
