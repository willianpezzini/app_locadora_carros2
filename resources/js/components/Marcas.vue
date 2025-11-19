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
                                    placeholder="Id">

                            </input-container-component>
                        </div>
                        <div class="col mb-3">
                            <input-container-component titulo="Nome da Marca" id="InputNome" id-help="nomeHelp"
                                texto-ajuda="Opcional. Informe o Nome da marca">
                                <input type="text" class="form-control" id="inputNome" aria-describedby="nomeHelp"
                                    placeholder="Nome da Marca">

                            </input-container-component>
                        </div>

                    </template>

                    <template v-slot:footer>
                        <button type="submit" class="btn btn-primary btn-sm">Pesquisar</button>
                    </template>
                </card-component>

                <!-- Fim card de busca -->

                <!-- Inicio card de Listagem de marcas -->

                <card-component title="Relação de marcas">
                    <template v-slot:body>
                        <table-component></table-component>
                    </template>
                    <template v-slot:footer>
                        <button type="submit" class="btn btn-primary btn-sm" data-toggle="modal"
                            data-target="#modalMarca">Adicionar</button>
                    </template>
                </card-component>
                <!-- Fim card de Listagem de marcas -->
            </div>
        </div>
        <modal-component id="modalMarca" titulo="Adicionar Marca">

            <template v-slot:alertas>
                <alert-component tipo="success"></alert-component>
                <alert-component tipo="danger"></alert-component>
            </template>
            <template v-slot:conteudo>
                <div class="form-group">
                    <input-container-component titulo="Nome da Marca" id="novoNome" id-help="novoNomeHelp"
                        texto-ajuda="Opcional. Informe o Nome da marca">
                        <input type="text" class="form-control" id="novoNome" aria-describedby="novoNomeHelp"
                            placeholder="Nome da Marca" v-model="nomeMarca">

                    </input-container-component>
                    {{ nomeMarca }}
                </div>

                <div class="form-group">
                    <input-container-component titulo="Imagem" id="novaImagem" id-help="novaImagemHelp"
                        texto-ajuda="Selecione uma imagem no formato PNG.">
                        <input type="file" class="form-control-file" id="novaImagem" aria-describedby="novaImagemHelp"
                            placeholder="Selecione uma imagem" @change="carregarImagem($event)">

                    </input-container-component>
                    {{ arquivoImagem }}
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

            console.log(token)
            return token
        }
    },

    data() {
        return {
            urlBase: 'http://localhost:8000/api/v1/marca',
            nomeMarca: '',
            arquivoImagem: []
        }
    },
    methods: {
        carregarImagem(e) {
            this.arquivoImagem = e.target.files
        },
        salvar() {
            console.log(this.nomeMarca, this.arquivoImagem)

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
                    console.log(response)
                })

                .catch(error => {
                    console.log(error)
                })
        }
    }
}
</script>