<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;

class DeviceController extends Controller {

    /* return view based on device*/
    public function detectDevice() {
        $agent = new Agent();

        $mobileDevice = $agent->isMobile();
        if ($mobileDevice) {
            return view('devices/mobile');
        }

        $tabletDevice = $agent->isTablet();
        if ($tabletDevice) {
            return view('devices/tablet');
        }

        $desktopDevice = $agent->isDesktop();
        if ($desktopDevice) {
            return view('devices/desktop');
        }
    }

    /* return result based on device*/
    public function detectDevices() {
        $agent = new Agent();

        $mobileDevice = $agent->isMobile();
        if ($mobileDevice) {
            $result = 'This is Mobile';
        }

        $tabletDevice = $agent->isTablet();
        if ($tabletDevice) {
            $result = 'This is Tablet';
        }

        $desktopDevice = $agent->isDesktop();
        if ($desktopDevice) {
            $result = 'This is Desktop';
        }

        return view('devices/devices', compact('result'));

    }
}
