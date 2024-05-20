<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\SearchHistory;
use Exception;
use Illuminate\Support\Facades\Log;

class PokemonController extends Controller
{
    public function mostrarFormulario()
    {
        return view('buscar');
    }

    //metodo de busqueda
    public function procesarBusqueda(Request $request)
    {
        try {
            // verif termino de busqeuda
            $request->validate([
                'term' => 'required|string|max:255',
            ]);

            // obtenemos el term=temrino de busqueda y la sessionID
            $term = $request->input('term');
            $sessionId = $request->session()->getId();

            // verificamos si ese pokemon ya fue buscado anteriormente
            $existingSearch = SearchHistory::where('term', $term)
                ->where('session_id', $sessionId)
                ->first();

            if ($existingSearch) {
                return response()->json(['message' => 'Esta búsqueda ya ha sido realizada.'], 200);
            }

            // llamamos a la API
            $response = Http::get("https://pokeapi.co/api/v2/pokemon/{$term}");

            if ($response->successful()) {
                // obtenemos los datos y las habilidades del pokemon en español
                $data = $response->json();
                $abilities = array_map(function ($ability) {
                    $abilityDetails = Http::get($ability['ability']['url'])->json();
                    $spanishName = collect($abilityDetails['names'])->firstWhere('language.name', 'es')['name'] ?? 'Unknown';
                    return $spanishName;
                }, $data['abilities']);

                // guardamos la busqueda en la DDBB
                SearchHistory::create([
                    'term' => $term,
                    'results' => json_encode($abilities),
                    'session_id' => $sessionId,
                ]);

                // ultimas 10 busquedas de la sesion
                $historial = SearchHistory::where('session_id', $sessionId)
                    ->orderBy('created_at', 'desc')
                    ->limit(10)
                    ->get();

                return response()->json([
                    'abilities' => $abilities,
                    'historial' => $historial
                ]);
            } else {
                // en caso de no encontrarse el pokemon
                Log::error("Error fetching Pokemon data: " . $response->body());
                return response()->json(['error' => 'No se encontró el Pokémon.'], 404);
            }
        } catch (Exception $e) {
            Log::error("Exception in procesarBusqueda: " . $e->getMessage());
            return response()->json(['error' => 'Ocurrió un error al procesar la búsqueda.'], 500);
        }
    }


    //metodo de mostrar el historial de busqueda
    public function mostrarHistorial(Request $request)
    {
        try {

            //obtenemos la sesion y el historial de esa sesion:
            $sessionId = $request->session()->getId();
            $historial = SearchHistory::where('session_id', $sessionId)
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();

            return response()->json(['historial' => $historial]);
        } catch (Exception $e) {
            Log::error("Exception in mostrarHistorial: " . $e->getMessage());
            return response()->json(['error' => 'Ocurrió un error al recuperar el historial.'], 500);
        }
    }
}
