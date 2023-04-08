<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\ExamSectionOption;

class ExamSection extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function exam()
    {
        return $this->belongsTo(Exam::class,'exam_id','id');
    }

    public function options()
    {
        return $this->hasMany(ExamSectionOption::class,'exam_section_id', 'id');
    }

    public function optionNames()
    {
        return $this->options()->pluck('option');
    }
}
