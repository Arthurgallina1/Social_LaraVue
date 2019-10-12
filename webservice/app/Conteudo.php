<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Conteudo extends Model
{
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'titulo', 'texto', 'imagem', 'link', 'data'
    ];

    public function comments()
    {
        return $this->hasMany('App\Comentario');
    }
    
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function curtidas()
    {   //Modelo, tabela, fk da relação, fk que esta juntando 
        return $this->belongsToMany('App\User', 'curtidas', 'conteudo_id', 'user_id');
    }

    public function getDataAttribute($value){
        $date = date('H:i d/m/Y', strtotime($value)); 
        return str_replace(':', 'h', $date);
    }
}
