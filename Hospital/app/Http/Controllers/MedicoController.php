<?php

namespace App\Http\Controllers;

 use App\Models\MedicalSpecialty;
use App\Models\Medico;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class MedicoController extends Controller
{
    /**
     * Display a listing of the resource(medical).
     * Exibe uma lista de médicos cadastrados.
     */
    public function index()
    {
        $medicos = Medico::with('user')->paginate(10);
        return view('medicos.index', compact('medicos'));
    }

    /**
     * Show the form for creating a new resource(medical).
     *  Exibe o formulário para cadastrar um novo médico.
     */
    public function create()
    {
        $especialidades = $this->getEspecialidades();
        $especialidadePadrao = $this->getEspecialidadePadrao();

        return view('medicos.create', compact('especialidades', 'especialidadePadrao'));
    }

    /**
     * Store a newly created resource in storage(medical).
     * Processa o formulário de criação de médico e salva os dados no banco.
     */
    public function store(Request $request)
    {
        $especialidades = $this->getEspecialidades();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'crm' => 'required|string|max:20|unique:medicos,crm',
            'especialidade' => ['required', 'string', Rule::in($especialidades)],
            'telefone' => 'nullable|string|max:20',
            'endereco' => 'nullable|string',
        ]);

        // Criar usuário
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'medico',
        ]);

        // Criar médico
        Medico::create([
            'user_id' => $user->id,
            'crm' => $request->crm,
            'especialidade' => $request->especialidade,
            'telefone' => $request->telefone,
            'endereco' => $request->endereco,
        ]);

        return redirect()->route('medicos.index')
            ->with('success', 'Médico cadastrado com sucesso!');
    }

    /**
     * Display the specified resource.
     * Exibe os detalhes de um médico específico.
     */
    public function show(string $id)
    {
        $medico = Medico::with('user')->findOrFail($id);
        return view('medicos.show', compact('medico'));
    }

    /**
     * Show the form for editing the specified resource.
     * Exibe o formulário para editar os dados de um médico existente.
     */
    public function edit(string $id)
    {
        $medico = Medico::with('user')->findOrFail($id);
        $especialidades = $this->getEspecialidades();
        $especialidadePadrao = $this->getEspecialidadePadrao();

        return view('medicos.edit', compact('medico', 'especialidades', 'especialidadePadrao'));
    }

    /**
     * Update the specified resource in storage.
     * Processa o formulário de edição de médico e atualiza os dados no banco.
     */
    public function update(Request $request, string $id)
    {
        $medico = Medico::findOrFail($id);
        $especialidades = $this->getEspecialidades();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $medico->user_id,
            'crm' => 'required|string|max:20|unique:medicos,crm,' . $id,
            'especialidade' => ['required', 'string', Rule::in($especialidades)],
            'telefone' => 'nullable|string|max:20',
            'endereco' => 'nullable|string',
        ]);

        // Atualizar usuário
        $medico->user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        // Atualizar senha se fornecida
        if ($request->filled('password')) {
            $request->validate(['password' => 'string|min:8|confirmed']);
            $medico->user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        // Atualizar médico
        $medico->update([
            'crm' => $request->crm,
            'especialidade' => $request->especialidade,
            'telefone' => $request->telefone,
            'endereco' => $request->endereco,
        ]);

        return redirect()->route('medicos.index')
            ->with('success', 'Médico atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     * Exclui um médico do banco de dados, removendo também o usuário associado.
     */
    public function destroy(string $id)
    {
        $medico = Medico::findOrFail($id);
        $user = $medico->user;
        
        $medico->delete();
        $user->delete();

        return redirect()->route('medicos.index')
            ->with('success', 'Médico excluído com sucesso!');
    }

    private function getEspecialidades(): array
    {
        $this->sincronizarEspecialidadesIniciais();

        return MedicalSpecialty::query()
            ->orderBy('name')
            ->pluck('name')
            ->all();
    }

    private function getEspecialidadePadrao(): string
    {
        $especialidades = $this->getEspecialidades();
        $padrao = config('medical.default_specialty');

        if ($padrao && in_array($padrao, $especialidades, true)) {
            return $padrao;
        }

        return $especialidades[0] ?? '';
    }

    private function sincronizarEspecialidadesIniciais(): void
    {
        if (MedicalSpecialty::query()->exists()) {
            return;
        }

        foreach (config('medical.specialties', []) as $especialidade) {
            $especialidade = trim((string) $especialidade);

            if ($especialidade !== '') {
                MedicalSpecialty::firstOrCreate(['name' => $especialidade]);
            }
        }
    }

}
