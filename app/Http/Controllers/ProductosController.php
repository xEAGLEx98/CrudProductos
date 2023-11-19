<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class ProductosController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function __construct(){
        $this->middleware('auth');
    }
    public function index()
    {
        $productos = Producto::paginate(5);
        return view('productos.index',['productos'=> $productos]);
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('productos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|min:5|max:30',
            'descripcion' => 'required|min:5|max:100',
            'precio' => ['required','numeric','regex:/^\d+(\.\d{1,2})?$/']
        ]);

        $producto = Producto::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'precio' => $request->precio
        ]);

        session()->flash('status', 'Se guardo el producto '.$request->nombre);
        return to_route('ProductosIndex');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $producto = Producto::find($id);
        return view('productos.edit',['producto'=>$producto]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nombre' => 'required|min:5|max:30',
            'descripcion' => 'required|min:5|max:100',
            'precio' => ['required','numeric','regex:/^\d+(\.\d{1,2})?$/']
        ]);

        $producto = Producto::find($id);
        if($producto){
            $producto->nombre = $request->input('nombre');
            $producto->descripcion = $request->input('descripcion');
            $producto->precio = $request->input('precio');

            $producto->save();
        }

        session()->flash('status', 'Se actualizo el producto '.$request->nombre);

        return to_route('ProductosIndex');
       

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $producto = Producto::find($id);
        if($producto){
            $producto->delete();
            session()->flash('status', 'Producto eliminado');
        }
        
        return to_route('ProductosIndex');
    }
}
