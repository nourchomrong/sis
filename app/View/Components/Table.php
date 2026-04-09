<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Table extends Component
{
    public $headers;
    public $data;
    public $emptyMessage;

    public function __construct(
        $headers = [],
        $data = [],
        $emptyMessage = 'No data found.'
    ) {
        $this->headers = $headers;
        $this->data = $data;
        $this->emptyMessage = $emptyMessage;
    }

    public function render()
    {
        return view('components.table');
    }
}
