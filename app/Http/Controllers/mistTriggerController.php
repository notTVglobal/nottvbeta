<?php

namespace App\Http\Controllers;

use App\Models\mistTrigger;
use Illuminate\Http\Request;

class mistTriggerController extends Controller
{
    public function logTrigger(Request $request) {

        if ($request->header('X-Trigger')) {

//            $payload = array(["stream","ip","protocol", "request_url"]);

            mistTrigger::create(['data' => $request]);
            return response('1', 'http://beta.local:8081/api/mistTrigger')->header('X-Trigger', 'STREAM_PUSH');
        }

    }
}
