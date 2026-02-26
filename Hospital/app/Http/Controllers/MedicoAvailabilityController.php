<?php

namespace App\Http\Controllers;

use App\Models\Medico;
use App\Models\MedicoAvailability;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class MedicoAvailabilityController extends Controller
{
    /**
     * Display a listing of the physician's available times
     */
    public function index()
    {
        $user = Auth::user();
        $medico = Medico::where('user_id', $user->id)->first();

        if (!$medico) {
            return redirect()->route('dashboard')
                ->with('error', 'Médico não encontrado.');
        }

        $disponibilidades = MedicoAvailability::where('medico_id', $medico->id)
            ->orderBy('data')
            ->orderBy('hora_inicio')
            ->paginate(15);

        return view('medicos.disponibilidades.index', compact('medico', 'disponibilidades'));
    }


    /**
     * Show the form for creating a new availability period
     */
    public function createPeriodo()
    {
        $user = Auth::user();
        $medico = Medico::where('user_id', $user->id)->first();

        if (!$medico) {
            return redirect()->route('dashboard')
                ->with('error', 'Médico não encontrado.');
        }

        $periodos = ['manhã', 'tarde', 'noite'];

        return view('medicos.disponibilidades.periodo', compact('medico', 'periodos'));
    }

    /**
     * Store a new availability period with exclusions
     */
    public function storePeriodo(Request $request)
    {
        $user = Auth::user();
        $medico = Medico::where('user_id', $user->id)->first();

        if (!$medico) {
            return redirect()->route('dashboard')
                ->with('error', 'Médico não encontrado.');
        }

        $validated = $request->validate([
            'data_inicio' => 'required|date_format:Y-m-d|after_or_equal:today',
            'data_fim' => 'required|date_format:Y-m-d|after_or_equal:data_inicio',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fim' => 'required|date_format:H:i|after:hora_inicio',
            'periodo' => 'required|in:manhã,tarde,noite',
            'datas_excluidas' => 'nullable|string',
        ], [
            'data_inicio.required' => 'Data de início é obrigatória',
            'data_inicio.date_format' => 'Formato de data inválido (use yyyy-mm-dd)',
            'data_inicio.after_or_equal' => 'A data de início não pode ser no passado',
            'data_fim.required' => 'Data de fim é obrigatória',
            'data_fim.date_format' => 'Formato de data inválido (use yyyy-mm-dd)',
            'data_fim.after_or_equal' => 'A data de fim deve ser após a data de início',
            'hora_inicio.required' => 'Hora de início é obrigatória',
            'hora_inicio.date_format' => 'Formato de hora inválido (HH:mm)',
            'hora_fim.required' => 'Hora de fim é obrigatória',
            'hora_fim.date_format' => 'Formato de hora inválido (HH:mm)',
            'hora_fim.after' => 'Hora de fim deve ser após a hora de início',
            'periodo.required' => 'Período é obrigatório',
            'periodo.in' => 'Período inválido',
        ]);

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

        foreach (CarbonPeriod::create($inicio, $fim) as $date) {
            $data = $date->format('Y-m-d');

            if (isset($exclusions[$data])) {
                $excluded++;
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

        return redirect()->route('medico.disponibilidades.index')
            ->with('success', "Período criado: {$created} novas disponibilidades, {$excluded} datas excluídas, {$existing} já existentes.");
    }


    /**
     * Show calendar view for adding multiple availabilities
     */
    public function calendario()
    {
        $user = Auth::user();
        $medico = Medico::where('user_id', $user->id)->first();

        if (!$medico) {
            return redirect()->route('dashboard')
                ->with('error', 'Médico não encontrado.');
        }

        $periodos = ['manhã', 'tarde', 'noite'];
        
        // Pega disponibilidades do próximo mês
        $disponibilidades = MedicoAvailability::where('medico_id', $medico->id)
            ->futuros()
            ->get()
            ->groupBy('data');

        return view('medicos.disponibilidades.calendario', compact('medico', 'periodos', 'disponibilidades'));
    }
}
