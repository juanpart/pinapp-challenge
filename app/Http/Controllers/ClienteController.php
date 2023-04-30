<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use Carbon\Carbon;

class ClienteController extends Controller{

    public function index() {
        
        if ( !($datosClientes = Cliente::select(['id', 'nombre', 'apellido', 'edad', 'nacimiento'])->get()) ) {
            return response()->json([
                'res' => false,
                'message' => 'No hay datos en la BD'
            ]);
        }
        $hoy = date('Y-m-d');
        
        foreach ($datosClientes as $cliente) {
            $fecha_fin = date("Y-m-d", strtotime($cliente->nacimiento . "+ " . 85 . " year"));
            if ($fecha_fin < $hoy) {
                $fecha_fin = $hoy;
            }

            $cliente->muerte = $fecha_fin;
        }

        return response()->json($datosClientes);

    }

    public function promedio() {

        if ( !($datosClientes = Cliente::select(['edad'])->get()) || !($promedio = CLiente::avg('edad')) || !($totalClientes = Cliente::count('id')) ) {
            return response()->json([
                'res' => false,
                'message' => 'No hay datos en la BD'
            ]);
        }

        $suma = 0;       
        foreach ($datosClientes as $cliente) {
            $suma += pow($cliente->edad - $promedio, 2);
        }
        $desviacion = sqrt($suma / $totalClientes);

        return response()->json(['desviacion' =>round($desviacion, 2), 'promedio' => $promedio]);
    }
    
    public function crear(Request $request) {
        
        $this->validate($request, [
            'nombre' => 'required|string|min:2|max:250',
            'apellido' => 'required|string|min:2|max:250',
            'nacimiento' => 'required|date'
        ]);
        $datosCliente = $request->all();
            
        $edad = Carbon::parse(date('Y-m-d'))->diffInYears(Carbon::parse($datosCliente['nacimiento']));
        $datosCliente['edad'] = $edad;
        
        Cliente::create($datosCliente);
         
        return response()->json([
            'res' => true,
            'message' => 'Se registró el nuevo cliente'
        ]);

    }
}