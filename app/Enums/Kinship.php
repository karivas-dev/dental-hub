<?php

namespace App\Enums;

enum Kinship: string
{
    case Padre = 'Padre';
    case Madre = 'Madre';
    case Hermano_a = 'Hermano/a';
    case Abuelo_a = 'Abuelo/a';
    case Tio_a = 'Tio/a';
    case Primo_a = 'Primo/a';
    case Sobrino_a = 'Sobrino/a';
    case Tatara_Abuelo_a = 'Tatara-abuelo/a';
    case Amigo_a = 'Amigo/a';
    case Otro = 'Otro';
}
