<template>
    <div :class="estilo" role="alert" v-if="detalhes.data && (detalhes.data.message || detalhes.data.errors)">
        {{ titulo }}
        <hr>
        
        <span v-if="detalhes.data.message">{{ detalhes.data.message }}</span>

        <ul v-if="detalhes.data.errors">
            <li v-for="(e, key) in detalhes.data.errors" :key="key">{{ e[0] }}</li>
        </ul>
    </div>
</template>

<script>
    export default {
        props: ['tipo', 'titulo', 'detalhes'],
        computed: {
            estilo() { return 'alert alert-'+this.tipo }
        }
    }
</script>