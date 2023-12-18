<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\OrdenProduccion;
use App\Models\Limites;
use App\Models\Recetas;
use App\Models\Materiales;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;

use App\Mail\EstadoMaterialChanged;
use App\Mail\OrdenGenerada; 

use PDF;

class OrdenesProduccionController extends Controller
{

    public function index() 
    {
        // Obtener todas las órdenes pendientes
        $todasOrdenes = OrdenProduccion::all();

        // Filtrar las órdenes para que tengan al menos un estado 'pendiente'
        $ordenesProduccion = $todasOrdenes->groupBy('noOrden')
            ->filter(function ($group) {
                // Filtra los grupos que tengan al menos un estado 'pendiente'
                return $group->pluck('status')->contains('pendiente');
            })
            ->flatten();

        // Calcular las sumas
        $sumas = $todasOrdenes->groupBy('noOrden')
            ->map(function ($row) {
                return [
                    'kg_requeridos' => $row->sum('requerido'),
                    'kg_entregados' => $row->sum('entregado'),
                    'kg_pendientes' => $row->sum('pendiente'),
                    'kg_devueltos' => $row->sum('devuelto')
                ];
            });

        // Pasar los resultados a la vista
        return view('ordenesProduccion.index', compact('ordenesProduccion', 'sumas'));
    }

    public function index2() 
    {
        // Obtener todas las órdenes pendientes
        $todasOrdenes = OrdenProduccion::all();

        // Filtrar las órdenes para que solo tengan un estado
        $ordenesProduccion = $todasOrdenes->groupBy('noOrden')
        ->reject(function ($group) {
            // Rechazar los grupos que tienen más de un estado o contienen 'pendiente'
            return $group->pluck('status')->unique()->count() > 1 || $group->pluck('status')->contains('pendiente');
        })
        ->flatten();

        // Calcular las sumas
        $sumas = $ordenesProduccion->groupBy('noOrden')
            ->map(function ($row) {
                return [
                    'kg_requeridos' => $row->sum('requerido'),
                    'kg_entregados' => $row->sum('entregado'),
                    'kg_pendientes' => $row->sum('pendiente'),
                    'kg_devueltos' => $row->sum('devuelto')
                ];
            });

        return view('ordenesProduccion.index2', compact('ordenesProduccion', 'sumas'));
    }

    public function show(OrdenProduccion $orden)
    {
        // Obtener todos los registros con la misma ficha e idreceta
        $ordenProduccionDetalles = OrdenProduccion::where('noOrden', $orden->noOrden)
            ->get();

        return view('ordenesProduccion.show', compact('orden', 'ordenProduccionDetalles'));
    }

    public function cerrarOrden(OrdenProduccion $orden)
    {
        // Verificar si el status ya es 'cerrada'
        if ($orden->status !== 'cerrada') {
            OrdenProduccion::where('noOrden', $orden->noOrden)->update(['status' => 'cerrada']);
        }

        return redirect()->route('ordenesProduccion.index');
    }



    public function show2(OrdenProduccion $orden)
    {
        // Obtener todos los registros con la misma ficha e idreceta
        $ordenProduccionDetalles = OrdenProduccion::where('noOrden', $orden->noOrden)
            ->get();

        return view('ordenesProduccion.show2', compact('orden', 'ordenProduccionDetalles'));
    }

    public function create()
    {
        // Obtener datos necesarios para los desplegables
        $modelos = Recetas::distinct()->pluck('modelo');
        $formatos = Recetas::distinct()->pluck('formato');
        $plantas = Recetas::distinct()->pluck('planta');

        return view('ordenesProduccion.create', compact('modelos', 'formatos', 'plantas'));
    }

    public function formatearNumero($valor)
    {
        // Reemplazar comas por nada y convertir a número
        return floatval(str_replace(',', '', $valor));
    }

