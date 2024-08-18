<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        if(Auth::user()->admin)
        {
            $data['admin'] = true;
            $data['role_id'] = 1;
            $data['branch_id'] = Auth::user()->branch->id;
        }

        return static::getModel()::create($data);
    }

}
