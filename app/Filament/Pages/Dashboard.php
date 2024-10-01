<?php

namespace App\Filament\Pages;

use App\Filament\Resources\AppointmentResource;
use Filament\Widgets;

class Dashboard extends \Filament\Pages\Dashboard
{

    public function getWidgets(): array
    {
        return [
            AppointmentResource\Widgets\LatestAppointments::class,
            Widgets\AccountWidget::class,
            //Widgets\FilamentInfoWidget::class,
        ];
    }

    public function getColumns(): int
    {
        return 1;
    }
}
