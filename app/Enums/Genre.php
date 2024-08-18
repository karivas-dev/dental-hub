<?php

namespace App\Enums;

enum Genre: string
{
    use CasesAsOptions;
    
    case Femenino = 'Femenino';
    case Masculino = 'Masculino';
}
