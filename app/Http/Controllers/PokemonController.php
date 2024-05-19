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

    public function procesarBusqueda(Request $request)
    {
        try {
            $request->validate([
                'term' => 'required|string|max:255',
            ]);

            $term = $request->input('term');
            $sessionId = $request->session()->getId();

            $response = Http::get("https://pokeapi.co/api/v2/pokemon/{$term}");

            if ($response->successful()) {
                $data = $response->json();
                $abilities = array_map(function ($ability) {
                    $abilityDetails = Http::get($ability['ability']['url'])->json();
                    $spanishName = collect($abilityDetails['names'])->firstWhere('language.name', 'es')['name'] ?? 'Unknown';
                    return $spanishName;
                }, $data['abilities']);

                SearchHistory::create([
                    'term' => $term,
                    'results' => json_encode($abilities),
                    'session_id' => $sessionId,
                ]);

                $historial = SearchHistory::where('session_id', $sessionId)
                    ->orderBy('created_at', 'desc')
                    ->limit(10)
                    ->get();

                return response()->json([
                    'abilities' => $abilities,
                    'historial' => $historial
                ]);
            } else {
                Log::error("Error fetching Pokemon data: " . $response->body());
                return response()->json(['error' => 'No se encontró el Pokémon.'], 404);
            }
        } catch (Exception $e) {
            Log::error("Exception in procesarBusqueda: " . $e->getMessage());
            return response()->json(['error' => 'Ocurrió un error al procesar la búsqueda.'], 500);
        }
    }

    public function mostrarHistorial(Request $request)
    {
        try {
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
