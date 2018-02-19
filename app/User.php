<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    public function posts()
    {
        return $this->hasMany(Post::class);
    }
    public function comments()
    {
        return $this->hasMany(Post::class);
    }

    public static function add($fields)
    {
        $user = new static;
        $user->fill($fields);
        $user->password = bcrypt($fields['password']);
        $user->save();

        return $user;

    }
    public function edit($fields)
    {

        $user->fill($fields);
        $user->password = bcrypt($fields['password']);
        $user->save();

        return $user;

    }
    public  function remowe($fields)
    {
        Storage::delete('uploads/'.$this->image);
        $this->delete();
    }

    public function uploadAvatar($image)
    {
        if($image==null) {return;}

        Storage::delete('uploads/'.$this->image);   //удаляем предыдущую картинку
        $filename = str_random(10).'.'.$image->extention();// генерим название файла
        $image->saveAs('uploads', $filename);
        $this->image = $filename;
        $this->save();
    }
    public function getImage($image)
    {
        if($this->image == null)
        {
            return '/img/no-user-image.png';
        }
        return '/uploads/'.$this->image;
    }

    public function makeAdmin()
    {
        $this->is_admin = 1;
    }
    public function makeNormal()
    {
        $this->is_admin = 0;
    }

    public function toggleAdmin($value)
    {
        if ($value == null)
        {
            return $this->makeNormal();
        }
        return $this->makeAdmin();
        
    }
    public function ban()
    {
        $this->status = 1;
        $this->save();
    }
    public function unban()
    {
        $this->status = 0;
        $this->save();
    }

    public function toggleBan($value)
    {
        if ($value == null)
        {
            return $this->unban();
        }
        return $this->ban();

    }

}
