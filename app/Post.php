<?php

namespace App;
use App\Category;
use App\Tag;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
class Post extends Model
{
   
   use SoftDeletes;
   
   protected $dates = [
      'publish_at'
    ];
    protected $fillable = [
        'title', 'description', 'content','image','category_id','publish_At','user_id'
    ];

    public function deleteImage(){
    	Storage::delete($post->image);
    }
    public function category(){
    	return $this->belongsTo(Category::class);
    }
    public function tags()
    {
      return $this->belongsToMany(Tag::class);
    }

    /**
     * check if post has tag
     * 
     * @return bool
     */
    public function hasTag($tagId)
    {
      return in_array($tagId, $this->tags->pluck('id')->toArray());
    }
    
    public function user(){
        return $this->belongsTo(User::class);
    }

    public function scopePublished($query)
    {
      return $query->where('publish_at', '<=', now());
    }

    public function scopeSearched($query)
    {
      $search = request()->query('search');

      if (!$search) {
        return $query->published();
      }

      return $query->published()->where('title', 'LIKE', "%{$search}%");
    }

}
