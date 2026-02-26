<?php

namespace App\Http\Controllers;

use App\Models\MedicalSpecialty;
use App\Models\Medico;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class MedicalSpecialtyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $especialidades = MedicalSpecialty::query()
            ->orderBy('name')
            ->get();

        $defaultSpecialty = (string) config('medical.default_specialty', 'Clinico Geral');

        return view('admins.specialties.index', compact('especialidades', 'defaultSpecialty'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, MedicalSpecialty $medicalSpecialty): RedirectResponse
    {
        $defaultSpecialty = (string) config('medical.default_specialty', 'Clinico Geral');

        if ($medicalSpecialty->name === $defaultSpecialty) {
            return redirect()
                ->route('especialidades.index')
                ->with('error', 'Nao e possivel excluir a especialidade padrao.');
        }

        MedicalSpecialty::firstOrCreate(['name' => $defaultSpecialty]);

        Medico::query()
            ->where('especialidade', $medicalSpecialty->name)
            ->update(['especialidade' => $defaultSpecialty]);

        $medicalSpecialty->delete();

        return redirect()
            ->route('especialidades.index')
            ->with('success', 'Especialidade removida e medicos atualizados para Clinico Geral(Padrao).');
    }
}
