<?php

namespace App\Http\Controllers;

use App\Job;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class JobsController extends Controller
{
    public function jobs()
    {
        $jobs = Job::with('company')->get();
        return response()->json($jobs);
    }

    public function buscarPromocoes()
    {
        $jobs = "teste";
        return response()->json(json_encode($jobs));
    }
}