    public function store(Request $request)
    {
        try {
            // Decodificar la cadena JSON en un array
            $registros = json_decode($request->input('registros'), true);

            $planta = $request->input('plantaMasFrecuente');

            if(is_null($planta)){
                $planta = "";
            }

            // Validar que hay registros para guardar
            if (empty($registros)) {
                return redirect()->route('ordenesProduccion.create')->with('error', 'No hay registros para guardar.');
            }

            // Obtener la fecha actual en formato YY:MM:DD
            $fechaActual = now()->format('y-m-d');

            // Obtener el usuario autenticado
            $currentUser = Auth::user();

            // Obtener el nombre del usuario autenticado
            $nombreUsuario = $currentUser->name;

            // Obtener el nombre del usuario autenticado
            $email = $currentUser->email;

            $numeroOrden = $this->generateNoOrden();

            // Ahora $registros es un array que puedes recorrer
            foreach ($registros as $registro) {
                // Formatear el valor de requerido
                $requerido = $this->formatearNumero($registro['requerido']);
                
                OrdenProduccion::create([
                    'usuario' => $nombreUsuario,
                    'noOrden' => $numeroOrden,
                    'fecha' => $fechaActual,
                    'codigo_mp' => $registro['codigo_mp'],
                    'descripcion_1' => $registro['descripcion_1'],
                    'requerido' => $requerido,
                    'um' => $registro['um'],
                    'entregado' => 0,
                    'pendiente' => $requerido,
                    'devuelto' => 0,
                    'status' => 'pendiente',
                    'motivo_devolucion' => 'Sin devolución',
                ]);
            }

            // Obtener los correos de los usuarios con rol "almacen"
            $correosAlmacen = DB::table('users')->where('role', 'almacen')->pluck('email')->toArray();

            //$correosAlmacen = ['rgonzalez@cesantoni.com.mx','sespinoza@cesantoni.com.mx']; //Remitentes

            // Agregar el correo del usuario autenticado
            $correosDestino = array_merge($correosAlmacen, [$email]); //Remitentes

            // Crear una nueva instancia de Dompdf
            $pdf = PDF::loadView('pdf.ordenProduccion', compact('registros', 'fechaActual', 'nombreUsuario', 'numeroOrden', 'planta'));

            // Enviar el correo
            Mail::to($correosDestino)->send(new OrdenGenerada($pdf->output(), $numeroOrden));

            // Redirigir al usuario a una nueva ventana para abrir el PDF
            $pdfPath = 'downloads/' . $numeroOrden . '.pdf';
            Storage::put($pdfPath, $pdf->output());

            return response()->file(
                storage_path('app/' . $pdfPath),
                [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => 'inline; filename="' . $numeroOrden . '.pdf"',
                ]
            );

        } catch (\Exception $e) {
            return redirect()->route('ordenesProduccion.create')->with('error', 'Error al guardar registros: ' . $e->getMessage());
        }
    }

    public function generateNoOrden()
    {
        // Obtener la fecha actual en formato YY:MM:DD
        $fechaActual = now()->format('ymd');

        // Obtener el último ID utilizado para la fecha actual desde la base de datos
        $ultimoID = OrdenProduccion::where('fecha', $fechaActual)
            ->orderByRaw('CAST(SUBSTRING_INDEX(noOrden, "/", -1) AS SIGNED) DESC')
            ->value('noOrden');

        // Obtener el número de ID desde la cadena y sumar 1
        $nuevoID = (int)substr(strrchr($ultimoID, "/"), 1) + 1;

        // Llenar los campos de la nueva orden de producción
        $numeroOrden = "OP/{$fechaActual}/{$nuevoID}";

        // Retornar el número de orden generado
        return $numeroOrden;
    }

    public function obtenerFormatos($modelo)
    {
        $formatos = Recetas::where('modelo', $modelo)->distinct()->pluck('formato');
        return response()->json($formatos);
    }

    public function obtenerPlantas($modelo, $formato)
    {
        $plantas = Recetas::where('modelo', $modelo)
            ->where('formato', $formato)
            ->distinct()
            ->pluck('planta');

        return response()->json($plantas);
    }

    public function obtenerRecetas($modelo, $formato, $planta)
    {
        // Obtener el usuario autenticado
        $currentUser = Auth::user();

        // Filtrar las recetas según el rol del usuario
        switch ($currentUser->role) {
            case 'esmalte':
                $recetas = Recetas::where('modelo', $modelo)
                    ->where('formato', $formato)
                    ->where('planta', $planta)
                    ->where('descripcion', '!=', 'TINTA')
                    ->get();
                break;
            case 'linea':
                $recetas = Recetas::where('modelo', $modelo)
                    ->where('formato', $formato)
                    ->where('planta', $planta)
                    ->where(function ($query) {
                        $query->where('descripcion', 'TINTA')
                            ->orWhere('descripcion', 'VEHICULO');
                    })
                    ->get();
                break;
            default:
                $recetas = Recetas::where('modelo', $modelo)
                    ->where('formato', $formato)
                    ->where('planta', $planta)
                    ->get();
                break;

            return response()->json([
                'success' => false,
                'message' => 'Rol no reconocido',
            ]);
        }

        $idRecetasDiferentes = [];

        // Verificar si hay más de una receta con idreceta diferente
        foreach ($recetas as $receta) {
            if (!in_array($receta->idreceta, $idRecetasDiferentes)) {
                $idRecetasDiferentes[] = $receta->idreceta;
            }
        }

        // Si hay dos o más idrecetas diferentes, devuelve un mensaje indicando eso
        if (count($idRecetasDiferentes) >= 2) {
            return response()->json([
                'success' => false,
                'message' => 'Hay dos o más idrecetas diferentes encontradas',
                'recetas' => $recetas
            ]);
        }

        // Si no se encontraron dos o más idrecetas diferentes, devuelve todas las recetas
        return response()->json([
            'success' => true,
            'recetas' => $recetas
        ]);
    }

