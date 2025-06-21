<?php

namespace App\View\Components\layout\modal\olhoVivo;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class RemoveLineModal extends Component
{
    public $id;
    public $cl;
    public $lt;
    public $tl;
    public $tp;
    public $ts;
    public $sl;
    /**
     * Create a new component instance.
     */
    public function __construct($id, $cl, $lt, $tl, $tp, $ts, $sl)
    {
        $this->id = $id;
        $this->cl = $cl;
        $this->lt = $lt;
        $this->tl = $tl;
        $this->tp = $tp;
        $this->ts = $ts;
        $this->sl = $sl;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.layout.modal.olho-vivo.remove-line-modal');
    }
}
