<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $admins = Admin::with('user')->paginate(10);
        return view('admins.index', compact('admins'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admins.create');
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
            'cargo' => 'nullable|string|max:255',
            'setor' => 'nullable|string|max:255',
            'telefone' => 'nullable|string|max:20',
            'observacoes' => 'nullable|string',
        ]);

        // Criar usuário
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'admin',
        ]);

        // Criar admin
        Admin::create([
            'user_id' => $user->id,
            'cargo' => $request->cargo,
            'setor' => $request->setor,
            'telefone' => $request->telefone,
            'observacoes' => $request->observacoes,
        ]);

        return redirect()->route('admins.index')
            ->with('success', 'Administrador cadastrado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $admin = Admin::with('user')->findOrFail($id);
        return view('admins.show', compact('admin'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $admin = Admin::with('user')->findOrFail($id);
        return view('admins.edit', compact('admin'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $admin = Admin::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $admin->user_id,
            'cargo' => 'nullable|string|max:255',
            'setor' => 'nullable|string|max:255',
            'telefone' => 'nullable|string|max:20',
            'observacoes' => 'nullable|string',
        ]);

        // Atualizar usuário
        $admin->user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        // Atualizar senha se fornecida
        if ($request->filled('password')) {
            $request->validate(['password' => 'string|min:8|confirmed']);
            $admin->user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        // Atualizar admin
        $admin->update([
            'cargo' => $request->cargo,
            'setor' => $request->setor,
            'telefone' => $request->telefone,
            'observacoes' => $request->observacoes,
        ]);

        return redirect()->route('admins.index')
            ->with('success', 'Administrador atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $admin = Admin::findOrFail($id);
        $user = $admin->user;
        
        $admin->delete();
        $user->delete();

        return redirect()->route('admins.index')
            ->with('success', 'Administrador excluído com sucesso!');
    }
}