    public function obtenerRecetasPorId($modelo, $formato, $planta, $idReceta)
    {
        // Obtener el usuario autenticado
        $currentUser = Auth::user();

        // Filtrar las recetas según el rol del usuario
        switch ($currentUser->role) {
            case 'esmalte':
                $recetas = Recetas::where('modelo', $modelo)
                    ->where('formato', $formato)
                    ->where('planta', $planta)
                    ->where('idreceta', $idReceta)
                    ->where('descripcion', '!=', 'TINTA')
                    ->get();
                break;
            case 'linea':
                $recetas = Recetas::where('modelo', $modelo)
                    ->where('formato', $formato)
                    ->where('planta', $planta)
                    ->where('idreceta', $idReceta)
                    ->where(function ($query) {
                        $query->where('descripcion', 'TINTA')
                            ->orWhere('descripcion', 'VEHICULO');
                    })
                    ->get();
                break;
            default:
                $recetas = Recetas::where('modelo', $modelo)
                    ->where('formato', $formato)
                    ->where('planta', $planta)
                    ->where('idreceta', $idReceta)
                    ->get();
                break;

            return response()->json([
                'success' => false,
                'message' => 'Rol no reconocido',
            ]);
        }
        
        return response()->json([
            'success' => true,
            'recetas' => $recetas
        ]);
    }

    public function obtenerCodigoMP($descripcion_1)
    {
        $codigo = Materiales::where('descripcion_1', $descripcion_1)->value('codigo_mp');
        return response()->json(['codigo_mp' => $codigo]);
    }
    
    public function obtenerDescripciones()
    {
        $descripciones = Materiales::pluck('descripcion_1')->sort()->values();
        return response()->json($descripciones);
    }


    public function editDevolver(OrdenProduccion $detalle, $orden)
    {
        return view('ordenesProduccion.editDevolver', compact('detalle', 'orden'));
    }

    public function updateDevolver(Request $request, OrdenProduccion $detalle, $orden)
    {
        // Obtener el valor actual de 'devuelto' y sumarlo con el nuevo valor
        $nuevaCantidadDevuelto = $detalle->devuelto + $request->input('devuelto');

        $codigoMpLimite = $detalle->codigo_mp;

        // Obtener el valor actual de 'entregado' en la tabla Limites
        $valorActualEntregadoLimites = Limites::where('codigo_mp', $codigoMpLimite)
        ->value('entregado');

        $descripcion_1 = $detalle->descripcion_1;

        $nuevaCantidadEntregado = $detalle->entregado - $request->input('devuelto');

        // Actualizar el detalle de la orden de producción
        $detalle->update([
            'devuelto' => $nuevaCantidadDevuelto,
            'entregado' => $nuevaCantidadEntregado,
            'motivo_devolucion' => $request->input('motivo_devolucion'),
        ]);

        // Calcular el nuevo valor de 'entregado' en la tabla Limites
        $nuevoEntregadoLimites = $valorActualEntregadoLimites - $request->input('devuelto');

        // Actualizar 'entregado' en la tabla Limites
        Limites::where('codigo_mp', $codigoMpLimite)
        ->update(['entregado' => $nuevoEntregadoLimites]);

        $porcentajeUso = Limites::where('codigo_mp', $codigoMpLimite)
        ->value('porcentaje_uso');

        $statusActual = Limites::where('codigo_mp', $codigoMpLimite)
        ->value('status');

        $limiteMaterial = Limites::where('codigo_mp', $codigoMpLimite)
        ->value('limite');

        // Actualizar 'status' en la tabla Limites según el porcentaje de uso
        $status = 'Sin uso';
        if ($porcentajeUso > 0 && $porcentajeUso < 70) {
            $status = 'ok';
        } elseif ($porcentajeUso >= 70 && $porcentajeUso <= 100) {
            $status = 'precaución';
        } elseif ($porcentajeUso > 100) {
            $status = 'excedido';
        }

        // Actualizar 'status' en la tabla Limites
        Limites::where('codigo_mp', $codigoMpLimite)
        ->update(['status' => $status]);

        // Aquí comprobamos si el status ha cambiado y enviamos el correo en caso afirmativo
        if (($status === 'precaución' || $status === 'excedido') && $status !== $statusActual) {
            // Configurar los datos para el correo
            $data = [
                'descripcion_1' => $descripcion_1,
                'codigoMpLimite' => $codigoMpLimite,
                'statusAnterior' => $statusActual,
                'status' => $status,
                'limiteMaterial' => $limiteMaterial,
                'nuevoEntregadoLimites' => $nuevoEntregadoLimites,
                'porcentajeUso' => $porcentajeUso,
            ];

            // Cambiar con la lista de direcciones de correo a las que deseas enviar
            $correosDestino = ['sjexis47@gmail.com'];

            // Enviar el correo
            Mail::to($correosDestino)->send(new EstadoMaterialChanged($data));
        }

        if ($detalle->status === "cerrada") {
            // Redireccionar con un mensaje de éxito
            return redirect()->route('ordenesProduccion.show2', $orden)->with('success', 'Material surtido exitosamente.');
        } else {
            // Redireccionar con un mensaje de éxito
            return redirect()->route('ordenesProduccion.show', $orden)->with('success', 'Material surtido exitosamente.');
        }
    }

