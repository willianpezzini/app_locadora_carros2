<template>
    <div class="form-group">
        <!-- Usando o V-bind com a abreviação : antes do for -->
        <label :for="id" class="form-label">{{ titulo }}</label>
        <!-- A tag slot joga o conteudo que está interno no componente para ser imprimido na tela -->
        <slot></slot>
        <div :id="idHelp" class="form-text text-muted">{{ textoAjuda }}</div>
    </div>
</template>

<script>
    export default {
        props:['id', 'titulo', 'idHelp', 'textoAjuda']
    }
</script>