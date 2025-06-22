<?php

namespace App\View\Components\layout;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class notFound extends Component
{
    private $msg;
    private $width;
    /**
     * Create a new component instance.
     */
    public function __construct($msg = null, $width = null)
    {
        $this->msg   = $msg ?? 'Página não encontrada';
        $this->width = $width ?? false;
    }


    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.layout.not-found');
    }
}
