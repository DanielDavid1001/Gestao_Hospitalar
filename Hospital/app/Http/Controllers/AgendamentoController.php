<?php

namespace App\Http\Controllers;

use App\Models\Agendamento;
use App\Models\Medico;
use App\Models\MedicoAvailability;
use App\Models\MedicalSpecialty;
use App\Models\Paciente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AgendamentoController extends Controller
{
    /**
     * Mostrar opções de agendamento (especialidade ou profissional)
     */
    public function escolher()
    {
        $user = Auth::user();
        $paciente = $user->paciente;

        if (!$paciente) {
            return redirect()->route('dashboard')
                ->with('error', 'Perfil de paciente não encontrado.');
        }

        $especialidades = MedicalSpecialty::query()
            ->orderBy('name')
            ->get();

        if ($especialidades->isEmpty()) {
            $defaultSpecialty = (string) config('medical.default_specialty', 'Clinico Geral');
            $especialidades = collect([(object) ['name' => $defaultSpecialty]]);
        }

        return view('agendamentos.escolher', compact('paciente', 'especialidades'));
    }

    /**
     * Listar médicos por especialidade
     */
    public function porEspecialidade($especialidade)
    {
        $user = Auth::user();
        $paciente = $user->paciente;

        if (!$paciente) {
            return redirect()->route('dashboard')
                ->with('error', 'Perfil de paciente não encontrado.');
        }

        $medicos = Medico::where('especialidade', $especialidade)
            ->with('availabilities')
            ->get();

        if ($medicos->isEmpty()) {
            return view('agendamentos.sem-disponibilidade', compact('paciente', 'especialidade'));
        }

        // Filtrar médicos que tenha disponibilidade futura
        $medicos = $medicos->filter(function ($medico) {
            return $medico->availabilities()
                ->where('data', '>=', now()->toDateString())
                ->exists();
        });

        if ($medicos->isEmpty()) {
            return view('agendamentos.sem-disponibilidade', compact('paciente', 'especialidade'));
        }

        return view('agendamentos.selecionar-medico', compact('paciente', 'medicos', 'especialidade'));
    }

    /**
     * Listar todos os médicos com disponibilidade
     */
    public function porProfissional()
    {
        $user = Auth::user();
        $paciente = $user->paciente;

        if (!$paciente) {
            return redirect()->route('dashboard')
                ->with('error', 'Perfil de paciente não encontrado.');
        }

        $medicos = Medico::with('availabilities')
            ->get()
            ->filter(function ($medico) {
                return $medico->availabilities()
                    ->where('data', '>=', now()->toDateString())
                    ->exists();
            });

        if ($medicos->isEmpty()) {
            return view('agendamentos.sem-disponibilidade', compact('paciente'));
        }

        return view('agendamentos.selecionar-medico', compact('paciente', 'medicos'));
    }

    /**
     * Mostrar disponibilidades do médico
     */
    public function disponibilidades($medico_id)
    {
        $user = Auth::user();
        $paciente = $user->paciente;

        if (!$paciente) {
            return redirect()->route('dashboard')
                ->with('error', 'Perfil de paciente não encontrado.');
        }

        $medico = Medico::findOrFail($medico_id);
        
        $disponibilidades = MedicoAvailability::where('medico_id', $medico_id)
            ->where('data', '>=', now()->toDateString())
            ->orderBy('data')
            ->orderBy('hora_inicio')
            ->get()
            ->groupBy(function ($item) {
                return Carbon::parse($item->data)->toDateString();
            });

        if ($disponibilidades->isEmpty()) {
            return view('agendamentos.sem-disponibilidade', compact('paciente', 'medico'));
        }

        return view('agendamentos.selecionar-horario', compact('paciente', 'medico', 'disponibilidades'));
    }

    /**
     * Mostrar formulário de confirmação
     */
    public function confirmar($medico_id, $data, $hora_inicio = null)
    {
        $user = Auth::user();
        $paciente = $user->paciente;

        if (!$paciente) {
            return redirect()->route('dashboard')
                ->with('error', 'Perfil de paciente não encontrado.');
        }

        $medico = Medico::findOrFail($medico_id);
        
        // Verificar se a disponibilidade existe
        $disponibilidade = MedicoAvailability::where('medico_id', $medico_id)
            ->where('data', $data)
            ->when($hora_inicio, function ($query) use ($hora_inicio) {
                return $query->where('hora_inicio', $hora_inicio);
            })
            ->first();

        if (!$disponibilidade) {
            return redirect()->route('agendamentos.disponibilidades', $medico_id)
                ->with('error', 'Disponibilidade não encontrada.');
        }

        return view('agendamentos.confirmar', compact('paciente', 'medico', 'disponibilidade'));
    }

    /**
     * Salvar agendamento
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $paciente = $user->paciente;

        if (!$paciente) {
            return redirect()->route('dashboard')
                ->with('error', 'Perfil de paciente não encontrado.');
        }

        $validated = $request->validate([
            'medico_id' => 'required|exists:medicos,id',
            'data' => 'required|date_format:Y-m-d|after_or_equal:today',
            'hora_inicio' => 'required|date_format:H:i',
            'nome_paciente' => 'required|string|max:255',
            'data_nascimento' => 'required|date_format:d/m/Y|before:today',
            'nome_responsavel' => 'nullable|string|max:255',
        ], [
            'medico_id.required' => 'Médico é obrigatório',
            'medico_id.exists' => 'Médico inválido',
            'data.required' => 'Data é obrigatória',
            'data.date_format' => 'Formato de data inválido',
            'data.after_or_equal' => 'Data não pode ser no passado',
            'hora_inicio.required' => 'Hora é obrigatória',
            'hora_inicio.date_format' => 'Formato de hora inválido (HH:mm)',
            'nome_paciente.required' => 'Nome do paciente é obrigatório',
            'data_nascimento.required' => 'Data de nascimento é obrigatória',
            'data_nascimento.date_format' => 'Formato de data inválido (dd/mm/yyyy)',
            'data_nascimento.before' => 'Data de nascimento deve ser no passado',
        ]);

        // Converter data de nascimento de dd/mm/yyyy para yyyy-mm-dd
        $dataNasc = \DateTime::createFromFormat('d/m/Y', $validated['data_nascimento'])->format('Y-m-d');

        // Verificar se a disponibilidade existe
        $disponibilidade = MedicoAvailability::where('medico_id', $validated['medico_id'])
            ->where('data', $validated['data'])
            ->where('hora_inicio', $validated['hora_inicio'])
            ->first();

        if (!$disponibilidade) {
            return redirect()->back()
                ->with('error', 'Disponibilidade não encontrada ou já foi agendada.');
        }

        try {
            $data_hora = Carbon::createFromFormat('Y-m-d H:i', $validated['data'] . ' ' . $validated['hora_inicio']);

            // Criar agendamento
            Agendamento::create([
                'paciente_id' => $paciente->id,
                'medico_id' => $validated['medico_id'],
                'data_hora' => $data_hora,
                'nome_paciente' => $validated['nome_paciente'],
                'data_nascimento' => $dataNasc,
                'nome_responsavel' => $validated['nome_responsavel'],
                'status' => 'pendente',
            ]);

            return redirect()->route('dashboard')
                ->with('success', 'Consulta agendada com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erro ao agendar consulta: ' . $e->getMessage());
        }
    }

    /**
     * Listar agendamentos do paciente
     */
    public function meus()
    {
        $user = Auth::user();
        $paciente = $user->paciente;

        if (!$paciente) {
            return redirect()->route('dashboard')
                ->with('error', 'Perfil de paciente não encontrado.');
        }

        $agendamentos = Agendamento::where('paciente_id', $paciente->id)
            ->with('medico')
            ->orderBy('data_hora', 'desc')
            ->paginate(10);

        return view('agendamentos.meus', compact('paciente', 'agendamentos'));
    }

    /**
     * Cancelar agendamento
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $paciente = $user->paciente;

        if (!$paciente) {
            return redirect()->route('dashboard')
                ->with('error', 'Perfil de paciente não encontrado.');
        }

        $agendamento = Agendamento::findOrFail($id);

        // Verificar se o agendamento pertence ao paciente
        if ($agendamento->paciente_id !== $paciente->id) {
            return redirect()->back()
                ->with('error', 'Você não tem permissão para cancelar este agendamento.');
        }

        // Só permite cancelar consultas futuras e com status pendente ou confirmada
        if ($agendamento->data_hora <= now() || !in_array($agendamento->status, ['pendente', 'confirmada'])) {
            return redirect()->back()
                ->with('error', 'Este agendamento não pode ser cancelado.');
        }

        $agendamento->update(['status' => 'cancelada']);

        return redirect()->route('agendamentos.meus')
            ->with('success', 'Agendamento cancelado com sucesso.');
    }
}