    public function guardarSurtido(Request $request)
    {
        // Obtener detalles del request
        $detalles = $request->input('detalles');

        // Iterar sobre los detalles y guardar en la base de datos
        foreach ($detalles as $detalle) {
            $valoresActuales = OrdenProduccion::where('id', $detalle['detalleId'])->first();

            if ($valoresActuales) {
                $entregado = $detalle['nuevoEntregado'];
                $codigoMpLimite = $valoresActuales->codigo_mp;
                $descripcion_1 = $valoresActuales->descripcion_1;

                if (!is_null($entregado) && ($entregado !== '0' || $entregado !== '' || !is_nan($entregado))) {
                    $nuevoValorEntregado = $valoresActuales->entregado + $entregado;

                    // Obtener el valor actual de 'entregado' en la tabla Limites
                    $limites = Limites::where('codigo_mp', $codigoMpLimite);

                    $limiteMaterial = Limites::where('codigo_mp', $codigoMpLimite)
                        ->value('limite');

                    if ($limites->exists()) {
                        $valorActualEntregadoLimites = $limites->value('entregado');

                        // Calcular el nuevo valor de 'entregado' en la tabla Limites
                        $nuevoEntregadoLimites = $valorActualEntregadoLimites + $entregado;

                        // Actualizar 'entregado' en la tabla Limites
                        $limites->update(['entregado' => $nuevoEntregadoLimites]);
                    } else {
                        $descripcion_1 = $valoresActuales->descripcion_1;
                        $mes = Limites::value('mes');
                        $año = Limites::value('año');
                        $limite = 1;

                        // Crear un nuevo registro en la tabla Limites
                        Limites::create([
                            'codigo_mp' => $codigoMpLimite,
                            'descripcion_1' => $descripcion_1,
                            'mes' => $mes,
                            'año' => $año,
                            'limite' => $limite,
                        ]);

                        $nuevoEntregadoLimites = $entregado;

                        // Actualizar 'entregado' en la tabla Limites
                        Limites::where('codigo_mp', $codigoMpLimite)
                            ->update(['entregado' => $nuevoEntregadoLimites]);
                    }

                    // Actualizar 'entregado' en la tabla OrdenProduccion
                    OrdenProduccion::where('id', $detalle['detalleId'])
                        ->update(['entregado' => $nuevoValorEntregado]);

                    $porcentajeUso = Limites::where('codigo_mp', $codigoMpLimite)
                    ->value('porcentaje_uso');

                    $statusActual = Limites::where('codigo_mp', $codigoMpLimite)
                    ->value('status');

                    // Actualizar 'status' en la tabla Limites según el porcentaje de uso
                    $status = 'Sin uso';
                    if ($porcentajeUso > 0 && $porcentajeUso < 70) {
                        $status = 'ok';
                    } elseif ($porcentajeUso >= 70 && $porcentajeUso <= 100) {
                        $status = 'precaución';
                    } elseif ($porcentajeUso > 100) {
                        $status = 'excedido';
                    }

                    // Actualizar 'status' en la tabla Limites
                    Limites::where('codigo_mp', $codigoMpLimite)
                    ->update(['status' => $status]);

                    // Aquí comprobamos si el status ha cambiado y enviamos el correo en caso afirmativo
                    if (($status === 'precaución' || $status === 'excedido') && $status !== $statusActual) {
                        // Configurar los datos para el correo
                        $data = [
                            'descripcion_1' => $descripcion_1,
                            'codigoMpLimite' => $codigoMpLimite,
                            'statusAnterior' => $statusActual,
                            'status' => $status,
                            'limiteMaterial' => $limiteMaterial,
                            'nuevoEntregadoLimites' => $nuevoEntregadoLimites,
                            'porcentajeUso' => $porcentajeUso,
                        ];

                        // Cambiar con la lista de direcciones de correo a las que deseas enviar
                        $correosDestino = ['sjexis47@gmail.com'];

                        // Enviar el correo
                        Mail::to($correosDestino)->send(new EstadoMaterialChanged($data));
                    }

                }
            }
        }

        // Devolver respuesta (puedes personalizar según tus necesidades)
        return redirect()->back()->with('success', 'Surtido guardado exitosamente');
    }

}