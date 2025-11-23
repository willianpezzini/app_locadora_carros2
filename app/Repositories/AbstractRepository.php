<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

abstract class AbstractRepository {

    protected $model;

    public function __construct(Model $model) {
        $this->model = $model;
    }

    public function selectAtributosRegistrosRelacionados($atributos) {
        // Ex: 'modelos:id,nome'
        $this->model = $this->model->with($atributos);
    }

    public function filtro($filtros) {
        // Ex: nome:like:%fiat%;outro_campo:>:10
        $filtros = explode(';', $filtros);
        
        foreach($filtros as $key => $condicao) {
            $c = explode(':', $condicao);
            // $c[0] = coluna, $c[1] = operador, $c[2] = valor
            $this->model = $this->model->where($c[0], $c[1], $c[2]);
        }
    }

    public function selectAtributos($atributos) {
        // O select do Eloquent espera uma string separada por virgula
        $this->model = $this->model->selectRaw($atributos);
    }

    public function getResultado() {
        return $this->model->get();
    }

    public function getResultadoPaginado($numeroRegistrosPorPagina) {
        return $this->model->paginate($numeroRegistrosPorPagina);
    }
}