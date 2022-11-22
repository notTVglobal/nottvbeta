<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class VideoController extends Controller
{
    public function mistApi(Request $request) {

        $challenge = $request->challenge;
        $password = $request->password;


//        dd($challenge);

        // do we add the challenge and the password together?

//        $newHashedPassword = [$challenge + $password];

        // or do we concatenate them?

        $newHashedPassword = ($password . '+' . $challenge);
        $x = hash('md5', $newHashedPassword);
        dd($newHashedPassword);
        return redirect()->route('video')->with('message', $newHashedPassword);

    }
}
