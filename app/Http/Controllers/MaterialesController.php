<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use League\Csv\Writer;

use PhpOffice\PhpSpreadsheet\IOFactory;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use App\Models\Materiales;
use App\Models\LimitesHistorico;

class MaterialesController extends Controller
{
    public function index()
    {
        // Obtener todas los materiales de la base de datos
        $materiales = Materiales::all();

        // Pasar los materiales a la vista a la vista
        return view('materiales.index', compact('materiales'));
    }

    public function historico()
    {
        // Obtener todas los materiales de la base de datos
        $limites = LimitesHistorico::all();

        // Pasar los materiales a la vista a la vista
        return view('limites.historico', compact('limites'));
    }
    
    public function create()
    {
        return view('materiales.create');
    }
    
    public function store(Request $request)
	{
	    $request->validate([
	        'codigo_mp' => [
	            'required',
	            'unique:materiales,codigo_mp', // La regla unique se utiliza para verificar la unicidad en la tabla 'materiales'
	        ],
	    ]);

	    Materiales::create($request->all());

	    return redirect()->route('materiales.index')->with('success', 'Material creado exitosamente.');
	}

    public function edit(Materiales $material)
    {
        return view('materiales.edit', compact('material'));
    }

    public function update(Request $request, Materiales $material)
	{
	    $request->validate([
	        'codigo_mp' => [
	            'required',
	            Rule::unique('materiales')->ignore($material->id), // Ignora el código actual del material durante la actualización
	        ],
	        'descripcion_1' => 'required',
	    ]);

	    $material->update($request->all());

	    return redirect()->route('materiales.index')->with('success', 'Material actualizado exitosamente.');
	}      

    public function destroy(Materiales $material)
	{
	    try {
	        // Elimina el material
	        $material->delete();

	        return redirect()->route('materiales.index')->with('success', 'Material eliminado exitosamente.');
	    } catch (\Exception $e) {
	        // Maneja cualquier excepción que pueda ocurrir durante la eliminación
	        return redirect()->back()->with('error', 'Error al eliminar el material: ' . $e->getMessage());
	    }
	}

	
public function uploadMateriales(Request $request)
{
    $request->validate([
        'excel_file' => 'required|mimes:xlsx,xls|max:2048', // Adjust max file size if needed
    ]);

    // Load the uploaded Excel file
    $file = $request->file('excel_file');
    $spreadsheet = IOFactory::load($file);

    // Get the active sheet
    $sheet = $spreadsheet->getActiveSheet();

    // Delete all existing records in the Materiales table
    Materiales::truncate();
    
    // Get the highest row with data
    $highestRow = $sheet->getHighestRow();

    // Define the starting row
    $row = 2;

    // Loop through the Excel data and insert into the database
    for ($i = $row; $i <= $highestRow; $i++) {
        // Retrieve values from each cell
        $codigo_mp = $sheet->getCell('A' . $i)->getValue();
        $descripcion_1 = $sheet->getCell('B' . $i)->getValue();
  

        // Insert into the database
        Materiales::create([
            'codigo_mp' => $codigo_mp,
            'descripcion_1' => $descripcion_1
        ]);
    }
    // Delete records where 'codigo_mp' is null
    Materiales::whereNull('codigo_mp')->delete();

    // Provide a response or redirect as needed
    return redirect()->back()->with('success', 'Data has been successfully imported into the database.');
}
}