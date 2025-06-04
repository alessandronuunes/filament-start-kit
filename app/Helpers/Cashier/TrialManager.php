<?php

declare(strict_types=1);

namespace App\Helpers\Cashier;

use App\Models\Team;
use Carbon\Carbon;

class TrialManager
{
    /**
     * Inicia um período de teste para um Team sem exigir detalhes de pagamento
     *
     * @param  Team  $team  O Team para iniciar o trial
     * @param  int  $days  Número de dias de trial (padrão: 7)
     */
    public static function startTrial(Team $team, int $days = 7): Team
    {
        // Se for cliente gratuito, não aplicar trial
        if ($team->is_free === 1) {
            return $team;
        }

        // Se já estiver em um trial, não fazer nada
        if ($team->trial_ends_at !== null && $team->trial_ends_at > Carbon::now()) {
            return $team;
        }

        // Definir a data de término do trial (convertendo para string para compatibilidade de tipos)
        $team->trial_ends_at = Carbon::now()->addDays($days)->toDateTimeString();
        $team->save();

        return $team;
    }

    /**
     * Verifica se um Team está atualmente em período de trial
     * Clientes com acesso gratuito são tratados como se não estivessem em trial
     *
     * @param  Team  $team  O Team para verificar
     */
    public static function isOnTrial(Team $team): bool
    {
        // Se for cliente gratuito, retorna false (não está em trial)
        if ($team->is_free === 1) {
            return false;
        }

        return $team->trial_ends_at !== null && $team->trial_ends_at > Carbon::now();
    }

    /**
     * Retorna o número de dias restantes no trial
     * Para clientes gratuitos, sempre retorna null
     *
     * @param  Team  $team  O Team para verificar
     * @return int|null Número de dias restantes ou null se não estiver em trial
     */
    public static function daysLeft(Team $team): ?int
    {
        // Clientes gratuitos não têm dias de trial
        if ($team->is_free === 1) {
            return null;
        }

        if (! self::isOnTrial($team)) {
            return null;
        }

        return (int) Carbon::now()->diffInDays($team->trial_ends_at);
    }

    /**
     * Encerra imediatamente o período de trial
     *
     * @param  Team  $team  O Team para encerrar o trial
     */
    public static function endTrial(Team $team): Team
    {
        // Se for cliente gratuito, não modificar o trial
        if ($team->is_free === 1) {
            return $team;
        }

        $team->trial_ends_at = Carbon::now()->subDay()->toDateTimeString();
        $team->save();

        return $team;
    }
}
