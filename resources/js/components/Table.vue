<template>
    <div>
        <table class="table table-hover">
            <thead>
                <tr >
                    <th v-for="t, key in titulos" :key="key" scope="row">{{ t }}</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="m in dados" :key="m.id">
                    <th scope="row">{{ m.id }}</th>
                    <td>{{ m.nome }}</td>
                    <td><img :src="'/storage/'+ m.imagem" alt="" width="30px"></td>
                </tr>
              
            </tbody>
        </table>
    </div>
</template>

<script>
export default {
    props: ['dados', 'titulos']
}
</script>