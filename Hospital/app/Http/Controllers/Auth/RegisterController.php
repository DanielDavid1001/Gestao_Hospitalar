<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Medico;
use App\Models\Paciente;
use App\Models\Admin;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     */
    protected function redirectTo(): string
    {
        $user = auth()->user();

        if ($user && $user->role === 'medico' && $user->medico) {
            return route('medicos.edit', $user->medico->id);
        }

        if ($user && $user->role === 'paciente' && $user->paciente) {
            return route('pacientes.edit', $user->paciente->id);
        }

        if ($user && $user->role === 'admin' && $user->admin) {
            return route('admins.edit', $user->admin->id);
        }

        return '/home';
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'in:admin,medico,paciente'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'role' => $data['role'],
            ]);

            if ($data['role'] === 'medico') {
                Medico::create([
                    'user_id' => $user->id,
                    'crm' => null,
                    'especialidade' => null,
                ]);
            }

            if ($data['role'] === 'paciente') {
                Paciente::create([
                    'user_id' => $user->id,
                    'cpf' => null,
                    'data_nascimento' => null,
                ]);
            }

            if ($data['role'] === 'admin') {
                Admin::create([
                    'user_id' => $user->id,
                    'cargo' => null,
                    'setor' => null,
                ]);
            }

            return $user;
        });
    }
}
