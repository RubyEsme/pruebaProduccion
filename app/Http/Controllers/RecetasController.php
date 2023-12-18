<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use League\Csv\Writer;

use PhpOffice\PhpSpreadsheet\IOFactory;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use App\Models\Recetas;
use App\Models\Materiales;

class RecetasController extends Controller
{
    public function index()
    {
        // Obtener todas las recetas de la base de datos
        $todasRecetas = Recetas::all();

        // Filtrar las recetas para quitar duplicados en SKU e idreceta
        $recetas = $todasRecetas->unique(function ($item) {
            return $item->sku . $item->idreceta;
        });

        // Pasar las recetas filtradas a la vista
        return view('recetas.index', compact('recetas'));
    }

    public function createDetalle($idReceta, $detalles)
    {
        // Convierte la cadena de detalles a un array
        $detallesArray = explode(',', $detalles);

        $detallesReceta = Recetas::find($detallesArray);
        $codigoMps = $detallesReceta->pluck('codigo_mp')->toArray();

        $materiales = Materiales::all();

        // Obtén el detalle específico relacionado con la receta
        $receta = Recetas::find($idReceta);

        // Pasar los códigos de MP a la vista
        return view('recetas.createDetalle', compact('receta', 'materiales', 'idReceta', 'codigoMps'));
    }

    public function storeDetalle(Request $request, $idReceta)
    {
        // Obtener la receta actual
        $receta = Recetas::find($idReceta);

        // Obtener los campos adicionales de $receta
        $recetaCampos = [
            'sku' => $receta->sku,
            'formato' => $receta->formato,
            'modelo' => $receta->modelo,
            'tipo' => $receta->tipo,
            'um' => $receta->um,
            'planta' => $receta->planta,
            'linea' => $receta->linea,
            'idreceta' => $receta->idreceta,
            'ficha' => $receta->ficha,
        ];

        // Combinar los campos del formulario y de la receta
        $campos = array_merge($recetaCampos, $request->all());

        // Crear el nuevo detalle
        Recetas::create($campos);

        // Puedes redirigir o hacer cualquier otra lógica aquí después de guardar el detalle

        // Ejemplo de redirección a la vista de edición de receta
        return redirect()->route('recetas.edit', $idReceta)->with('success', 'Detalle agregado exitosamente');
    }

    public function show(Recetas $receta)
    {
        // Obtener todos los registros con la misma ficha e idreceta
        $recetasDetalles = Recetas::where('ficha', $receta->ficha)
            ->where('idreceta', $receta->idreceta)
            ->get();

        return view('recetas.show', compact('receta', 'recetasDetalles'));
    }
    
    public function edit(Recetas $receta)
    {
        // Obtener todos los registros con la misma ficha e idreceta
        $recetasDetalles = Recetas::where('ficha', $receta->ficha)
            ->where('idreceta', $receta->idreceta)
            ->get();

        // Obtener los IDs de los detalles para excluir
        $detallesExcluir = $recetasDetalles->pluck('id')->toArray();

        // Obtener la lista de materiales
        $materiales = Materiales::all();

        return view('recetas.edit', compact('receta', 'recetasDetalles', 'detallesExcluir', 'materiales'));
    }

    public function editDetalle(Recetas $detalle, $idReceta)
    {
        // Obtener la lista de materiales
        $materiales = Materiales::all();

        return view('recetas.editDetalle', compact('detalle', 'idReceta', 'materiales'));
    }

    public function updateDetalle(Request $request, Recetas $detalle, $idReceta)
    {
        // Valida los campos según tus necesidades
        $request->validate([
            'cantidad' => 'required',
        ]);

        // Obtén el detalle específico relacionado con la receta
        $detalle = Recetas::find($detalle->id);

        // Actualiza los campos con los valores del formulario
        $detalle->update([
            'cantidad' => $request->input('cantidad'),
            'observaciones' => $request->input('observaciones'),
        ]);

        return redirect()->route('recetas.edit', $idReceta)->with('success', 'Material actualizado exitosamente.');
    }
 

    public function update(Request $request, Recetas $receta)
    {
        // Valida los campos según tus necesidades
        $request->validate([
            'sku' => 'required',
            'formato' => 'required',
            'modelo' => 'required',
            'tipo' => 'required',
            'planta' => 'required',
            'linea' => 'required',
            'idreceta' => 'required',
        ]);
    
        // Obtiene las IDs de los detalles a excluir
        $detallesExcluir = $request->input('detallesExcluir', []);

        // Asegúrate de que $detallesExcluir siempre sea un array
        $detallesExcluir = is_array($detallesExcluir) ? $detallesExcluir : [];

        // Si el array no está vacío, convierte las IDs a un array numérico
        if (!empty($detallesExcluir)) {
            // Divide la cadena en valores individuales y los convierte a números
            $detallesExcluir = array_map('intval', explode(',', reset($detallesExcluir)));
        }

        // Construye la nueva ficha concatenada
        $nuevaFicha = $request->input('planta') . '-' . $request->input('linea') . '-' . $request->input('idreceta') . '-' . $request->input('sku');

        // Verifica si la nueva ficha ya existe en otros registros excluyendo los detalles seleccionados
        $fichaExistente = Recetas::where('ficha', $nuevaFicha)
        ->whereNotIn('id', $detallesExcluir) // Excluir las IDs de detalles seleccionadas
        ->exists();

        // Si la ficha ya existe, redirecciona con un mensaje de error
        if ($fichaExistente) {
            return redirect()->back()->with('error', 'La ficha ya existe en otros registros. No se puede actualizar.');
        }
    
        // Obtiene todos los detalles relacionados con la receta
        $recetasDetalles = Recetas::where('ficha', $receta->ficha)
            ->where('idreceta', $receta->idreceta)
            ->get();
    
        // Itera sobre los detalles y actualiza cada uno
        foreach ($recetasDetalles as $detalle) {
            // Actualiza los detalles con los datos del formulario
            $detalle->update([
                'sku' => $request->input('sku'),
                'formato' => $request->input('formato'),
                'modelo' => $request->input('modelo'),
                'tipo' => $request->input('tipo'),
                'planta' => $request->input('planta'),
                'linea' => $request->input('linea'),
                'idreceta' => $request->input('idreceta'),
                // Agrega otros campos que necesitas actualizar
            ]);
        }
    
        return redirect()->route('recetas.index')->with('success', 'Receta actualizada exitosamente.');
    }            

