<?php

namespace App\Http\Controllers;

use App\Models\Agendamento;
use App\Models\Medico;
use App\Models\MedicoAvailability;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class MedicoAvailabilityController extends Controller
{
    private function resolveMedicoContext(Request $request): array
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            $medicosAdmin = Medico::with('user')
                ->get()
                ->sortBy(function ($medico) {
                    return mb_strtolower($medico->user->name ?? '');
                })
                ->values();

            if ($medicosAdmin->isEmpty()) {
                return [null, collect(), null];
            }

            $medicoId = (int) $request->input('medico_id');
            $medico = $medicosAdmin->firstWhere('id', $medicoId) ?? $medicosAdmin->first();

            return [$medico, $medicosAdmin, $medico->id];
        }

        $medico = Medico::where('user_id', $user->id)->first();

        return [$medico, collect(), $medico?->id];
    }

    /**
     * Display a listing of the physician's available times
     */
    public function index(Request $request)
    {
        [$medico, $medicosAdmin, $medicoSelecionadoId] = $this->resolveMedicoContext($request);

        if (!$medico) {
            return redirect()->route('dashboard')
                ->with('error', 'Médico não encontrado.');
        }

        $disponibilidades = MedicoAvailability::where('medico_id', $medico->id)
            ->orderBy('data')
            ->orderBy('hora_inicio')
            ->paginate(6);

        return view('medicos.disponibilidades.index', compact('medico', 'disponibilidades', 'medicosAdmin', 'medicoSelecionadoId'));
    }


    /**
     * Show the form for creating a new availability period
     */
    public function createPeriodo(Request $request)
    {
        [$medico, $medicosAdmin, $medicoSelecionadoId] = $this->resolveMedicoContext($request);

        if (!$medico) {
            return redirect()->route('dashboard')
                ->with('error', 'Médico não encontrado.');
        }

        $periodos = ['manhã', 'tarde', 'noite'];

        $datasComPeriodo = MedicoAvailability::where('medico_id', $medico->id)
            ->futuros()
            ->get(['data', 'periodo'])
            ->groupBy('periodo')
            ->map(function ($items) {
                return $items->map(function ($item) {
                    return Carbon::parse($item->data)->format('Y-m-d');
                })->unique()->values();
            })
            ->toArray();

        return view('medicos.disponibilidades.periodo', compact('medico', 'periodos', 'datasComPeriodo', 'medicosAdmin', 'medicoSelecionadoId'));
    }

    /**
     * Store a new availability period with exclusions
     */
    public function storePeriodo(Request $request)
    {
        $user = Auth::user();
        [$medico, $medicosAdmin, $medicoSelecionadoId] = $this->resolveMedicoContext($request);

        if (!$medico) {
            return redirect()->route('dashboard')
                ->with('error', 'Médico não encontrado.');
        }

        $validated = $request->validate([
            'data_inicio' => 'required|date_format:Y-m-d|after_or_equal:today',
            'data_fim' => 'required|date_format:Y-m-d',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fim' => 'required|date_format:H:i',
            'periodo' => 'required|in:manhã,tarde,noite',
            'datas_excluidas' => 'nullable|string',
        ], [
            'data_inicio.required' => 'Data de início é obrigatória',
            'data_inicio.date_format' => 'Formato de data inválido (use yyyy-mm-dd)',
            'data_inicio.after_or_equal' => 'A data de início não pode ser no passado',
            'data_fim.required' => 'Data de fim é obrigatória',
            'data_fim.date_format' => 'Formato de data inválido (use yyyy-mm-dd)',
            'hora_inicio.required' => 'Hora de início é obrigatória',
            'hora_inicio.date_format' => 'Formato de hora inválido (HH:mm)',
            'hora_fim.required' => 'Hora de fim é obrigatória',
            'hora_fim.date_format' => 'Formato de hora inválido (HH:mm)',
            'periodo.required' => 'Período é obrigatório',
            'periodo.in' => 'Período inválido',
        ]);

        $dataInicio = Carbon::createFromFormat('Y-m-d', $validated['data_inicio']);
        $dataFim = Carbon::createFromFormat('Y-m-d', $validated['data_fim']);

        if ($dataInicio->gt($dataFim)) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['hora_fim' => 'Data final deve ser após a data inicial']);
        }

        $rawExclusions = array_filter(array_map('trim', explode(',', (string) $validated['datas_excluidas'])));
        $invalidExclusions = [];
        $exclusions = [];

        foreach ($rawExclusions as $date) {
            $dt = \DateTime::createFromFormat('Y-m-d', $date);
            if (!$dt || $dt->format('Y-m-d') !== $date) {
                $invalidExclusions[] = $date;
                continue;
            }
            $exclusions[$date] = true;
        }

        if (!empty($invalidExclusions)) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['datas_excluidas' => 'Datas excluídas inválidas: ' . implode(', ', $invalidExclusions)]);
        }

        $inicio = Carbon::createFromFormat('Y-m-d', $validated['data_inicio'])->startOfDay();
        $fim = Carbon::createFromFormat('Y-m-d', $validated['data_fim'])->startOfDay();

        $created = 0;
        $existing = 0;
        $excluded = 0;
        $periodoDuplicado = 0;

        $datasJaComPeriodo = MedicoAvailability::where('medico_id', $medico->id)
            ->where('periodo', $validated['periodo'])
            ->whereBetween('data', [$validated['data_inicio'], $validated['data_fim']])
            ->pluck('data')
            ->map(function ($data) {
                return Carbon::parse($data)->format('Y-m-d');
            })
            ->unique()
            ->flip();

        foreach (CarbonPeriod::create($inicio, $fim) as $date) {
            $data = $date->format('Y-m-d');

            if (isset($exclusions[$data])) {
                $excluded++;
                continue;
            }

            if ($datasJaComPeriodo->has($data)) {
                $periodoDuplicado++;
                continue;
            }

            $availability = MedicoAvailability::firstOrCreate(
                [
                    'medico_id' => $medico->id,
                    'data' => $data,
                    'hora_inicio' => $validated['hora_inicio'],
                    'hora_fim' => $validated['hora_fim'],
                    'periodo' => $validated['periodo'],
                ],
                [
                    'medico_id' => $medico->id,
                    'data' => $data,
                    'hora_inicio' => $validated['hora_inicio'],
                    'hora_fim' => $validated['hora_fim'],
                    'periodo' => $validated['periodo'],
                ]
            );

            if ($availability->wasRecentlyCreated) {
                $created++;
            } else {
                $existing++;
            }
        }

        if ($created === 0 && $periodoDuplicado > 0) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['periodo' => "Período já cadastrado para {$periodoDuplicado} dia(s) selecionado(s)."]);
        }

        $redirectParams = $user->isAdmin() ? ['medico_id' => $medico->id] : [];

        return redirect()->route('medico.disponibilidades.index', $redirectParams)
            ->with('success', "Período criado: {$created} novas disponibilidades, {$excluded} datas excluídas, {$periodoDuplicado} dias ignorados por período já cadastrado, {$existing} já existentes.");
    }


    /**
     * Show calendar view for adding multiple availabilities
     */
    public function calendario(Request $request)
    {
        [$medico, $medicosAdmin, $medicoSelecionadoId] = $this->resolveMedicoContext($request);

        if (!$medico) {
            return redirect()->route('dashboard')
                ->with('error', 'Médico não encontrado.');
        }

        $periodos = ['manhã', 'tarde', 'noite'];
        
        // Pega somente os 3 próximos períodos disponíveis
        $disponibilidades = MedicoAvailability::where('medico_id', $medico->id)
            ->futuros()
            ->orderBy('data')
            ->orderBy('hora_inicio')
            ->get()
            ->groupBy(function ($disponibilidade) {
                return Carbon::parse($disponibilidade->data)->format('Y-m-d');
            })
            ->take(3);

        return view('medicos.disponibilidades.calendario', compact('medico', 'periodos', 'disponibilidades', 'medicosAdmin', 'medicoSelecionadoId'));
    }

    /**
     * Remove an availability record
     */
    public function destroy(Request $request, $id)
    {
        $user = Auth::user();
        $disponibilidade = MedicoAvailability::findOrFail($id);

        if ($user->isMedico()) {
            $medico = Medico::where('user_id', $user->id)->first();

            if (!$medico || (int) $disponibilidade->medico_id !== (int) $medico->id) {
                return redirect()->back()->with('error', 'Você não tem permissão para excluir esta disponibilidade.');
            }
        } elseif (!$user->isAdmin()) {
            return redirect()->back()->with('error', 'Você não tem permissão para excluir disponibilidades.');
        }

        $temConsultaAtiva = Agendamento::where('medico_id', $disponibilidade->medico_id)
            ->whereDate('data_hora', Carbon::parse($disponibilidade->data)->format('Y-m-d'))
            ->whereTime('data_hora', $disponibilidade->hora_inicio->format('H:i:s'))
            ->whereIn('status', ['pendente', 'confirmada'])
            ->exists();

        if ($temConsultaAtiva) {
            return redirect()->back()->with('error', 'Não é possível excluir: existe consulta pendente/confirmada neste horário.');
        }

        $disponibilidade->delete();

        $redirectParams = [];
        if ($user->isAdmin()) {
            $redirectParams['medico_id'] = (int) ($request->input('medico_id') ?: $disponibilidade->medico_id);
        }

        return redirect()->route('medico.disponibilidades.lista', $redirectParams)
            ->with('success', 'Disponibilidade excluída com sucesso.');
    }
}
