<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MedicoAvailability;
use App\Models\Medico;
use Carbon\Carbon;

class MedicoAvailabilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pegar todos os médicos cadastrados
        $medicos = Medico::all();

        if ($medicos->isEmpty()) {
            $this->command->warn('Nenhum médico encontrado. Execute AdminSeeder primeiro.');
            return;
        }

        // Para cada médico, criar disponibilidades para os próximos 30 dias
        foreach ($medicos as $medico) {
            $startDate = now()->addDay();

            // Segunda a sexta (dias úteis)
            for ($i = 0; $i < 30; $i++) {
                $date = $startDate->copy()->addDays($i);

                // Pular finais de semana (6 = sábado, 0 = domingo)
                if ($date->dayOfWeek === 6 || $date->dayOfWeek === 0) {
                    continue;
                }

                // Manhã: 08:00-09:00
                $this->criarDisponibilidade($medico->id, $date, '08:00', '09:00', 'manhã');

                // Manhã: 10:00-11:00
                $this->criarDisponibilidade($medico->id, $date, '10:00', '11:00', 'manhã');

                // Tarde: 14:00-15:00
                $this->criarDisponibilidade($medico->id, $date, '14:00', '15:00', 'tarde');

                // Tarde: 15:00-16:00
                $this->criarDisponibilidade($medico->id, $date, '15:00', '16:00', 'tarde');
            }
        }

        $this->command->info('Disponibilidades dos médicos criadas com sucesso!');
    }

    private function criarDisponibilidade($medicoId, $data, $horaInicio, $horaFim, $periodo)
    {
        $existe = MedicoAvailability::where('medico_id', $medicoId)
            ->whereDate('data', $data)
            ->whereTime('hora_inicio', $horaInicio)
            ->whereTime('hora_fim', $horaFim)
            ->where('periodo', $periodo)
            ->exists();

        if (!$existe) {
            MedicoAvailability::create([
                'medico_id' => $medicoId,
                'data' => $data->toDateString(),
                'hora_inicio' => $horaInicio,
                'hora_fim' => $horaFim,
                'periodo' => $periodo,
            ]);
        }
    }
}
