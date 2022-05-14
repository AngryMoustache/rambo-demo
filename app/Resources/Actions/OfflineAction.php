<?php

namespace App\Resources\Actions;

use AngryMoustache\Rambo\Actions\Action;

class OfflineAction extends Action
{
    public $icon = 'fas fa-eraser';
    public $label = 'Offline';

    public static $livewireComponent = 'offline-action';

    public function shouldHide($resource = null)
    {
        return rand(0, 1);
    }
}
