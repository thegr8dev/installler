<?php

namespace Mediacity\Installer\Http\Controllers;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Jackiedo\DotenvEditor\Facades\DotenvEditor;

class InstallerController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | InstallerController
    |--------------------------------------------------------------------------
    |
    | This controller performs the operation for installation of application.
    |
    */

    /**
     * This function view the tos page for installer.
     * 
     * @return Renderable
     */

    public function eula(){

        if(config('installer.install_status')  == 0){
            return view('installer::install.eula');
        }

        return redirect('/');
        
    }

     /**
     * This function store  the tos page and redirect for server check.
     * 
     * @return RedirectResponse
     */

    public function agreeeula(Request $request){

        if(config('installer.install_status')  == 0){
            return redirect('/install/servercheck');
        }

        return redirect('/');

    } 

    /**
     * This function render server check view.
     * 
     * @param $request
     * @return Renderable
     */
    
    public function servercheck(Request $request){

        if(config('installer.install_status')  == 0){
            return view('installer::install.server');
        }

        return redirect('/');
    }

     /**
     * This function redirect for verify envato license.
     * 
     * @return RedirectResponse
     */

    public function submitservercheck(Request $request){

        if(config('installer.install_status')  == 0){
            return redirect('/install/verify-license');
        }

        return redirect('/');
    }

    /**
     * This function render the envato license view.
     * 
     * @return Renderable
     */

    public function license(){

        if(config('installer.install_status')  == 0){
            return view('installer::install.license');
        }

        return redirect('/');
    }

    /**
     * This function inject the InitializeController for licnese verification.
     * 
     * @return Response json
     */

    public function verifylicense(){

      

        if(config('installer.install_status')  == 0){
            $licnese = new InitializeController;
            return $licnese->verify(request());
        }

        return redirect('/');

    }

    /**
     * This function render the database setup view.
     * 
     * @return Renderable
     */

    public function dbsetup(Request $request){

        if(config('installer.install_status')  == 0){
            return view('installer::install.dbsetup');
        }

        return redirect('/');
    }

    /**
     * This function store the database details into .env file.
     * 
     * @return RedirectResponse
     */

    public function submitdbsetup(Request $request){

        if(config('installer.install_status')  == 0){

            $db_setup = DotenvEditor::setKeys([

                'DB_HOST'     => filter_var($request->DB_HOST),
                'DB_PORT'     => filter_var($request->DB_PORT),
                'DB_DATABASE' => filter_var($request->DB_DATABASE),
                'DB_USERNAME' => filter_var($request->DB_USERNAME),
                'DB_PASSWORD' => filter_var($request->DB_PASSWORD)

            ]);

            $db_setup->save();

            return redirect('/install/finish');
        }

       return redirect('/');

    }

    /**
     * This function store the database details and migrate the table and seeds.
     * 
     * @return MixedResponse (Renderable, RedirectRespnse)
     */

    public function finish(Request $request){

        if(config('installer.install_status')  == 0){

            try{

                DB::connection()->getPdo();

                $this->migrate();

                $install_done = DotenvEditor::setKeys([
                    'IS_INSTALLED' => '1'
                ]);

                $install_done->save();

                $users = User::all();

                return view('installer::install.finish',compact('users'));
                

            }catch(\Exception $e){

                // Reseting the database and @return back

                Artisan::call('migrate:reset');
                
                return back()->with('error',$e->getMessage());

            }
        }

        return redirect('/');

        
    }

    /**
     * This function calling the migrate and seed command.
     * 
     * @return Boolean
     */

    public function migrate(){

        set_time_limit(-1);
        
        Artisan::call('migrate:fresh --seed');
        
    }
}
