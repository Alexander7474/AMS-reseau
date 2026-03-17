<?php

class Graph
{
    public static function render(array $datasets, array $labels, array $options = []): string
    {
        $width   = $options['width']  ?? 600;
        $height  = $options['height'] ?? 350;
        $padding = $options['padding'] ?? 50;

        $xLabel = $options['xLabel'] ?? '';
        $yLabel = $options['yLabel'] ?? '';

        $colors = ['#3b82f6', '#ef4444', '#10b981', '#f59e0b', '#8b5cf6'];

        // Collect all values
        $allValues = [];
        foreach ($datasets as $set) {
            $allValues = array_merge($allValues, $set['data']);
        }

        // valeur de l'axe Y 
        $maxValue = max($allValues);
        $minValue = 0;

        $count = count($labels);
        $stepX = ($width - 2 * $padding) / max(1, $count - 1);
        $graphHeight = $height - 2 * $padding;

        // Normalize Y
        $normalizeY = function ($value) use ($minValue, $maxValue, $graphHeight, $height, $padding) {
            $ratio = ($value - $minValue) / (($maxValue - $minValue) ?: 1);
            return $height - $padding - $ratio * $graphHeight;
        };

        $svg = "<svg width='100%' height='{$height}' viewBox='0 0 {$width} {$height}'>";
        $svg .= "<style>
            .axis { stroke: #333; stroke-width: 1; }
            .grid { stroke: #e5e7eb; stroke-width: 1; }
            .label { font-size: 11px; fill: #555; font-family: Arial, sans-serif; }
        </style>";

        // Y-axis ticks
        $gridLines = $options['gridLines'] ?? 8;
        for ($i = 0; $i <= $gridLines; $i++) {
            $ratio = $i / $gridLines;
            $y = $padding + ($ratio * $graphHeight);

            // Value at this tick
            $value = $maxValue - ($ratio * ($maxValue - $minValue));
            $value = round($value, 2);

            // Grid line
            $svg .= "<line x1='{$padding}' y1='{$y}' x2='".($width - $padding)."' y2='{$y}' class='grid'/>";

            // Y-axis label
            $svg .= "<text x='".($padding - 10)."' y='".($y + 4)."' text-anchor='end' class='label'>{$value}</text>";
        }

        // Axes
        $svg .= "<line x1='{$padding}' y1='{$padding}' x2='{$padding}' y2='".($height-$padding)."' class='axis'/>";
        $svg .= "<line x1='{$padding}' y1='".($height-$padding)."' x2='".($width-$padding)."' y2='".($height-$padding)."' class='axis'/>";

        // X labels
        foreach ($labels as $i => $label) {
            $x = $padding + $i * $stepX;
            $y = $height - $padding + 15;
            $svg .= "<text x='{$x}' y='{$y}' text-anchor='middle' class='label'>{$label}</text>";
        }

        // Axis titles
        if ($xLabel) {
            $svg .= "<text x='".($width/2)."' y='".($height-5)."' text-anchor='middle' class='label'>{$xLabel}</text>";
        }

        if ($yLabel) {
            $svg .= "<text x='15' y='".($height/2)."' transform='rotate(-90 15,".($height/2).")' text-anchor='middle' class='label'>{$yLabel}</text>";
        }

        // Draw datasets
        foreach ($datasets as $index => $set) {
            $data  = $set['data'];
            $color = $set['color'] ?? $colors[$index % count($colors)];

            $points = [];
            foreach ($data as $i => $value) {
                $x = $padding + $i * $stepX;
                $y = $normalizeY($value);
                $points[] = "$x,$y";
            }

            $polyline = implode(' ', $points);

            $svg .= "<polyline 
                        fill='none' 
                        stroke='{$color}' 
                        stroke-width='2.5' 
                        stroke-linecap='round' 
                        stroke-linejoin='round'
                        points='{$polyline}' 
                    />";
        }

        $svg .= "</svg>";

        return $svg;
    }
}
