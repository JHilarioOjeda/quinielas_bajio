<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Livewire\WithPagination;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Auth;
use DB;
use Log;
use Http;
use Throwable;
use App\Models\QuinielaEvent;


class QuinielaEdit extends Component
{

    use WithFileUploads;

    public $matches = [], $quinielaEvent;


    public function render()
    {
        return view('livewire.admin.quiniela-edit');
    }
}
