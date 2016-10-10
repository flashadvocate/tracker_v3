<?php

namespace App\Settings;

use App\Division;

class LocalitySettings
{

    use Settable;

    /**
     * @var Division
     */
    protected $division;

    /**
     * Settings constructor.
     * @param array $settings
     * @param DivisionSettings|Division $division
     */
    public function __construct(array $settings, Division $division)
    {
        $this->settings = $settings;
        $this->division = $division;
    }

    protected function persist()
    {
        return $this->division->update(['locality' => $this->settings]);
    }
}
