<?php

namespace App\View\Components\Utilities;

use Carbon\Carbon;
use Illuminate\View\Component;

class DatePicker extends Component
{
    public $date;

    public $nepali_date;

    public $english_date;

    /*
    |--------------------------------------------------------------------------
    | Modes
    |--------------------------------------------------------------------------
    |
    | single = Single calendar render determined by $default
    | toggle = Duel calendar toggle mode
    | both = Visible both calendar
    */
    public $mode = 'toggle';

    // eng or nep
    public $default = "nep";

    public $nepDateAttribute = "nepali_date";

    public $engDateAttribute = "english_date";

    public $disabledCounterpart = false;

    public $disabledCounterpartCalendar;

    public $required = false;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($date = null, $mode = 'toggle', $default = "nep", $nepDateAttribute = "nepali_date", $engDateAttribute = "english_date", $disabledCounterpart = false, $autoUpdateInput = false, $required = false)
    {
        $this->mode = $mode;
        $this->default = $default;
        $this->nepDateAttribute = $nepDateAttribute;
        $this->engDateAttribute = $engDateAttribute;
        $this->disabledCounterpart = $disabledCounterpart;
        $this->required = $required;

        $eng_today = Carbon::now()->format('Y-m-d');
        $nep_today = nepaliToday();

        if ($default == "nep") {
            $this->nepali_date = $date ?? ($autoUpdateInput ? $nep_today : null);
            if (!is_null($this->nepali_date)) {
                $exploded = explode('-', $this->nepali_date);
                $english_date = date_converter()->nep_to_eng($exploded[0], $exploded[1], $exploded[2]);
                $this->english_date = $english_date['year'] . '-' . $english_date['month'] . '-' . $english_date['date'];
            }
        }

        if ($default == "eng") {
            $this->english_date = $date ?? ($autoUpdateInput ? $eng_today : null);
            if (!is_null($this->english_date)) {
                $exploded = explode('-', $this->english_date);
                $nepali_date = date_converter()->eng_to_nep($exploded[0], $exploded[1], $exploded[2]);
                $this->nepali_date = $nepali_date['year'] . '-' . $nepali_date['month'] . '-' . $nepali_date['date'];
            }
        }

        if ($this->disabledCounterpart) {
            $this->disabledCounterpartCalendar = $this->default == "nep" ? "eng" : "nep";
        }
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.utilities.date-picker.' . (in_array(trim($this->mode), ['single', 'toggle', 'both']) ? $this->mode : 'toggle'));
    }
}
