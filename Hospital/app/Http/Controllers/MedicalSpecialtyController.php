<?php

namespace App\Http\Controllers;

use App\Models\MedicalSpecialty;
use App\Models\Medico;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

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
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $dados = $request->validate([
            'name' => [
                'required',
                'string',
                'min:3',
                'max:255',
                'regex:/^[A-ZÁÀÂÃÉÈÊÍÌÎÓÒÔÕÚÙÛÇ][A-Za-zÀ-ÿ\s]+$/u',
                Rule::unique('medical_specialties', 'name'),
            ],
        ], [
            'name.regex' => 'A especialidade deve seguir o padrão das demais existentes (inicial maiúscula e apenas letras/espaços).',
            'name.unique' => 'Essa especialidade já existe na lista.',
        ]);

        $nome = Str::of($dados['name'])->squish()->toString();

        MedicalSpecialty::firstOrCreate(['name' => $nome]);

        return redirect()
            ->route('especialidades.index')
            ->with('success', 'Especialidade adicionada com sucesso!');
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
