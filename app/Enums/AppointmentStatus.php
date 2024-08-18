<?php

namespace App\Enums;

enum AppointmentStatus: string
{
    case Programada = 'Programada';
    case Reagendada = 'Reagendada';
    case Cancelada = 'Cancelada';
    case Completada = 'Completada';
}
