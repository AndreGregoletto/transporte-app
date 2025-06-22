<?php

namespace App\View\Components\layout\modal\olhoVivo;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class InfoLineModal extends Component
{
    public $cl;
    public $lt;
    public $tl;
    public $tp;
    public $ts;
    public $sl;
    public $frequency;

    /**
     * Create a new component instance.
     */
    public function __construct($cl, $lt, $tl, $tp, $ts, $sl, $frequency = null)
    {
        $this->cl   = $cl;
        $this->lt   = $lt;
        $this->tl   = $tl;
        $this->tp   = $tp;
        $this->ts   = $ts;
        $this->sl   = $sl;
        $this->frequency = $frequency ?? [];
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.layout.modal.olho-vivo.info-line-modal');
    }
}
