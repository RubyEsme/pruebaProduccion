<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RecetasController;
use App\Http\Controllers\MaterialesController;
use App\Http\Controllers\LimitesController;
use App\Http\Controllers\OrdenesProduccionController;
use App\Http\Middleware\CheckRole;

//////////////////////////// RUTAS PARA USUARIOS GUEST (SIN ROLES) /////////////////////////////////

// Ruta para mostrar el formulario de inicio de sesión
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');

// Ruta para procesar el inicio de sesión
Route::post('/login', [LoginController::class, 'login']);

// Ruta para mostrar el formulario de inicio de sesión
Route::put('/firstLogin', [LoginController::class, 'showFirstLoginForm'])->name('firstLogin');

Route::put('/usuarios/{id}/updatePassword', [LoginController::class, 'updatePassword'])->name('usuarios.updatePassword');

Route::put('/usuarios/{id}/updatePasswordFirstLogin', [LoginController::class, 'updatePasswordFirstLogin'])->name('usuarios.updatePasswordFirstLogin');

//////////////////////////// RUTAS PARA REGISTRAR USUARIOS (SOLO ADMIN) ////////////////////////////

// Ruta de bienvenida accesible solo para usuarios con el rol 'admin'
Route::get('/', function () {
    return view('welcome');
})->name('welcome')->middleware(['auth', CheckRole::class . ':admin,planeacion']);

// Ruta de registro accesible solo para usuarios con el rol 'admin'
Route::get('/registro', function () {
    return view('registro');
})->name('registro')->middleware(['auth', CheckRole::class . ':admin']);

// Ruta para procesar el registro
Route::post('/registro', [LoginController::class, 'registro'])->name('validar-registro');

//////////////////////////// RUTAS PARA ELIMINAR USUARIOS (SOLO ADMIN) /////////////////////////////

Route::middleware(['auth', 'role:admin'])->group(function () {
    // Ruta de bienvenida
    Route::get('/usuarios', [LoginController::class, 'index'])->name('usuarios.index');

    // Ruta para editar usuarios, eliminar y editar contraseñas
    Route::delete('/usuarios/{id}', [LoginController::class, 'destroy'])->name('usuarios.destroy');

    // Ruta para editar usuario
    Route::get('/usuarios/{id}/edit', [LoginController::class, 'edit'])->name('usuarios.edit');

    Route::put('/usuarios/{id}', [LoginController::class, 'update'])->name('usuarios.update');

    // Ruta para editar usuario
    Route::get('/usuarios/{id}/editPassword', [LoginController::class, 'editPassword'])->name('usuarios.editPassword');
    
});

////////////////////////////// RUTAS DE PROCESOS LOGIN /////////////////////////////////////////////

Route::post('/validar-registro', [LoginController::class, 'registro'])->name('validar-registro');
Route::post('/inicia-sesion', [LoginController::class, 'login'])->name('inicia-sesion');

////////////////////////////// RUTAS DE PROCESOS LOGOUT ////////////////////////////////////////////

Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

/////////////////////// RUTAS PARA RECETAS (PLANEACION) ////////////////////////////////////////////

Route::middleware(['auth', 'role:admin,planeacion'])->group(function () {
    // Rutas para la gestión de recetas
    Route::post('/recetas/uploadRecetas', [RecetasController::class, 'uploadRecetas'])->name('uploadRecetas');
    Route::get('/recetas', [RecetasController::class, 'index'])->name('recetas.index');

    Route::get('/recetas/{receta}', [RecetasController::class, 'show'])->name('recetas.show');
    Route::get('/recetas/{receta}/edit', [RecetasController::class, 'edit'])->name('recetas.edit');
    Route::put('/recetas/{receta}', [RecetasController::class, 'update'])->name('recetas.update');
    Route::put('/recetas/{detalle}/{idReceta}/updateDetalle', [RecetasController::class, 'updateDetalle'])->name('recetas.updateDetalle');
    Route::delete('/recetas/{receta}', [RecetasController::class, 'destroy'])->name('recetas.destroy');

    // Rutas para la gestión de detalles de recetas
    Route::get('/recetas/{idReceta}/createDetalle/{detalles}', [RecetasController::class, 'createDetalle'])->name('recetas.createDetalle');
    Route::post('/recetas/{idReceta}/storeDetalle', [RecetasController::class, 'storeDetalle'])->name('recetas.storeDetalle');
    Route::get('/recetas/{detalle}/{idReceta}/editDetalle', [RecetasController::class, 'editDetalle'])->name('recetas.editDetalle');
    Route::delete('/recetas/{detalle}/destroyDetalle', [RecetasController::class, 'destroyDetalle'])->name('recetas.destroyDetalle');

    // Otras rutas
    Route::get('/obtener-descripciones', [RecetasController::class, 'obtenerDescripciones'])->name('recetas.obtenerDescripciones');
});


/////////////////////// RUTAS PARA MATERIALES (PLANEACION) /////////////////////////////////////////