    public function destroy(Recetas $receta)
    {
        try {
            // Obtén la ficha del registro que se está eliminando
            $ficha = $receta->ficha;

            // Elimina todos los registros con la misma ficha
            Recetas::where('ficha', $ficha)->delete();

            return redirect()->route('recetas.index')->with('success', 'Recetas eliminadas exitosamente.');
        } catch (\Exception $e) {
            // Maneja cualquier excepción que pueda ocurrir durante la eliminación
            return redirect()->back()->with('error', 'Error al eliminar las recetas: ' . $e->getMessage());
        }
    }

    public function destroyDetalle(Recetas $detalle)
    {
        // Obtener la ficha del detalle antes de eliminarlo
        $ficha = $detalle->ficha;

        // Eliminar el detalle por el campo detalle-id
        Recetas::where('id', $detalle->id)->delete();

        // Buscar el primer registro con la misma ficha después de la eliminación
        $primerRegistroDespuesEliminacion = Recetas::where('ficha', $ficha)->first();

        // Redirigir al primer registro con la misma ficha
        if ($primerRegistroDespuesEliminacion) {
            return redirect()->route('recetas.edit', ['receta' => $primerRegistroDespuesEliminacion->id])
                ->with('success', 'Detalle eliminado exitosamente.');
        }

        // Si no se encuentra ningún registro con la misma ficha, redirigir a alguna página predeterminada
        return redirect()->route('recetas.index')->with('success', 'Detalle eliminado exitosamente.');
    }


    public function obtenerDescripciones(Request $request)
    {
        $codigoMp = $request->input('codigo_mp');

        // Obtén las opciones de descripción_1 basadas en $codigoMp
        $materiales = Materiales::where('codigo_mp', $codigoMp)->get(['descripcion_1']);

        // Devuelve las opciones en formato JSON
        return response()->json(['options' => $materiales]);
    }

  public function uploadRecetas(Request $request)
{
    $request->validate([
        'excel_file' => 'required|mimes:xlsx,xls|max:2048', // Adjust max file size if needed
    ]);

    // Load the uploaded Excel file
    $file = $request->file('excel_file');
    $spreadsheet = IOFactory::load($file);

    // Get the active sheet
    $sheet = $spreadsheet->getActiveSheet();

    
    
    // Get the highest row with data
    $highestRow = $sheet->getHighestRow();

    // Define the starting row
    $row = 2;

    // Delete all existing records in the Recetas table
    Recetas::truncate();

    // Loop through the Excel data and insert into the database
    for ($i = $row; $i <= $highestRow; $i++) {
        // Retrieve values from each cell
        $sku = $sheet->getCell('A' . $i)->getValue();
        $formato = $sheet->getCell('B' . $i)->getValue();
        $modelo = $sheet->getCell('C' . $i)->getValue();
        $tipo = $sheet->getCell('D' . $i)->getValue();
        $codigo_mp = $sheet->getCell('E' . $i)->getValue();
        $descripcion = $sheet->getCell('F' . $i)->getValue();
        $descripcion_1 = $sheet->getCell('G' . $i)->getValue();
        $cantidad = $sheet->getCell('H' . $i)->getValue();
        $um = $sheet->getCell('I' . $i)->getValue();
        $planta = $sheet->getCell('J' . $i)->getValue();
        $linea = $sheet->getCell('K' . $i)->getValue();
        $rodillo_digital = $sheet->getCell('L' . $i)->getValue();
        $idreceta = $sheet->getCell('M' . $i)->getValue();
        $proveedor = $sheet->getCell('N' . $i)->getValue();

        // Insert into the database
        Recetas::create([
            'sku' => $sku,
            'formato' => $formato,
            'modelo' => $modelo,
            'tipo' => $tipo,
            'codigo_mp' => $codigo_mp,
            'descripcion' => $descripcion,
            'descripcion_1' => $descripcion_1,
            'cantidad' => $cantidad,
            'um' => $um,
            'planta' => $planta,
            'linea' => $linea,
            'rodillo_digital' => $rodillo_digital,
            'idreceta' => $idreceta,
            'proveedor' => $proveedor,
        ]);
    }
    // Delete records where 'codigo_mp' is null
    Recetas::whereNull('codigo_mp')->delete();

    // Provide a response or redirect as needed
    return redirect()->back()->with('success', 'Data has been successfully imported into the database.');
}

}
