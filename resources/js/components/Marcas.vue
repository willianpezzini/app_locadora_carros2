<template>
    <div class="container">
        <div class="row justify-content-center ">
            <div class="col-md-8">
                <!-- Inicio card de busca -->
                <card-component title="Busca de Marcas">
                    <template v-slot:body>
                        <div class="col mb-3">
                            <input-container-component titulo="ID" id="InputId" id-help="idHelp"
                                texto-ajuda="Opcional. Informe o Id da marca">
                                <input type="number" class="form-control" id="inputId" aria-describedby="idHelp"
                                    placeholder="Id" v-model="busca.id">

                            </input-container-component>
                        </div>
                        <div class="col mb-3">
                            <input-container-component titulo="Nome da Marca" id="InputNome" id-help="nomeHelp"
                                texto-ajuda="Opcional. Informe o Nome da marca">
                                <input type="text" class="form-control" id="inputNome" aria-describedby="nomeHelp"
                                    placeholder="Nome da Marca" v-model="busca.nome">

                            </input-container-component>
                        </div>

                    </template>

                    <template v-slot:footer>
                        <button type="submit" class="btn btn-primary btn-sm" @click="pesquisar()">Pesquisar</button>
                    </template>
                </card-component>

                <!-- Fim card de busca -->

                <!-- Inicio card de Listagem de marcas -->

                <card-component title="Relação de marcas">
                    <template v-slot:body>
                        <table-component 
                            :dados="marcas.data" 
                            :titulos="titulosTabela"
                            :carregando="carregando">
                        </table-component>
                    </template>
                    <template v-slot:footer>
                        <div class="row d-flex align-items-center">
                            <div class="col-10">
                                <paginate-component>
                                    <li v-for="link, key in marcas.links" :key="key"
                                        :class="link.active ? 'page-item active' : 'page-item'"
                                        @click="paginacao(link)">
                                        <a class="page-link" v-html="link.label" ></a>
                                    </li>

                                </paginate-component>
                            </div>

                            <div class="col">
                                <button type="submit" class="btn btn-primary btn-sm " data-toggle="modal"
                                    data-target="#modalMarca">
                                    Adicionar
                                </button>
                            </div>
                        </div>

                    </template>
                </card-component>
                <!-- Fim card de Listagem de marcas -->
            </div>
        </div>
        <modal-component id="modalMarca" titulo="Adicionar Marca">

            <template v-slot:alertas>
                <alert-component tipo="success" :detalhes="transacaoDetalhes" titulo="Cadastro realizado com sucesso"
                    v-if="transacaoStatus == 'add'">
                </alert-component>
                <alert-component tipo="danger" :detalhes="transacaoDetalhes" titulo="Erro ao tentar cadastrar a marca"
                    v-if="transacaoStatus == 'error'"></alert-component>
            </template>
            <template v-slot:conteudo>
                <div class="form-group">
                    <input-container-component titulo="Nome da Marca" id="novoNome" id-help="novoNomeHelp"
                        texto-ajuda="Opcional. Informe o Nome da marca">
                        <input type="text" class="form-control" id="novoNome" aria-describedby="novoNomeHelp"
                            placeholder="Nome da Marca" v-model="nomeMarca">

                    </input-container-component>
                </div>

                <div class="form-group">
                    <input-container-component titulo="Imagem" id="novaImagem" id-help="novaImagemHelp"
                        texto-ajuda="Selecione uma imagem no formato PNG.">
                        <input type="file" class="form-control-file" id="novaImagem" aria-describedby="novaImagemHelp"
                            placeholder="Selecione uma imagem" @change="carregarImagem($event)">

                    </input-container-component>
                </div>
            </template>

            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary" @click="salvar">Salvar</button>
            </template>
        </modal-component>
    </div>
</template>

<script>

export default {
    computed: {
        token() {
            let token = document.cookie.split(';').find(indice => {
                return indice.startsWith('token=')
            })

            token = token.split('=')[1]
            token = 'Bearer ' + token

            // console.log(token)
            return token
        }
    },

    data() {
        return {
            urlBase: 'http://localhost:8000/api/v1/marca',
            urlPaginacao: '',
            urlFiltro: '',
            nomeMarca: '',
            arquivoImagem: [],
            transacaoStatus: '',
            transacaoDetalhes: {},
            carregando: false,
            marcas: { data: [] },
            busca: { id: '', nome: '' },

            titulosTabela: {
                id: { titulo: 'ID', tipo: 'text' },
                nome: { titulo: 'Nome da Marca', tipo: 'text' },
                imagem: { titulo: 'Logo', tipo: 'image' },
                created_at: { titulo: 'Data Criação', tipo: 'data' },
            }
        }
    },
    methods: {
        carregarImagem(e) {
            this.arquivoImagem = e.target.files
        },
        paginacao(link) {
            if (link.url) {
                this.urlPaginacao = link.url.split('?')[1]
                this.carregarLista()
            }
        },
        pesquisar() {
            // console.log(this.busca)
            let filtro = ''

            for(let chave in this.busca) {
                if (this.busca[chave]) {
                    if (filtro != '') {
                    filtro += ";"
                }

                filtro += chave + ':like:%' + this.busca[chave] + '%';
                }
                
            }
            if (filtro != '') {
                this.urlPaginacao = 'page=1';
                this.urlFiltro = 'filtro=' + filtro;
            } else {
                this.urlFiltro = ''
            }

            
            this.carregarLista()
            console.log('urlFiltro: ',this.urlFiltro)
        },
        salvar() {
            // console.log(this.nomeMarca, this.arquivoImagem)

            let formData = new FormData();
            formData.append('nome', this.nomeMarca)
            formData.append('imagem', this.arquivoImagem[0])

            let config = {
                headers: {
                    'Content-Type': 'multipart/form-data',
                    'Accept': 'application/json',
                    'Authorization': this.token
                }
            }

            axios.post(this.urlBase, formData, config)
                .then(response => {
                    this.transacaoStatus = 'add'
                    this.transacaoDetalhes = {
                        data: {
                            message: 'ID de Registro: ' + response.data.id
                        }
                    };

                    this.nomeMarca = '';
                    this.arquivoImagem = null;
                    document.getElementById('novaImagem').value = '';
                })

                .catch(errors => {

                    this.transacaoStatus = 'error'
                    this.transacaoDetalhes = {
                        data: errors.response.data,
                        status: errors.response.status,
                        statusText: errors.response.statusText
                    };
                    console.log('Erros capturados', this.transacaoDetalhes);
                });
        },
        carregarLista() {
            let config = {
                headers: {
                    'Accept': 'application/json',
                    'Authorization': this.token
                }
            }
            this.carregando = true

            let url = this.urlBase + '?';

            if (this.urlPaginacao) {
                url += this.urlPaginacao 
            }

            if (this.urlFiltro) {
                if (this.urlPaginacao) {
                    url += '&' + this.urlFiltro;
                } else {
                    url += this.urlFiltro;
                }
            }

            if (url.endsWith('?')) {
                url = url.slice(0, -1)
            }

            console.log('URL Final: ', url)

            this.marcas = { data: [] };
            
            axios.get(url, config)
                .then(response => {
                    this.marcas = response.data;
                    // console.log(this.marcas)
                })
                .catch(errors => {
                    console.log(errors)
                })
                .finally(() => {
                    this.carregando = false
                })
        }
    },
    mounted() {
        this.carregarLista()
    }
}
</script>