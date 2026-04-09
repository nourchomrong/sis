<?php

namespace App\Livewire\Layout;

use Livewire\Component;

class Pagination extends Component
{
    public $currentPage = 1;
    public $totalPages = 1;

    public function gotoPage($page)
    {
        $this->currentPage = $page;

        // send event to parent
        $this->dispatch('pageChanged', page: $page);
    }

    public function next()
    {
        if ($this->currentPage < $this->totalPages) {
            $this->gotoPage($this->currentPage + 1);
        }
    }

    public function previous()
    {
        if ($this->currentPage > 1) {
            $this->gotoPage($this->currentPage - 1);
        }
    }

    public function render()
    {
        return view('livewire.layout.pagination');
    }
}
