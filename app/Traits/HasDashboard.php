<?php

namespace App\Traits;

trait HasDashboard
{
    public function dataInfo(String $name, String $icon, String $bg_color, int $total = null)
    {
        return [
            'name' => $name,
            'icon' => $icon,
            'bg_color' => $bg_color,
            'total' => $total ?? (new $this->model())->all()->count(),
        ];
    }
}
