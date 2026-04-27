<?php

namespace App\Http\Controllers;

use App\Models\Producto;          // ➊ Importa tu modelo de productos
use Illuminate\Http\Request;

class CuentaController extends Controller
{
    /**
     * Muestra la página “Mi cuenta”.
     */
    public function index()
    {
        // ➋ Obtén la lista de productos que quieras mostrar
        //    (cámbialo por la consulta que necesites)
        $productos = Producto::where('activo', true)
                             ->orderBy('nombre')
                             ->paginate(9);   // paginación opcional

        // ➌ Pasa la variable a la vista
        return view('cuenta.index', compact('productos'));
    }
}
