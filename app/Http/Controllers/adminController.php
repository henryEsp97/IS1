<?php

namespace App\Http\Controllers;
use App\Combi;
use App\Models\Viaje;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class adminController extends Controller
{
    public function show(Request $request)
    {
        $request->user()->authorizeRoles(['admin']);
        return view("vistasDeAdmin/homeAdmin")->with('request',$request);
    }
    public function showGestionCuentas(Request $request){
        $request->user()->authorizeRoles(['admin']);
        $data=User::all();
        $msg="";
        return view('vistasDeAdmin/gestionDeCuentas')->with(['data' =>$data])->with("mensaje", $msg)->with("request", $request);
    }

    public function showUnPerfil(User $user, Request $request)
    {   $data = User::where('dni','=',$user->dni)->get();
        $msg = "";
        
        
        return view('vistasDeAdmin/verPerfil')->with('data',$data)->with('msg', $msg)->with('request',$request);
    }

    public function showBuscarCuenta(Request $request){
        $request->user()->authorizeRoles(['admin']);
        $data=User::where('email','=',request('email'))->get();
        $msg="";
        return view('vistasDeAdmin/gestionDeCuentas')->with(['data' =>$data])->with("mensaje", $msg)->with("request", $request);
    }



    public function showGestionCombis(Request $request){
   
        $data=Combi::all();
        $msg="";
        return view('vistasDeAdmin/gestionDeCombis')->with(['data' =>$data])->with("mensaje", $msg)->with("request", $request);
    }

    public function showCrearCombi(Request $request){
        $request->user()->authorizeRoles(['admin']);
        $data = '';
        return view('vistasDeAdmin/crearCombi')->with(['data' =>$data])->with("request", $request);
    }
    public function showBuscarCombi(Request $request){
        $request->user()->authorizeRoles(['admin']);
        $data=Combi::where('patente','=',request('patente'))->get();
        $msg="";
        return view('vistasDeAdmin/gestionDeCombis')->with(['data' =>$data])->with("mensaje", $msg)->with("request", $request);
    }


    public function store(Request $request)
    {
        $patente = request('patente');
        $tipoCombi = request('tipo');
        $capacidad =0;

        $msg="La Patente se cargo con exito";
        if ($tipoCombi == '1'){
            $capacidad = 20;
            $tipoCombi = 'comoda';
        }
        else{
            $capacidad = 22;
            $tipoCombi = 'super-comoda';
        }
        $cantidad= Combi::where("patente", "=", $patente)->count();
        $patente=strtoupper($patente);
        $patente=trim($patente);
        //CODIGO DE VALIDACION DE PATENTEBlade::component
        $resultado = false;
        $parteNum = '';
        $parteStr ='';

        if (strlen($patente) == 6){
            $parteStr= substr($patente, 0, 3);
            $parteNum= substr($patente, 3, 3);
        } 
        if (strlen($patente) == 7){
            $parteStr= substr($patente, 0, 2);
            $parteNum= substr($patente, 2, 3);
            $parteStr= $parteStr.substr($patente, 5, 2);
        }
        if ((is_numeric($parteNum))&&(is_string($parteStr))){
            $resultado=true;
        }
        //FIN CODIGO VALIDACION
        
        if ($cantidad == 0 ){
            if($resultado){
                
                Combi::create ([
                    'cant asientos' => $capacidad,
                    'patente'=> $patente,
                    'tipo' => $tipoCombi,
                    ]);
            }
            else{
                $msg="La patente es invalida.";
            }
        }
        else{
            $msg=" La patente ya existe";
        }
        return view('vistasDeAdmin/crearCombi', ['data' =>$msg])->with('request',$request);
        
    }


    public function destroy($id, Request $request)
    {
        $hoy = date("Y-m-d");
        $msg="La patente se elimino con éxito";
        $patente = Combi::where('id',$id)->get('patente');
        if (strlen($patente)==22){
            $patente= substr($patente, 13,6);
        }
        else{
            $patente= substr($patente, 13,7);
        }
        $cantidadViajesFuturos= Viaje::where('patente', $patente)->where('fecha','>',$hoy)->count();
        if ($cantidadViajesFuturos == 0){
            Combi::where('id',$id)->delete();
        }
        else{
            $msg="La patente combi con patente: $patente tiene: $cantidadViajesFuturos viajes por hacer";
        }
        $data=Combi::all();
        
        return view('vistasDeAdmin/gestionDeCombis')->with('request',$request)->with('data',$data)->with("mensaje", $msg);
    }  



    public function showActCombi($combi, Request $request)
    {
        $msg="Se cambio el modo de la combi";
        $tipo = Combi::where('id',$combi)->get('tipo');
        if (strlen($tipo)==25){
            $tipo = substr($tipo,10,12 );
        }
        else {
            $tipo = substr($tipo, 10,6);
        }

        $patente = Combi::where('id',$combi)->get('patente');
        if (strlen($patente)==22){
            $patente= substr($patente, 13,6);
        }
        else{
            $patente= substr($patente, 13,7);
        }
        if ($tipo == 'comoda'){
            Combi::where('id',$combi)->update([
                'tipo' => 'super-comoda',
                'cant asientos' => '22',
                'patente' => $patente,
            ]);
        }
        else{
            $cantViajesCapacidadMaxima=Viaje::where('patente',$patente)->where('cant disponibles','=','0')->count();
            if ($cantViajesCapacidadMaxima==0){
                Combi::where('id',$combi)->update([
                    'tipo' => 'comoda',
                    'cant asientos' => '20',
                    'patente' => $patente,
                ]);
            }
            else {
                $msg="No se puede cambiar el modo, la misma tiene viajes con capacidad maxima";
            }
        }
        $data=Combi::all();
        return view('vistasDeAdmin/gestionDeCombis')->with('data',$data)->with('mensaje',$msg)->with('request',$request);  
    } 
}

    
