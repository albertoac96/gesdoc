<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Auth::routes();
Route::get('/', function () {
    $idUser = Auth::id();
    if($idUser == ""){
        return view('auth.login');
    }
    return view('welcome');
    
});

Route::get('/register', function () {
    $idUser = Auth::id();
   
    return view('auth.login');
    
});

Route::get('/passreset', function () {
     
    return view('auth.passwords.email');
    
});



//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home'); 


Route::middleware(['auth'])->group(function () {
    Route::get('/traecarpetas', [App\Http\Controllers\CarpetaController::class, 'TraeCarpetas'])->name('TraeCarpetas');

    Route::post('/iu', [App\Http\Controllers\HomeController::class, 'InfoUser'])->name('InfoUser');
    Route::get('/authinfo', [App\Http\Controllers\HomeController::class, 'InfoUserPerfil'])->name('InfoUserPerfil');

    Route::get('/carpetaspadre/{id}', [App\Http\Controllers\CarpetaController::class, 'CarpetasPadreDe'])->name('CarpetasPadreDe');

    //Route::get('/sacaimg', [App\Http\Controllers\ArchivosController::class, 'sacaImagenes'])->name('sacaImagenes');

    Route::post('/deleterel', [App\Http\Controllers\GlobalController::class, 'DeleteRel'])->name('DeleteRel');
    Route::post('/addrel', [App\Http\Controllers\GlobalController::class, 'AddRel'])->name('AddRel');
    Route::post('/edit', [App\Http\Controllers\GlobalController::class, 'EditItem'])->name('EditItem');


    Route::get('/nombrede/{id}', [App\Http\Controllers\CarpetaController::class, 'NombreDe'])->name('NombreDe');
    Route::post('/regcarpeta', [App\Http\Controllers\CarpetaController::class, 'registraCarpeta'])->name('registraCarpeta');

    Route::get('/listadocs/{id}', [App\Http\Controllers\DocumentosController::class, 'ListaDocs'])->name('ListaDocs');

    Route::get('/traepermisos', [App\Http\Controllers\PermisosController::class, 'index'])->name('indexPermisos');
    Route::get('/permisosperfil/{id}', [App\Http\Controllers\PermisosController::class, 'permisosPerfil'])->name('indexPermisos');

    Route::get('/traemenu', [App\Http\Controllers\CarpetaController::class, 'TraeMenu'])->name('TraeMenu');



    Route::get('/autoresdeldoc/{id}', [App\Http\Controllers\DocumentosController::class, 'AutoresDelDoc'])->name('AutoresDelDoc');
    Route::get('/infodoc/{id}', [App\Http\Controllers\DocumentosController::class, 'InfoDoc'])->name('InfoDoc');
    Route::get('/carpeta/{id}', [App\Http\Controllers\DocumentosController::class, 'CarpetaDoc'])->name('TraeMenu');
    Route::post('/upload', [App\Http\Controllers\DocumentosController::class, 'UploadArchivo'])->name('UploadArchivo');
    Route::post('/uploaddoc', [App\Http\Controllers\DocumentosController::class, 'uploadDocumento'])->name('uploadDocumento');
    Route::post('/regenlace', [App\Http\Controllers\DocumentosController::class, 'regEnlace'])->name('regEnlace');
    Route::post('/traetemasasocia', [App\Http\Controllers\DocumentosController::class, 'temasNoAsociados'])->name('temasNoAsociados');
    Route::post('/asociatemas', [App\Http\Controllers\DocumentosController::class, 'asociaTemas'])->name('asociaTemas');
    Route::get('/traedocsarel/{id}', [App\Http\Controllers\DocumentosController::class, 'DocsARelacionar'])->name('DocsARelacionar');
    Route::get('/docsrecientes', [App\Http\Controllers\DocumentosController::class, 'DocsRecientes'])->name('DocsRecientes');

    Route::post('/traetagsnoasocia', [App\Http\Controllers\DocumentosController::class, 'tagsNoAsociados'])->name('tagsNoAsociados');
    Route::post('/tags', [App\Http\Controllers\DocumentosController::class, 'traeTags'])->name('traeTags');
    Route::post('/regtag', [App\Http\Controllers\Catalogos\etiquetasController::class, 'RegTag'])->name('RegTag');

    Route::get('/cbotipodoc', [App\Http\Controllers\Catalogos\TipoDocController::class, 'cboTipoDoc'])->name('cboTipoDoc');
    Route::get('/tdindex', [App\Http\Controllers\Catalogos\TipoDocController::class, 'TipoDocIndex'])->name('cboTipoDoc');

    Route::get('/cbotemas', [App\Http\Controllers\Catalogos\temasController::class, 'cboTemas'])->name('cboTemas');
    Route::get('/traetemas', [App\Http\Controllers\Catalogos\temasController::class, 'showTemas'])->name('TraeTemas');

    Route::get('/perindex/{id}', [App\Http\Controllers\Catalogos\publicacionesController::class, 'PeriodicasIndex'])->name('PeriodicasIndex');
    Route::get('/pubsshow', [App\Http\Controllers\Catalogos\publicacionesController::class, 'traerPubs'])->name('traerPubs');


    Route::get('/autorindex', [App\Http\Controllers\Catalogos\autoresController::class, 'AutoresIndex'])->name('AutoresIndex');
    Route::get('/tipoautor', [App\Http\Controllers\Catalogos\autoresController::class, 'TipoAutorList'])->name('TipoAutorList');
    Route::get('/tipoautorshow', [App\Http\Controllers\Catalogos\autoresController::class, 'tipoautorshow'])->name('tipoautorshow');
    Route::post('/regautor', [App\Http\Controllers\Catalogos\autoresController::class, 'RegAutor'])->name('RegAutor');



    Route::get('/atrdoc/{id}', [App\Http\Controllers\Catalogos\atributosController::class, 'AtrDelDoc'])->name('AtrDelDoc');
    Route::get('/atrshow', [App\Http\Controllers\Catalogos\atributosController::class, 'MostrarAtr'])->name('MostrarAtr');
    Route::get('/atrshowaso', [App\Http\Controllers\Catalogos\atributosController::class, 'MostrarAtrAso'])->name('MostrarAtr');
    Route::get('/atrtipo/{id}', [App\Http\Controllers\Catalogos\atributosController::class, 'AtrDelTipo'])->name('MostrarAtr');

    Route::post('/resultados', [App\Http\Controllers\BuscadorController::class, 'ResultadosBuscador'])->name('Buscador');

    Route::get('/notasarchivo/{id}', [App\Http\Controllers\NotasController::class, 'TraeNotasDelArchivo'])->name('NotasDelArchivo');
    Route::post('/rnota', [App\Http\Controllers\NotasController::class, 'RegistraNota'])->name('RegistraNota');
    Route::post('/enota', [App\Http\Controllers\NotasController::class, 'EditaNota'])->name('EditaNota');
    Route::get('/dnota', [App\Http\Controllers\NotasController::class, 'DeleteNota'])->name('DeleteNota');

    Route::get('/sacarext', [App\Http\Controllers\DocumentosController::class, 'sacarExt'])->name('sacarExt');
    Route::get('/sacarautores', [App\Http\Controllers\DocumentosController::class, 'sacarAutores'])->name('sacarAutores');
    Route::post('/deletItem', [App\Http\Controllers\DocumentosController::class, 'deleteDocumento'])->name('deleteDocumento');
    Route::post('/editTitulo', [App\Http\Controllers\DocumentosController::class, 'editTituloDoc'])->name('deleteDocumento');
    Route::post('/movedoc', [App\Http\Controllers\DocumentosController::class, 'moveDoc'])->name('moveDoc');

    Route::get('/showusers', [App\Http\Controllers\SeguridadController::class, 'verUsuarios'])->name('verUsuarios');
    Route::post('/edituser', [App\Http\Controllers\SeguridadController::class, 'editUsuario'])->name('editUsuario');
    Route::get('/deleteuser/{id}', [App\Http\Controllers\SeguridadController::class, 'deleteUsuario'])->name('deleteUsuario');
    Route::get('/showroles', [App\Http\Controllers\SeguridadController::class, 'verRoles'])->name('verRoles');
    Route::get('/showpermisos', [App\Http\Controllers\SeguridadController::class, 'traerPermisos'])->name('traerPermisos');
    Route::get('/pmenu/{id}', [App\Http\Controllers\SeguridadController::class, 'permRol'])->name('permRol');
    Route::get('/paccions/{id}', [App\Http\Controllers\SeguridadController::class, 'permAccions'])->name('permAccions');
    Route::post('/editpermisos', [App\Http\Controllers\SeguridadController::class, 'editPermisos'])->name('editPermisos');
    Route::post('/tp', [App\Http\Controllers\SeguridadController::class, 'tienePermiso'])->name('tienePermiso');
    Route::get('/listsessions', [App\Http\Controllers\SeguridadController::class, 'listaSesiones'])->name('listaSesiones');
    Route::post('/delsesion', [App\Http\Controllers\SeguridadController::class, 'deleteSesion'])->name('deleteSesion');

    
    
    Route::group(['prefix' => 'user'], function(){
        Route::post('/nuevo', [App\Http\Controllers\HomeController::class, 'newUser']);
        Route::post('/editar', [App\Http\Controllers\HomeController::class, 'editUser']);
        Route::post('/baja', [App\Http\Controllers\HomeController::class, 'bajaUser']);
    });

    Route::group(['prefix' => 'roles'], function(){
        Route::post('/nuevo', [App\Http\Controllers\contacto\solicitudesController::class, 'newRol']);
        Route::post('/editar', [App\Http\Controllers\contacto\solicitudesController::class, 'editRol']);
    });

    Route::group(['prefix' => 'pubs'], function(){
        Route::post('/nuevo', [App\Http\Controllers\contacto\solicitudesController::class, 'newPub']);
        Route::post('/editar', [App\Http\Controllers\contacto\solicitudesController::class, 'editPub']);
    });

    Route::group(['prefix' => 'tipodoc'], function(){
        Route::post('/nuevo', [App\Http\Controllers\contacto\solicitudesController::class, 'newTipoDoc']);
        Route::post('/editar', [App\Http\Controllers\contacto\solicitudesController::class, 'editTipoDoc']);
        Route::post('/baja', [App\Http\Controllers\contacto\solicitudesController::class, 'bajaTipoDoc']);
        Route::post('/asociaratrs', [App\Http\Controllers\contacto\solicitudesController::class, 'atrToTipoDoc']);
    });

    Route::group(['prefix' => 'atributos'], function(){
        Route::post('/nuevo', [App\Http\Controllers\contacto\solicitudesController::class, 'newAtr']);
        Route::post('/editar', [App\Http\Controllers\contacto\solicitudesController::class, 'editAtr']);
        Route::post('/baja', [App\Http\Controllers\contacto\solicitudesController::class, 'bajaAtr']);
    });

    Route::group(['prefix' => 'autores'], function(){
        Route::get('/show', [App\Http\Controllers\Catalogos\autoresController::class, 'showAutores']);
        Route::post('/edit', [App\Http\Controllers\Catalogos\autoresController::class, 'showAutores']);
        Route::post('/baja', [App\Http\Controllers\Catalogos\autoresController::class, 'showAutores']);
    });

    Route::group(['prefix' => 'eventos'], function(){
        Route::get('/autor/{id}', [App\Http\Controllers\eventosController::class, 'verEventosDeAutor']);
        Route::get('/autorunico/{id}', [App\Http\Controllers\eventosController::class, 'eventosDeAutorShow']);
        Route::get('/tipos', [App\Http\Controllers\eventosController::class, 'tiposEventos']);
        Route::get('/sugerencias', [App\Http\Controllers\eventosController::class, 'verSugerencias']);
        Route::post('/new', [App\Http\Controllers\eventosController::class, 'guardarEvento']);
        Route::get('/delevento/{id}', [App\Http\Controllers\eventosController::class, 'verEventosDe']);
        Route::get('/info/{id}', [App\Http\Controllers\eventosController::class, 'infoDelEvento']);
        Route::post('/savered', [App\Http\Controllers\eventosController::class, 'guardarRed']);
        Route::get('/actores/{id}', [App\Http\Controllers\eventosController::class, 'traeActoresDe']);
        Route::get('/nodo/{id}', [App\Http\Controllers\eventosController::class, 'traeNodo']);
    });


    Route::group(['prefix' => 'cidoc'], function(){
        Route::group(['prefix' => 'entidad'], function(){
            Route::get('/show', [App\Http\Controllers\Catalogos\Cidoc\entidadController::class, 'showEntidad']);
            Route::post('/edit', [App\Http\Controllers\Catalogos\Cidoc\entidadController::class, 'editEntidad']);
            Route::post('/baja', [App\Http\Controllers\Catalogos\Cidoc\entidadController::class, 'bajaEntidad']);
        });
        Route::group(['prefix' => 'propiedad'], function(){
            Route::get('/show', [App\Http\Controllers\Catalogos\Cidoc\propiedadController::class, 'showPropiedad']);
            Route::post('/edit', [App\Http\Controllers\Catalogos\Cidoc\propiedadController::class, 'editPropiedad']);
            Route::post('/baja', [App\Http\Controllers\Catalogos\Cidoc\propiedadController::class, 'bajaPropiedad']);
        });
        Route::group(['prefix' => 'relacion'], function(){
            Route::get('/show', [App\Http\Controllers\Catalogos\Cidoc\relacionController::class, 'showRel']);
            Route::post('/edit', [App\Http\Controllers\Catalogos\Cidoc\relacionController::class, 'editRel']);
            Route::post('/baja', [App\Http\Controllers\Catalogos\Cidoc\relacionController::class, 'bajaRel']);
        });
    });

    



});





Route::get('{any}', [App\Http\Controllers\HomeController::class, 'index'])->where('any', '.*');

