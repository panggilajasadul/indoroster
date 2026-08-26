<?php

namespace App\Helpers;

class BlockTheme
{
    public string $theme;

    public bool $isDark;

    public string $bgClasses;

    public string $headingColor;

    public string $subColor;

    public string $badgeClass;

    public string $cardBg;

    public string $cardTitle;

    public string $cardDesc;

    public string $btnPrimary;

    public string $btnSecondary;

    public static function resolve(?string $theme = 'white'): self
    {
        $obj = new self;
        $theme = $theme ?: 'white';
        $obj->theme = $theme;
        $obj->isDark = in_array($theme, ['dark', 'pattern-dark', 'gradient', 'gradient-terra', 'accent']);

        $obj->bgClasses = match ($theme) {
            'dark', 'pattern-dark' => 'bg-slate-950 text-white',
            'accent' => 'bg-gradient-to-br from-terra-600 via-terra-700 to-amber-900 text-white',
            'gradient' => 'bg-gradient-to-br from-slate-950 via-slate-900 to-terra-950 text-white',
            'gradient-terra' => 'bg-gradient-to-br from-terra-600 via-terra-700 to-slate-950 text-white',
            'pattern-light' => 'bg-slate-50 dark:bg-slate-900/90 text-slate-900 dark:text-slate-100',
            'slate' => 'bg-slate-100/90 dark:bg-slate-950 text-slate-900 dark:text-slate-100',
            default => 'bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100',
        };

        $obj->headingColor = match ($theme) {
            'dark', 'pattern-dark', 'gradient', 'gradient-terra', 'accent' => 'text-white',
            default => 'text-slate-900 dark:text-white',
        };

        $obj->subColor = match ($theme) {
            'dark', 'pattern-dark', 'gradient', 'gradient-terra' => 'text-slate-300',
            'accent' => 'text-white/85',
            'slate' => 'text-slate-600 dark:text-slate-400',
            default => 'text-slate-500 dark:text-slate-400',
        };

        $obj->badgeClass = match ($theme) {
            'dark', 'pattern-dark', 'gradient', 'gradient-terra' => 'bg-terra-500/20 text-terra-400 border border-terra-500/30',
            'accent' => 'bg-white/20 text-white border border-white/30',
            default => 'bg-terra-500/10 dark:bg-terra-500/20 text-terra-600 dark:text-terra-400 border border-terra-500/20 dark:border-terra-500/30',
        };

        $obj->cardBg = match ($theme) {
            'dark', 'pattern-dark', 'gradient', 'gradient-terra' => 'bg-slate-900/90 border border-slate-800 shadow-soft-sm text-white',
            'accent' => 'bg-white/10 border border-white/20 backdrop-blur-sm shadow-soft-sm text-white',
            'slate' => 'bg-white dark:bg-slate-900/90 border border-slate-200 dark:border-slate-800 shadow-soft-xs text-slate-800 dark:text-slate-100',
            default => 'bg-slate-50/90 dark:bg-slate-800/80 border border-slate-200/80 dark:border-slate-700/80 shadow-soft-xs text-slate-800 dark:text-slate-100',
        };

        $obj->cardTitle = match ($theme) {
            'dark', 'pattern-dark', 'gradient', 'gradient-terra', 'accent' => 'text-white',
            default => 'text-slate-800 dark:text-white',
        };

        $obj->cardDesc = match ($theme) {
            'dark', 'pattern-dark', 'gradient', 'gradient-terra' => 'text-slate-300',
            'accent' => 'text-white/85',
            default => 'text-slate-600 dark:text-slate-300',
        };

        $obj->btnPrimary = match ($theme) {
            'accent' => 'bg-white hover:bg-slate-100 text-terra-700 shadow-luxury',
            default => 'bg-terra-500 hover:bg-terra-600 text-white shadow-luxury',
        };

        $obj->btnSecondary = match ($theme) {
            'dark', 'pattern-dark', 'gradient', 'gradient-terra', 'accent' => 'border border-white/25 hover:border-white text-white hover:bg-white/10',
            default => 'border border-slate-300 dark:border-slate-700 hover:border-terra-400 dark:hover:border-terra-400 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 hover:text-terra-600 dark:hover:text-terra-400',
        };

        return $obj;
    }
}
