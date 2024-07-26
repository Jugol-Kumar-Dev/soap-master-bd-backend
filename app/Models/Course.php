<?php

namespace App\Models;

use Carbon\Carbon;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @method static paginate(int $int)
 * @method static findOrFail($id)
 * @method static count()
 */
class Course extends Model
{
    use HasFactory, HasSlug;

    protected $guarded = ['id'];
    protected $fillable = [
        'name',
        'slug',
        'description',
        'content',
        'cover',
        'video',
        'files',
        'price',
        'user_id',
        'category_id',
        'active_on',
        'status',
    ];

    protected $casts = [
        'active_on' => 'datetime:d/m/Y',
    ];

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function category()
    {
        return $this->belongsTo('App\Models\Category');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }


    public function transactions()
    {
        return $this->hasMany('App\Models\Transaction');
    }

    public function orders()
    {
        return $this->hasMany('App\Models\Order');
    }

    protected function cover(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? config('app.url').Storage::url($value) : '/images/avatar.png',
        );
    }


    public function mocktests(){
        return $this->belongsToMany(Mocktest::class,'course_mocktest')->withPivot('status')->withTimestamps();
    }
    public function lessons(){
        return $this->hasMany(Lesson::class);
    }

    public function chapters()
    {
        return $this->hasMany(Chapter::class);
    }


}
