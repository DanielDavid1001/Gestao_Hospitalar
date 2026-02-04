<?php

namespace App\Http\Controllers;

use App\Models\Medico;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class MedicoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $medicos = Medico::with('user')->paginate(10);
        return view('medicos.index', compact('medicos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('medicos.create');
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
            'crm' => 'required|string|max:20|unique:medicos,crm',
            'especialidade' => 'required|string|max:255',
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
     */
    public function show(string $id)
    {
        $medico = Medico::with('user')->findOrFail($id);
        return view('medicos.show', compact('medico'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $medico = Medico::with('user')->findOrFail($id);
        return view('medicos.edit', compact('medico'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $medico = Medico::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $medico->user_id,
            'crm' => 'required|string|max:20|unique:medicos,crm,' . $id,
            'especialidade' => 'required|string|max:255',
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
}
