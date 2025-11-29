<template>
    <div>
        <div v-if="carregando" class="text-center p-5">
            <div class="spinner-border text-primary" role="status">
                <span class="sr-only">Carregando...</span>
            </div>
            <p class="mt-2 text-muted">Carregando dados...</p>
        </div>
        <table class="table table-hover" v-if="dados && dados.length > 0">
            <thead>
                <tr >
                    <th v-for="(titulo, key) in titulos" :key="key" scope="col" >
                        {{ titulo.titulo}}
                    </th>
                    <th v-if="$slots.acoes">Ações</th>
                    <th v-if="visualizar.visivel || editar || excluir"></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="obj, index in dados" :key="obj.id || index">
                    <td v-for="(titulo, chave) in titulos" :key="chave" style="padding: 10px; height: 60px;">
                        <span v-if="chave === 'imagem'">
                            <img v-if="obj[chave]" :src="'/storage/'+obj[chave]" alt="Imagem do registro" width="40px" height="40px" class="img-thumbnail">
                            <span v-else class="text-muted small">Sem imagem</span>
                        </span>
                        <span v-else-if="chave === 'created_at' || chave === 'updated_at'">
                            {{ formatarData(obj[chave]) }}
                        </span>
                        <span v-else>
                            {{ obj[chave] }}
                        </span>
                    </td>
                    <td v-if="$slots.acoes">
                        <slot name="acoes" :item="obj"></slot>
                    </td>
                    <td v-if="visualizar.visivel || editar || excluir">
                        <button v-if="visualizar.visivel" class="btn btn-outline-success btn-sm" :data-toggle="visualizar.dataToggle" :data-target="visualizar.dataTarget" @click="setStore(obj)">Visualizar</button>
                        <button v-if="editar" class="btn btn-outline-primary btn-sm">Editar</button>
                        <button v-if="excluir" class="btn btn-outline-danger btn-sm">Excluir</button>
                    </td>
                </tr>
            </tbody>
        </table>
        <div v-else class="alert alert-info">
            Nenhum registro encontrado.
        </div>
    </div>
</template>

<script>
export default {
    props: {
        dados: {
            type: Array,
            require: true,
            default: () => []
        },
        titulos: {
            type: Object,
            required: true
        },
        carregando: {
            type: Boolean,
            require: false,
            default: false
        },
        visualizar: {
            type: Boolean,
            require: true,
            default: true
        },
        editar: {
            type: Boolean,
            require: true,
            default: true
        },
        excluir: {
            type: Boolean,
            require: true,
            default: true
        }
    },
    methods: {
        formatarData(dataString) {
            if (!dataString) return '';

            const data = new Date(dataString);

            return data.toLocaleDateString('pt-BR', { timeZone: 'UTC'});
        },
        setStore(obj) {
            this.$store.state.item = obj
            console.log(obj)
        }
    }
}
</script>