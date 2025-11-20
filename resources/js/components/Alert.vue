<template>
    <div :class="estilo" role="alert">
        {{ titulo }}
        <hr>

        <div v-if="detalhes.data">
            <span v-if="detalhes.data.message && !detalhes.data.errors">
                {{ detalhes.data.message }}
            </span>

            <ul v-else-if="detalhes.data.errors">
                <li v-for="(erro, key) in detalhes.data.errors" :key="key">
                    {{ erro[0] }}
                </li>
            </ul>

            <span v-else>
                Erro desconhecido!
            </span>
        </div>

    </div>

</template>

<script>
export default {
    props: ['tipo', 'titulo', 'detalhes'],
    computed: {
        estilo() {
            return 'alert alert-' + this.tipo
        }
    }
}
</script>