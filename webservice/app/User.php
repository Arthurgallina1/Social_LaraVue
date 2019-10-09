<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use  HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'image'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function comments()
    {
        return $this->hasMany('App\Comentario'); //nome do modelo. tera N comentarios e nos comentarios tera um usuario
    }

    public function conteudos()
    {
        return $this->hasMany('App\Conteudo');
    }

    // The third argument is the foreign key name of the model on which you are defining the relationship, while the fourth argument is the 
    // foreign key name of the model that you are joining to:
    public function curtidas()
    {   //Modelo, tabela, fk do modelo que esta definindo a relação, fk que esta dando o join 
        return $this->belongsToMany('App\Conteudo', 'curtidas', 'user_id', 'conteudo_id');
    }

    public function amigos()
    {   //Modelo, tabela, fk do modelo que esta definindo a relação, fk que esta dando o join 
        return $this->belongsToMany('App\User', 'amigos', 'user_id', 'amigo_id');
    }
    //mutação de atributo
    public function getImageAttribute($value){
        return asset($value);
    }

}
