<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ruta;
use App\Ciudad;
use App\Models\Viaje;
use DB;

class adminRutasController extends Controller
{
    public function showindex(Request $request){
 
        $data=Ruta::all();
        $msg=""; 
        $ruta= Ruta::get();
        $origen= Ciudad::get();
        $destino= Ciudad::get();
        return view('ruta/rutahome')->with(['data' =>$data])->with("mensaje", $msg)->with("request", $request)->with('origen', $origen)->with('destino',$destino)->with('ruta', $ruta);
    }


    public function crearRuta(Request $request)
    {
        $origen=Ciudad::select('nombre')->distinct()->get();
        $msg="Por favor seleccione el origen de la Ruta:";
        return view('ruta/origen')->with(['data' =>$origen])->with("mensaje", $msg)->with("request", $request);
    }

    public function elegirDestino(Request $request)
    {
        $origen=request('combo');
        $destino=Ciudad::select('nombre')->where('nombre','<>', $origen)->distinct()->get();
        $msg="Por favor seleccione el destino de la Ruta:";
        return view('ruta/destino')->with(['data' =>$destino])->with("origen", $origen)->with("mensaje", $msg)->with("request", $request);
    }

    public function cargarNuevaRuta(Request $request)
    {
        $destino= request('combo');
        $origen= request('origen');
        $msg= "Ruta creada con extio";
        $ruta=$origen.', '.$destino;
        $cont=Ruta::where('nombreRuta', $ruta)->get()->count();

        if($cont == 0){
            Ruta::create([
                'nombreRuta' => $ruta,
                'origen'=> $origen,
                'destino'=> $destino]);
        }
        else{
            $msg="No se puede crear la ruta, ya existe.";
        }
        $data=Ruta::all();
        return view('ruta/rutahome')->with(['data' =>$data])->with("mensaje", $msg)->with("request", $request);
    }

    public function borrarRuta($ruta, Request $request)
    {
        $hoy = date("Y-m-d");
        $msg="La ruta se borro satisfactoriamente";
        $nombreRuta=Ruta::where('id', $ruta)->value('nombreRuta');
        
        $viajesdeRuta=Viaje::where('ruta', $nombreRuta)->where('fecha', '>', $hoy)->count();

        if ($viajesdeRuta > 0){
            $msg="La ruta esta anotada a: ".$viajesdeRuta. " viajes, la misma no se puede borrar";
        }

        else{
            Ruta::where('id',$ruta)->delete();
        }
        $data=Ruta::all();
        return view('ruta/rutahome')->with(['data' =>$data])->with("mensaje", $msg)->with('request',$request);
    }

    public function buscarRuta(Request $request)
    {
        $origen= request('origen');
        $destino= request('destino'); //AGREGAR EL TESTEO DE RUTAS
        $ruta= request('ruta');
        $msg="";

        if ($origen != null){
            $data=Ruta::where('origen', $origen)->get();
                }
        else if($destino !=null){
            $data=Ruta::where('destino', $destino)->get();
        }
        else if ($ruta != null){
            $data=Ruta::where('nombreRuta', $ruta)->get();        }
        else{
            $data=Ruta::get();
        }

        $msg="";
        $ruta= Ruta::get();
        $origen= Ciudad::get();
        $destino= Ciudad::get();
        return view('ruta/rutahome')->with(['data' =>$data])->with("mensaje", $msg)->with("request", $request)->with('origen', $origen)->with('destino',$destino)->with('ruta', $ruta);
    }
   

    public function crearCiudad(Request $request)
    {
        $msg="Ingrese el nombre de la nueva ciudad:";
        return view('ruta/crearCiudad')->with("mensaje", $msg)->with("request", $request);
    }

    public function cargarNuevaCiudad(Request $request)
    {
        $msg="Ciudad cargada correctamente:";
        
        $cont=Ciudad::where('nombre', request('ciudad'))->get()->count();

        if($cont == 0){
            Ciudad::create([
                'nombre' => request('ciudad')
            ]);
        }
        else{
            $msg="No se puede cargar la ciudad, ya existe.";
        }
        $data=Ruta::all();
        return view('ruta/rutahome')->with(['data' =>$data])->with("mensaje", $msg)->with("request", $request);
    }

    public function quitarCiudad(Request $request)
    {
        $data=Ciudad::all();
        $msg="Elija la ciudad que quiere quitar:";
        return view('ruta/quitarCiudad')->with(['data' =>$data])->with("mensaje", $msg)->with("request", $request);
    }

    public function borrarciudad(Request $request)
    {
        $ciudad= request('combo');
        $hoy = date("Y-m-d");

        $msg="La ciudad se borro satisfactoriamente";
        Ciudad::where('nombre', request('combo'))->delete();
        $data=Ruta::all();
        return view('ruta/rutahome')->with(['data' =>$data])->with("mensaje", $msg)->with('request',$request);
    }

}
