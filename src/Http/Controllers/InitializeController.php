<?php
namespace Mediacity\Installer\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class InitializeController extends Controller
{
    public function verify($request)
    {

        $d = \Request::getHost();
        $domain = str_replace("www.", "", $d);

        $alldata = ['app_id' => config('installer.envato_appid'), 'ip' => $request->ip(), 'domain' => $domain, 'code' => filter_var($request->code)];

        $data = $this->make_request($alldata);

        if ($data['status'] == '1') {
            return redirect('/install/dbsetup');
        } elseif ($data['msg'] == 'Already Register') {
            return back()->with('error',__('User is already registered'));
        } else {
            session()->flash('error',$data['msg']);
            return back();
        }
    }

    public function make_request($alldata)
    {
        $response = Http::post('https://mediacity.co.in/purchase/public/api/verifycode', [

            'app_id' => $alldata['app_id'],
            'ip'     => $alldata['ip'],
            'code'   => $alldata['code'],
            'domain' => $alldata['domain']

        ]);

        $result = $response->json();

        if ($response->successful()) {
            if ($result['status'] == '1') {

                $lic_json = array(

                    'name' => request()->user_id,
                    'code' => $alldata['code'],
                    'type' => __('envato'),
                    'domain' => $alldata['domain'],
                    'lic_type' => __('regular'),
                    'token' => $result['token'],

                );

                $file = json_encode($lic_json,JSON_PRETTY_PRINT);

                $filename = 'license.json';

                Storage::disk('local')->put('/keys/' . $filename, $file);

                return array(
                    'msg' => $result['message'],
                    'status' => '1',
                );

            } else {
                $message = $result['message'];

                return array(
                    'msg' => filter_var($message),
                    'status' => '0',
                );
            }
        } else {
            $message = __("Failed to validate");

            return array(
                'msg' => filter_var($message),
                'status' => '0',
            );
        }

    }

}
