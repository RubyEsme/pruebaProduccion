<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use PhpOffice\PhpSpreadsheet\IOFactory;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


use App\Models\LimitesHistorico;
use App\Models\Limites;
use App\Models\Materiales;

class LimitesController extends Controller
{
    public function index()
    {
        // Obtener todas los materiales de la base de datos donde codigo_mp no sea igual a 0
        $limites = Limites::where('codigo_mp', '<>', '0')->get();

        // Pasar los materiales a la vista
        return view('limites.index', compact('limites'));
    }

    public function indexCotejo()
    {
        // Obtener todas los materiales de la base de datos donde codigo_mp no sea igual a 0
        $limites = Limites::where('codigo_mp', '<>', '0')->get();

        // Pasar los materiales a la vista
        return view('limites.indexCotejo', compact('limites'));
    }

    public function historico()
    {
        // Obtener todas los materiales de la base de datos
        $limites = LimitesHistorico::all();

        // Pasar los materiales a la vista a la vista
        return view('limites.historico', compact('limites'));
    }

    public function historicoCotejo()
    {
        // Obtener todas los materiales de la base de datos
        $limites = LimitesHistorico::all();

        // Pasar los materiales a la vista a la vista
        return view('limites.historicoCotejo', compact('limites'));
    }

    public function create()
    {
        $materiales = Materiales::whereNotIn('codigo_mp', Limites::pluck('codigo_mp')->toArray())->get();

        $limites = Limites::all();

        $codigoMps = $limites->pluck('codigo_mp')->toArray();

        // Pasar los códigos de MP a la vista
        return view('limites.create', compact('codigoMps', 'materiales'));
    }

    
    public function store(Request $request)
    {
        Limites::create($request->all());

        return redirect()->route('limites.index')->with('success', 'Limite creado exitosamente.');
    }

    public function edit(Limites $limite)
    {
        return view('limites.edit', compact('limite'));
    }

    public function update(Request $request, Limites $limite)
    {
        $limite->update($request->all());

        return redirect()->route('limites.index')->with('success', 'Limite actualizado exitosamente.');
    }      

    public function destroy(Limites $limite)
    {
        try {
            // Actualiza los campos en lugar de eliminar el registro
            $limite->update([
                'codigo_mp' => '0',
                'descripcion_1' => '0',
                'mes' => '0',
                'año' => '0',
                'limite' => '0',
            ]);

            // Verifica si la actualización fue exitosa antes de eliminar
            if ($limite->wasChanged()) {
                // Elimina el material solo si la actualización fue exitosa
                $limite->delete();
                return redirect()->route('limites.index')->with('success', 'Limite actualizado y eliminado exitosamente.');
            } else {
                return redirect()->route('limites.index')->with('success', 'Limite actualizado exitosamente, pero no fue necesario eliminar.');
            }
        } catch (\Exception $e) {
            // Maneja cualquier excepción que pueda ocurrir durante la actualización o eliminación
            return redirect()->back()->with('error', 'Error al actualizar/eliminar el límite: ' . $e->getMessage());
        }
    }

    public function corteMes()
    {
        // Elimina todos los registros de la tabla 'limites'
        DB::table('limites')->delete();

        return redirect()->route('limites.index')->with('success', 'Límites eliminados exitosamente.');
    }

    public function verificarPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string',
        ]);

        // Verifica la contraseña del usuario
        if (Hash::check($request->password, Auth::user()->password)) {
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
    }

    public function uploadLimites(Request $request)
{
    $request->validate([
        'excel_file' => 'required|mimes:xlsx,xls|max:2048', // Adjust max file size if needed
    ]);

    // Update records where 'entregado' is equal to 0 and set values to 0
    Limites::where('entregado', 0)->update([
        'codigo_mp' => '0',
        'descripcion_1' => '0',
        'mes' => '0',
        'año' => '0',
        'limite' => '0',
    ]);

    // Delete records where 'entregado' is equal to 0
    Limites::where('entregado', '=', 0)->delete();

    // Update 'limite' field to 1 where 'entregado' is higher than 0
    Limites::where('entregado', '>', 0)->update(['limite' => 1]);

    // Load the uploaded Excel file
    $file = $request->file('excel_file');
    $spreadsheet = IOFactory::load($file);

    // Get the active sheet
    $sheet = $spreadsheet->getActiveSheet();

    // Get the highest row with data
    $highestRow = $sheet->getHighestRow();

    // Define the starting row
    $row = 2;

    // Loop through the Excel data and insert into the database
    for ($i = $row; $i <= $highestRow; $i++) {
        // Retrieve values from each cell
        $codigo_mp = $sheet->getCell('A' . $i)->getValue();
        $descripcion_1 = $sheet->getCell('B' . $i)->getValue();
        $mes = $sheet->getCell('C' . $i)->getValue();
        $año = $sheet->getCell('D' . $i)->getValue();
        $limite = $sheet->getCell('E' . $i)->getValue();

        // Insert into the database
        Limites::create([
            'codigo_mp' => $codigo_mp,
            'descripcion_1' => $descripcion_1,
            'mes' => $mes,
            'año' => $año,
            'limite' => $limite,
        ]);
    }
    // Delete records where 'codigo_mp' is null
    Limites::whereNull('codigo_mp')->delete();

    // Provide a response or redirect as needed
    return redirect()->back()->with('success', 'Data has been successfully imported into the database.');
}

    
}


