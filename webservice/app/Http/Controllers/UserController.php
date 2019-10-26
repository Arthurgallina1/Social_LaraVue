<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function login(Request $request)
    {
        
            $data = $request->all(); //retorna tudo que chega do formulario

            
            $validacao =  Validator::make($data, [ 
                'email' => 'required|string|email|max:255',
                'password' => 'required|string',
            ]);
            //metodo para verficiar erros
        if($validacao->fails()){
            return ['status' => false, "validacao" => true, "errors" => $validacao->errors()];
           
        }
        //Valida o login caso ok, retorna o token. Auth => classe de autenticação. attempt método estatico para fazer validação do usuario
        if(Auth::attempt(['email'=>$data['email'], 'password'=>$data['password']])){ //autenticação OK
                $user = auth()->user(); //auth é um helper que retorna o usuario que está logado
                $user->token = $user->createToken($user->email)->accessToken; //gera o token p user
                // $user->image = asset($user->image);
                // return $user;
                return ['status' => true, "user" => $user];
        } else {
            return ['status' => false];
        }

            return $user;
       

    }
    public function cadastro(Request $request){
        $data = $request->all(); //retorna tudo que chega do formulario
        $validacao =  Validator::make($data, [ 
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
        //metodo para verficiar erros
       if($validacao->fails()){
            return ['status' => false, "validacao" => true, "errors" => $validacao->errors()];
       }
    
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]); //Recebe o objeto de usuario criado (class feita já. Recebe os fillable.
        $user->token = $user->createToken($user->email)->accessToken; //gera o token p user
        return ['status' => true, "user" => $user];
    }

    public function user(Request $request){
        return $request->user();
    }

    public function perfil(Request $request){
        $user = $request->user();
        $data = $request->all();
    
        if(isset($data['password'])){ //senha prenchida -> testar validação completa
            $validacao =  Validator::make($data, [ 
                'name' => 'required|string|max:255',
                'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)], //ignora o email dele.
                'password' => 'required|string|min:6|confirmed',
            ]);
            //metodo para verficiar erros
           if($validacao->fails()){
                 return ['status' => false, "validacao" => true, "errors" => $validacao->errors()];
           }
           $user->password = bcrypt($data['password']);
    
        } else {
            $validacao =  Validator::make($data, [ 
                'name' => 'required|string|max:255',
                'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)], //ignora o email dele.   
            ]);
    
            if($validacao->fails()){
                return ['status' => false, "validacao" => true, "errors" => $validacao->errors()];

            }
            $user->name = $data['name'];
            $user->email = $data['email'];
        }
    
        if(isset($data['imagem'])){
            //validar image base 64
            Validator::extend('base64image', function($attribute, $value, $parameters, $validators) {
                $explode = explode(',', $value);
                $allow = ['png', 'jpg', 'svg', 'jpeg'];
                $format = str_replace([
                    'data:image/',
                    ';',
                    'base64'
                ],
                [
                    '','','',
                ],
                $explode[0]
            );
            //check file format
            if(!in_array($format, $allow)){
                return false;
            }
    
            //check base64 format
            if(!preg_match('%^[a-zA-Z0-9/+]*={0,2}$%', $explode[1])){
                return false;
            }
            return true;
            });
    
            $validacao =  Validator::make($data, [ 
                'imagem' => 'base64image',
                 
            ], ['base64image' => 'Invalid image!']); //erro gerado
    
            if($validacao->fails()){
                return ['status' => false, "validacao" => true, "errors" => $validacao->errors()];

            }
    
            $time = time();
            $diretorioPai = 'profiles';
            $diretorioImagem = $diretorioPai.DIRECTORY_SEPARATOR.'profile_id'.$user->id;
            $urlImagem = $diretorioImagem.DIRECTORY_SEPARATOR.$time.'.'.$data['type'];
    
            //decodificar base64 para arquivo
            $file = str_replace('data:image/'.$data['type'].';base64,','', $data['imagem']);
            $file = base64_decode($file);
            
            if(!file_exists($diretorioPai)){
                mkdir($diretorioPai, 0700);
            }
            if($user->image){ //deleta imagem antiga
                //chega como uma url completa pois esta sendo mutada
                $imgUser = str_replace(asset('/'),'',$user->image);
                if(file_exists($imgUser)){
                    unlink($imgUser);
                }
            }
    
            if(!file_exists($diretorioImagem)){
                mkdir($diretorioImagem, 0700);
            }
    
            file_put_contents($urlImagem, $file);
            
            $user->image = $urlImagem;
    
        }
    
        $user->save(); //salva no BD
    
        // $user->image = asset($user->image); //helper do laravel que monta url para o public (dominio do servidor + caminho da imagem), link da imagem para exibi-la.
        $user->token = $user->createToken($user->email)->accessToken; //pegar usuario (infos), crio um token pra ele e retorno os dados e os tokens
        return ["status" => true, "user" => $user];
    }

    public function seguir (Request $request)
    {
        $user = $request->user();
        $amigo = User::find($request->id); //amigo que será adicionado
        if($amigo && $user->id != $amigo->id){
            $user->amigos()->toggle($amigo->id);
            return ['status' => true, 'amigos' => $user->amigos, 'seguidores' => $amigo->seguidores]; //retorna todos os amigos do usuario logado e do dono da pagina
        } else {
            return ['status' => false, 'erro' => 'Erro ao seguir'];
        }
       
    }

    public function listarAmigos(Request $request)
    {
        $user = $request->user();

        if($user){
            return ['status' => true, 'amigos' => $user->amigos, 'seguidores' => $user->seguidores];
        } else {
            return ['status' => false, 'erro' => 'Erro ao listar amigos'];
        }


    }

    public function listarAmigosDono(Request $request, $id)
    {
        $user = $request->user();
        $donoPagina = User::find($id);
        
        if($donoPagina){
            // $user->amigos()->toggle($donoPagina->id);
            return ['status' => true, 'amigos' => $donoPagina->amigos, "amigoslogado" => $user->amigos, 'seguidores' => $donoPagina->seguidores]; // $donoPagina->amigos listas os amigos do dona da pagina
        } else {
            return ['status' => false, 'erro' => 'Erro ao listar amigos'];
        }

    }

}
