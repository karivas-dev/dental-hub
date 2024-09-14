<?php

namespace App\Enums;

enum System: string
{
    use CasesAsOptions;
    case Respiratorio = 'Respiratorio';
    case Cardiovascular = 'Cardiovascular';
    case Digestivo = 'Digestivo';
    case Endocrino = 'Endocrino';
    case Excretor = 'Excretor';
    case Inmunologico = 'Inmunológico';
    case Muscular = 'Muscular';
    case Nervioso = 'Nervioso';
    case Reproductor = 'Reproductor';
    case Oseo = 'Óseo';
    case Circulatorio = 'Circulatorio';
    case Linfatico = 'Linfático';
    case Tegumentario = 'Tegumentario';
}
