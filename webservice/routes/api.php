<?php

use Illuminate\Http\Request;
use App\User;
use App\Conteudo;
use App\Comentario;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('/cadastro', "UserController@cadastro");
Route::post('/login', "UserController@login");
Route::middleware('auth:api')->put('/perfil', "UserController@usuario");
Route::middleware('auth:api')->put('/perfil', "UserController@perfil");
Route::middleware('auth:api')->post('/conteudo/add', "ConteudoController@publish");
Route::middleware('auth:api')->get('/conteudo/list', "ConteudoController@list");
Route::middleware('auth:api')->put('/conteudo/curtida/{id}', "ConteudoController@curtida");
Route::middleware('auth:api')->put('/conteudo/curtidaperfil/{id}', "ConteudoController@curtidapagina");
Route::middleware('auth:api')->post('/conteudo/comentario/{id}', "ConteudoController@comentar");
Route::middleware('auth:api')->post('/conteudo/comentariopagina/{id}', "ConteudoController@comentarpagina");
Route::middleware('auth:api')->get('/conteudo/pagina/list/{id}', "ConteudoController@pagina");
Route::middleware('auth:api')->post('/user/follow', "UserController@seguir");
Route::middleware('auth:api')->get('/user/amigos', "UserController@listarAmigos");
Route::middleware('auth:api')->get('/user/amigos/{id}', "UserController@listarAmigosDono");

Route::get('/testes', function(){
    
    // APAGAR TODOS CONTEUDOS
    // $conteudos = Conteudo::all();
    // foreach($conteudos as $key => $value) {
    //     echo $value;
    // }
    // $user = User::find(1); //simulação que o usuario 1 está logado
    
    // CRIAÇÃO DE CONTEUDOS
    // Método que relaciona usuario com conteudo. 
    // $user->conteudos()->create([
    //     'titulo' =>'Conteudo 3',
    //     'texto' => 'Texto 3 do post',
    //     'imagem' => 'url img 3',
    //     'link' => 'url do post 3',
    //     'data' => '2019-12-02' //date('Y-m-d')
    // ]); //criar um novo conteudo, passand os fillables do Conteudo.php (  'titulo', 'texto', 'imagem', 'link', 'data'    )
    // return $user->conteudos; //lista todos conteudos desse usuario. 

    // ADICIONAR AMIGOS
    // $user2 = User::find(2); //amigo que será adicionado
    // //metodo que faz relacionamentos de amigos entre usuarios
    //                         //attach ira enviar o id de um para o outro. Um pivô relaciona ID do user e do amigo.
    //                         // $user->amigos()->attach($user2->id);
    //                         //remover a ligação
    //                         // $user->amigos()->detach($user2->id);
    // //Porém invés de usar attach e detach, posso usar o toggle! Então consegue adicionar e remover se ficar sobreescrevendo e etc...
    // $user->amigos()->toggle($user2->id);

    // return $user->amigos; //lista amigos do usuario 1

    // ADICIONAR CURTIDAS
    // $conteudo = Conteudo::find(1); 
    // $user->curtidas()->toggle($conteudo->id); //Esse usuariu curtiu esse conteudo.
    // // return $conteudo->curtidas; //Listar curtidas que o conteudo tem
    // return $conteudo->curtidas()->count(); //Listar numero de curtidas qye o conteudo tem

    // ADICIONAR COMENTARIOS
    // $user = User::find(1);
    // $conteudo = Conteudo::find(12);
    // $user->comments()->create([
    //     'conteudo_id' => $conteudo->id,
    //     'texto' => 'Post top',
    //     'data' => date('Y-m-d')
    // ]); // Conteudo que o comentario seria criado   'conteudo_id', 'texto', 'data')
    // $user->comments()->create([
    //     'conteudo_id' => $conteudo->id,
    //     'texto' => 'segundo comentario  do post',
    //     'data' => date('Y-m-d')
    // ]); // Conteudo que o comentario seria criado   'conteudo_id', 'texto', 'data')

    // return $conteudo->comments; //listar todos comentarios desse conteudo
    $user = User::find(8);
    $amigos = $user->amigos()->pluck('amigo_id');
    $amigos->push($user->id); //collections do laravel, para dar push no usuario logado além dos amigos que ele segue.
    $conteudos = Conteudo::whereIn('user_id', $amigos)->with('user')->orderBy('data', 'DESC')->paginate(5);
    

    dd($conteudos);

    
    

});

