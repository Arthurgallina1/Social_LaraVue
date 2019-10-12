<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Conteudo;
use App\User;


class ConteudoController extends Controller
{   
     // LISTAR TODOS CONTEUDOS
     public function list(Request $request)
     {
        //  $user = $request->user();
        $conteudos = Conteudo::with('user')->orderBy('data', 'DESC')->paginate(5);
        $user = $request->user();

        foreach ($conteudos as $key => $conteudo){
            $conteudo->total_curtidas = $conteudo->curtidas()->count();//funciona como um objeto, será refletido no $conteudos, teremos a prop total_curtidas.
            $conteudo->comentarios = $conteudo->comments()->with('user')->get(); //quando via colocar outro método precisa colocar os paranteses
            $curtiu = $user->curtidas->find($conteudo->id); //se tiver algo é pq curtiu, vazio n curtiui
           
            if($curtiu){
                $conteudo->curtiu_conteudo = true;
            } else {
                $conteudo->curtiu_conteudo = false;
            }
        }
        return ['status' => true, 'conteudo' => $conteudos];

     }
    // CRIAÇÃO DE CONTEUDOS
    public function publish(Request $request)
    {
        $user = $request->user();
        $data = $request->all();

        // validação
        // $validacao =  Validator::make($data, [ 
        //     'titulo' => 'required',
        //     'texto' => 'required'
        // ]);
        //metodo para verficiar erros
        //  if($validacao->fails()){
        //     return ['status' => false, "validacao" => true, "errors" => $validacao->errors()];

        // }
        $conteudo = new Conteudo; //instanciando objeto de conteudo 

        $conteudo->titulo = $data['titulo'];
        $conteudo->texto = $data['texto'];
        $conteudo->imagem = $data['imagem'] ? $data['imagem'] : '#' ;
        $conteudo->link = $data['link'] ? $data['link'] : '#' ; //tratamento se nao chegar nada
        $conteudo->data = date('Y-m-d H:i:s');
        //invés de create usar um save: atribui os conteudos pro usuario 

        $user->conteudos()->save($conteudo);

        $conteudos = Conteudo::with('user')->orderBy('data', 'DESC')->paginate(5);

        return ['status' => true, 'conteudo' => $conteudos];
        // return ['status' => true, 'conteudos' => $user->conteudos]; //lista de conteudos do determinado usuario
    }

    public function curtida($id, Request $request)
    {
        $user = $request->user();

        // ADICIONAR CURTIDAS
        $conteudo = Conteudo::find($id);
        //validação pobre
        if($conteudo){
            $user->curtidas()->toggle($conteudo->id); //Esse usuariu curtiu esse conteudo.
            // return $conteudo->curtidas; //Listar curtidas que o conteudo tem
            // return $conteudo->curtidas->count();
            return ['status' => true, 'curtidas' => $conteudo->curtidas()->count(), 'lista' => $this->list($request)]; //Listar numero de curtidas qye o conteudo tem //chama o método lista e coloca o return dele dentro de 'lista'
        } else {
            return ['status' => false, 'erro' => 'It doesn\'t exist'];
        }
       
    }

    public function comentar($id, Request $request)
    {
        $user = $request->user();

        // ADICIONAR CURTIDAS
        $conteudo = Conteudo::find($id); //busca conteudo
        if($conteudo){
            // cria comentario 
            $user->comments()->create([
                'conteudo_id' => $conteudo->id,
                'texto' => $request->texto,
                'data' => date('Y-m-d H:i:s')
            ]); // Conteudo que o comentario seria criado   'conteudo_id', 'texto', 'data')
            return ['status' => true, 'lista' => $this->list($request)]; //Listar numero de curtidas qye o conteudo tem //chama o método lista e coloca o return dele dentro de 'lista'
        } else {
            return ['status' => false, 'erro' => 'It doesn\'t exist'];
        }
       
    }

    public function pagina($id, Request $request)
    {
       //  $user = $request->user();
       $donoPerfil = User::find($id);
       if($donoPerfil){
                $conteudos = $donoPerfil->conteudos()->with('user')->orderBy('data', 'DESC')->paginate(5);
                $user = $request->user();
                $conteudos->id = $id;
                foreach ($conteudos as $key => $conteudo){
                    $conteudo->total_curtidas = $conteudo->curtidas()->count();//funciona como um objeto, será refletido no $conteudos, teremos a prop total_curtidas.
                    $conteudo->comentarios = $conteudo->comments()->with('user')->get(); //quando via colocar outro método precisa colocar os paranteses
                    $curtiu = $user->curtidas->find($conteudo->id); //se tiver algo é pq curtiu, vazio n curtiui
                
                    if($curtiu){
                        $conteudo->curtiu_conteudo = true;
                    } else {
                        $conteudo->curtiu_conteudo = false;
                    }
                }
                return ['status' => true, 'conteudo' => $conteudos, 'donoPerfil' => $donoPerfil];
       } else {
           return ['status' => false, 'error' => 'User doesn\'t exist!'];
       }
      

    }

    
}
