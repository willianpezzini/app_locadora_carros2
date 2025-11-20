<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MarcaController extends Controller
{
    public function __construct(protected Marca $marca)
    {
        // Com PHP 8, a atribuição é automática. Não precisa de linha aqui.
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $marcas = $this->marca->all();
        return response()->json($marcas, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // 1. Validação
        $request->validate([
            'nome' => 'required|unique:marcas,nome|min:3',
            'imagem' => 'required|file|mimes:png,jpeg,jpg'
        ], [
            'required' => 'O campo :attribute é obrigatório.',
            'unique' => 'O nome da marca já existe',
            'min' => 'O campo :attribute deve ter no mínimo 3 caracteres.',
            'mimes' => 'O arquivo deve ser uma imagem do tipo PNG, JPEG ou JPG.'
        ]);

        // 2. Upload da Imagem (Salva no disco 'public')
        $imagem = $request->file('imagem');
        $imagem_urn = $imagem->store('imagens', 'public');

        // 3. Criação do Registro no Banco
        // Usamos o caminho da imagem ($imagem_urn), não o arquivo bruto.
        $marca = $this->marca->create([
            'nome' => $request->nome,
            'imagem' => $imagem_urn
        ]);

        return response()->json($marca, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $marca = $this->marca->find($id);
        if ($marca === null) {
            return response()->json(['erro' => 'Recurso pesquisado não existe'], 404);
        }
        return response()->json($marca, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Marca  $marca
     * @return \Illuminate\Http\Response
     */
    public function edit(Marca $marca)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $marca = $this->marca->find($id);

        if ($marca === null) {
            return response()->json(['erro' => 'Impossível realizar a atualização. O recurso solicitado não existe'], 404);
        }

        if ($request->method() === 'PATCH') {
            // Validação dinâmica para PATCH
            $regrasDinamicas = array();

            // Percorre todas as regras definidas no Model
            foreach ($marca->rules() as $input => $regra) {
                // Só aplica a regra se o input estiver presente na requisição
                if (array_key_exists($input, $request->all())) {
                    $regrasDinamicas[$input] = $regra;
                }
            }

            $request->validate($regrasDinamicas, $marca->feedback());
        } else {
            // Validação completa para PUT
            $request->validate($marca->rules(), $marca->feedback());
        }

        // Se enviou uma nova imagem, remove a antiga se necessário
        if ($request->file('imagem')) {
            Storage::disk('public')->delete($marca->imagem);
            $imagem = $request->file('imagem');
            $imagem_urn = $imagem->store('imagens', 'public');

            // Atualiza o objeto $marca com o novo caminho antes de salvar
            $marca->imagem = $imagem_urn;
        }

        // Preenche os outros dados (nome, etc)
        $marca->fill($request->except('imagem')); // preenche tudo exceto a imagem que já tratamos
        $marca->save();

        return response()->json($marca, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $marca = $this->marca->find($id);

        if ($marca === null) {
            return response()->json(['erro' => 'Impossível realizar a exclusão. O recurso solicitado não existe'], 404);
        }

        // Remove o arquivo de imagem antigo do disco
        Storage::disk('public')->delete($marca->imagem);

        $marca->delete();
        return response()->json(['msg' => 'A marca foi removida com sucesso!'], 200);
    }
}
