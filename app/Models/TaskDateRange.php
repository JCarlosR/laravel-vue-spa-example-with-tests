<?php

namespace App\Models;

use Carbon\Carbon;

class TaskDateRange
{
    /**
     * @var Carbon
     */
    private $from;

    /**
     * @var Carbon
     */
    private $to;

    public function setFrom(Carbon $from): TaskDateRange
    {
        $this->from = $from;
        
        return $this;
    }

    public function setTo(Carbon $to): TaskDateRange
    {
        $this->to = $to;
        
        return $this;
    }
    
    public function getDates()
    {
        $dates = collect();
        
        while ($this->from >= $this->to) {
            $dates->add(
                (clone $this->from)->toDateString()   
            );
            
            $this->from->subDay();
        }
        
        return $dates;
    }
    
}
