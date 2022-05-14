<?php

namespace App\Http\Livewire;

use AngryMoustache\Rambo\Http\Livewire\Actions\ActionComponent;

class OfflineAction extends ActionComponent
{
    public function handle()
    {
        $this->resource->item->online = ! $this->resource->item->online;
        $this->resource->item->save();
        $this->emit('refresh');
    }
}
