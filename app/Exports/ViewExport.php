<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;

/**
 * Pengganti Excel::create(...)->sheet(...)->loadView(...) milik maatwebsite/excel v2:
 * satu sheet berjudul $judulSheet yang isinya di-render dari sebuah view Blade.
 */
class ViewExport implements FromView, WithTitle
{
    public function __construct(
        protected string $namaView,
        protected array $data,
        protected string $judulSheet
    ) {
    }

    public function view(): View
    {
        return view($this->namaView, $this->data);
    }

    public function title(): string
    {
        // Nama sheet Excel maksimal 31 karakter dan tidak boleh memuat : \ / ? * [ ]
        // (v2 memotongnya otomatis).
        return mb_substr(str_replace([':', '\\', '/', '?', '*', '[', ']'], '', $this->judulSheet), 0, 31);
    }
}
