<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\comida;
use App\Models\pedido;

class RestauranteController extends Controller
{
    // Mostrar la lista de comidas (el menú)
    public function index() {
        $comidas = comida::all();
        return view('restaurante.index', compact('comidas'));
    }

    // Guardar un nuevo pedido con el usuario autenticado actual
    public function storePedido(Request $request) {
        pedido::create([
            'user_id' => auth()->id(), // Dinámico según el usuario logueado
            'nombre_plato' => $request->nombre_plato, // Coincide con la base de datos
            'observaciones' => $request->observaciones, // Ej: "Sin cebolla, extra queso"
            'total' => $request->total,
            'estado' => 'recibido' // Estado inicial requerido
        ]);

        return redirect()->back()->with('success', '¡Pedido registrado con éxito!');
    }

    // Mostrar los pedidos que están en la cocina (estado 'recibido')
    public function cocina() {
        $pedidos = \App\Models\Pedido::where('estado', 'recibido')->get();
        return view('restaurante.cocina', compact('pedidos'));
    }

    // Cambiar el estado del pedido de 'recibido' a 'preparado'
    public function marcarPreparado($id) {
        $pedido = \App\Models\Pedido::find($id);
        if ($pedido) {
            $pedido->estado = 'preparado';
            $pedido->save();
        }
        return redirect()->route('cocina.index')->with('success', '¡Pedido marcado como preparado!');
    }

    // Mostrar los pedidos listos para entregar (estado 'preparado')
    public function entrega() {
        $pedidos = \App\Models\Pedido::where('estado', 'preparado')->get();
        return view('restaurante.entrega', compact('pedidos'));
    }

    // Cambiar el estado del pedido de 'preparado' a 'entregado'
    public function marcarEntregado($id) {
        $pedido = \App\Models\Pedido::find($id);
        if ($pedido) {
            $pedido->estado = 'entregado';
            $pedido->save();
        }
        return redirect()->route('entrega.index')->with('success', '¡Pedido entregado con éxito al cliente!');
    }

    // Mostrar el historial de consumo únicamente del cliente autenticado
    public function historial() {
        $pedidos = \App\Models\Pedido::where('user_id', auth()->id())->get(); // Solo sus pedidos
        $totalGastado = $pedidos->sum('total');
        return view('restaurante.historial', compact('pedidos', 'totalGastado'));
    }

    // Modificar únicamente las observaciones de un pedido propio mientras esté 'recibido'
    public function updatePedido(Request $request, $id) {
        $pedido = \App\Models\Pedido::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        if (strtolower($pedido->estado) !== 'recibido') {
            return redirect()->back()->withErrors('Solo puedes modificar las observaciones mientras el pedido esté en estado recibido.');
        }

        $request->validate([
            'observaciones' => ['nullable', 'string', 'max:255'],
        ]);

        $pedido->observaciones = $request->observaciones;
        $pedido->save();

        return redirect()->back()->with('success', '¡Modificaciones del pedido #' . $pedido->id . ' actualizadas!');
    }

    // Mostrar formulario para crear un nuevo plato
    public function createComida() {
        return view('restaurante.create');
    }

    // Guardar el nuevo plato en la base de datos
    public function storeComida(Request $request) {
        $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'precio' => ['required', 'numeric', 'min:0'],
            'categoria' => ['required', 'string', 'max:255'],
        ]);

        comida::create([
            'nombre' => $request->nombre,
            'precio' => $request->precio,
            'categoria' => $request->categoria,
        ]);

        return redirect()->route('menu')->with('success', '¡Plato creado con éxito!');
    }

    // Eliminar un plato del menú
    public function destroyComida($id) {
        $comida = comida::find($id);
        if ($comida) {
            $comida->delete();
        }
        return redirect()->route('menu')->with('success', '¡Plato eliminado con éxito!');
    }
}