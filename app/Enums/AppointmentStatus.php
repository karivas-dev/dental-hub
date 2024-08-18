<?php

namespace App\Enums;

enum AppointmentStatus: string
{
    use CasesAsOptions;
    
    case Programada = 'Programada';
    case Reagendada = 'Reagendada';
    case Cancelada = 'Cancelada';
    case Completada = 'Completada';
}