Route::middleware(['auth', 'role:admin,planeacion'])->group(function () {
    Route::get('/materiales', [MaterialesController::class, 'index'])->name('materiales.index');
    Route::post('/materiales/uploadMateriales', [MaterialesController::class, 'uploadMateriales'])->name('uploadMateriales');
    Route::get('/materiales/create', [MaterialesController::class, 'create'])->name('materiales.create');
    Route::post('/materiales', [MaterialesController::class, 'store'])->name('materiales.store');
    Route::get('/materiales/{material}/edit', [MaterialesController::class, 'edit'])->name('materiales.edit');
    Route::put('/materiales/{material}', [MaterialesController::class, 'update'])->name('materiales.update');
    Route::delete('/materiales/{material}', [MaterialesController::class, 'destroy'])->name('materiales.destroy');
    Route::get('/materiales/exportar', [MaterialesController::class, 'exportar'])->name('materiales.exportar');
    
});

/////////////////////// RUTAS PARA LIMITES (PLANEACION) ////////////////////////////////////////////

Route::middleware(['auth', 'role:admin,planeacion'])->group(function () {
    // Rutas para CRUD de limites
    Route::get('/limites', [LimitesController::class, 'index'])->name('limites.index');
    Route::get('/limitesCotejo', [LimitesController::class, 'indexCotejo'])->name('limites.indexCotejo');
    Route::get('/limites/create', [LimitesController::class, 'create'])->name('limites.create');
    Route::post('/limites', [LimitesController::class, 'store'])->name('limites.store');
    Route::post('/limites/uploadLimites', [LimitesController::class, 'uploadLimites'])->name('uploadLimites');
    Route::get('/limites/{limite}/edit', [LimitesController::class, 'edit'])->name('limites.edit');
    Route::put('/limites/{limite}', [LimitesController::class, 'update'])->name('limites.update');
    Route::delete('/limites/{limite}', [LimitesController::class, 'destroy'])->name('limites.destroy');

    // Rutas adicionales
    Route::get('/limites-historico', [LimitesController::class, 'historico'])->name('limites.historico');
    Route::get('/limites-historico-cotejo', [LimitesController::class, 'historicoCotejo'])->name('limites.historicoCotejo');

    // Ruta para corte de mes
    Route::get('/limites/corteMes', [LimitesController::class, 'corteMes'])->name('limites.corteMes');
    Route::post('/limites/verificar-password', [LimitesController::class, 'verificarPassword'])->name('limites.verificarPassword');

});


//////////////////// RUTAS PARA ORDENES DE PRODUCCION (SOLO LINEA Y ESMALTE) /////////////////////

Route::middleware(['auth', 'role:linea,esmalte,admin'])->group(function () {
    Route::get('/ordenes-produccion/create', [OrdenesProduccionController::class, 'create'])->name('ordenesProduccion.create');
    Route::post('/guardar-ordenes', [OrdenesProduccionController::class, 'store'])->name('ordenesProduccion.store');
    Route::get('/obtener-formatos/{modelo}', [OrdenesProduccionController::class, 'obtenerFormatos'])->name('ordenesProduccion.obtenerFormatos');
    Route::get('/obtener-plantas/{modelo}/{formato}', [OrdenesProduccionController::class, 'obtenerPlantas'])->name('ordenesProduccion.obtenerPlantas');
    Route::get('/obtener-recetas/{modelo}/{formato}/{planta}', [OrdenesProduccionController::class, 'obtenerRecetas'])->name('ordenesProduccion.obtenerRecetas');
    Route::get('/obtener-recetas-por-id/{modelo}/{formato}/{planta}/{idReceta}', [OrdenesProduccionController::class, 'obtenerRecetasPorId'])->name('ordenesProduccion.obtenerRecetasPorId');
    Route::get('/obtener-codigo-mp/{descripcion_1}', [OrdenesProduccionController::class, 'obtenerCodigoMP'])
        ->where('descripcion_1', '.*')
        ->name('ordenesProduccion.obtenerCodigoMP');
    Route::get('/obtener-descripciones', [OrdenesProduccionController::class, 'obtenerDescripciones'])->name('ordenesProduccion.obtenerDescripciones');
});

//////////////////////// RUTAS PARA ORDENES DE PRODUCCION (SOLO ALMACEN) ///////////////////////////

Route::middleware(['auth', 'role:almacen,admin'])->group(function () {
    Route::get('/ordenes-produccion', [OrdenesProduccionController::class, 'index'])->name('ordenesProduccion.index');
    Route::get('/ordenes-produccion2', [OrdenesProduccionController::class, 'index2'])->name('ordenesProduccion.index2');
    Route::get('/ordenes-produccion/cerrar-orden/{orden}', [OrdenesProduccionController::class, 'cerrarOrden'])->name('ordenesProduccion.cerrarOrden');

    Route::get('/ordenes-produccion/{detalle}/{orden}/editDevolver', [OrdenesProduccionController::class, 'editDevolver'])->name('ordenesProduccion.editDevolver');
    Route::put('/ordenes-produccion/{detalle}/{orden}/updateDevolver', [OrdenesProduccionController::class, 'updateDevolver'])->name('ordenesProduccion.updateDevolver');

    Route::get('/ordenes-produccion/{orden}', [OrdenesProduccionController::class, 'show'])->name('ordenesProduccion.show');
    Route::get('/ordenes-produccion2/{orden}', [OrdenesProduccionController::class, 'show2'])->name('ordenesProduccion.show2');

    Route::post('/ordenes-produccion/guardar-surtido', [OrdenesProduccionController::class, 'guardarSurtido'])->name('ordenesProduccion.guardarSurtido');
});