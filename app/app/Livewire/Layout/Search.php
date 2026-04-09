<?php

namespace App\Livewire\Layout;

use Livewire\Component;

class Search extends Component
{
    public function highlight($text, $search)
{
    return str_ireplace(
        $search,
        '<span class="bg-yellow-200 font-bold">'.$search.'</span>',
        $text
    );
}
    public function render()
    {
        return view('livewire.layout.search');
    }
}
