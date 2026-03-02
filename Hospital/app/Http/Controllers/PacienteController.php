<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PacienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pacientes = Paciente::with('user')->paginate(10);
        return view('pacientes.index', compact('pacientes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pacientes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'cpf' => 'required|string|max:14|unique:pacientes,cpf',
            'data_nascimento' => 'required|date',
            'telefone' => 'nullable|string|max:20',
            'endereco' => 'nullable|string',
            'sexo' => 'nullable|in:M,F,Outro',
            'tipo_sanguineo' => 'nullable|string|max:5',
            'alergias' => 'nullable|string',
        ]);

        // Criar usuário
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'paciente',
        ]);

        // Criar paciente
        Paciente::create([
            'user_id' => $user->id,
            'cpf' => $request->cpf,
            'data_nascimento' => $request->data_nascimento,
            'telefone' => $request->telefone,
            'endereco' => $request->endereco,
            'sexo' => $request->sexo,
            'tipo_sanguineo' => $request->tipo_sanguineo,
            'alergias' => $request->alergias,
        ]);

        return redirect()->route('pacientes.index')
            ->with('success', 'Paciente cadastrado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $paciente = Paciente::with('user')->findOrFail($id);
        return view('pacientes.show', compact('paciente'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $paciente = Paciente::with('user')->findOrFail($id);
        return view('pacientes.edit', compact('paciente'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $paciente = Paciente::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $paciente->user_id,
            'cpf' => 'required|string|max:14|unique:pacientes,cpf,' . $id,
            'data_nascimento' => 'required|date',
            'telefone' => 'nullable|string|max:20',
            'endereco' => 'nullable|string',
            'sexo' => 'nullable|in:M,F,Outro',
            'tipo_sanguineo' => 'nullable|string|max:5',
            'alergias' => 'nullable|string',
        ]);

        // Atualizar usuário
        $paciente->user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        // Atualizar senha se fornecida
        if ($request->filled('password')) {
            $request->validate(['password' => 'string|min:8|confirmed']);
            $paciente->user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        // Atualizar paciente
        $paciente->update([
            'cpf' => $request->cpf,
            'data_nascimento' => $request->data_nascimento,
            'telefone' => $request->telefone,
            'endereco' => $request->endereco,
            'sexo' => $request->sexo,
            'tipo_sanguineo' => $request->tipo_sanguineo,
            'alergias' => $request->alergias,
        ]);

        return redirect()->route('pacientes.index')
            ->with('success', 'Paciente atualizado com sucesso!');
    }

    /**
     * Exibe o formulário de edição do paciente logado.
     */
    public function editPerfil()
    {
        $user = Auth::user();
        $paciente = $user->paciente;

        if (!$paciente) {
            return redirect()->route('dashboard')
                ->with('error', 'Perfil de paciente não encontrado.');
        }

        $paciente->load('user');

        return view('pacientes.edit', compact('paciente'));
    }

    /**
     * Atualiza os dados do paciente logado.
     */
    public function updatePerfil(Request $request)
    {
        $user = Auth::user();
        $paciente = $user->paciente;

        if (!$paciente) {
            return redirect()->route('dashboard')
                ->with('error', 'Perfil de paciente não encontrado.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $paciente->user_id,
            'cpf' => 'required|string|max:14|unique:pacientes,cpf,' . $paciente->id,
            'data_nascimento' => 'required|date',
            'telefone' => 'nullable|string|max:20',
            'endereco' => 'nullable|string',
            'sexo' => 'nullable|in:M,F,Outro',
            'tipo_sanguineo' => 'nullable|string|max:5',
            'alergias' => 'nullable|string',
        ]);

        $paciente->user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        if ($request->filled('password')) {
            $request->validate(['password' => 'string|min:8|confirmed']);
            $paciente->user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        $paciente->update([
            'cpf' => $request->cpf,
            'data_nascimento' => $request->data_nascimento,
            'telefone' => $request->telefone,
            'endereco' => $request->endereco,
            'sexo' => $request->sexo,
            'tipo_sanguineo' => $request->tipo_sanguineo,
            'alergias' => $request->alergias,
        ]);

        return redirect()->route('dashboard')
            ->with('success', 'Dados atualizados com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $paciente = Paciente::findOrFail($id);
        $user = $paciente->user;
        
        $paciente->delete();
        $user->delete();

        return redirect()->route('pacientes.index')
            ->with('success', 'Paciente excluído com sucesso!');
    }
}
