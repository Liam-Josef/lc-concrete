<?php
// app/Models/Invoice.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'student_id','lesson_id','number','date','subtotal','tax','total','paid'
    ];
    protected $dates = ['date'];
    protected $casts = [
        'date' => 'date',
        'paid' => 'boolean',
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function student() { return $this->belongsTo(Student::class); }
    public function lesson()  { return $this->belongsTo(Lesson::class); }
    public function payments(){ return $this->hasMany(Payment::class); }


}
