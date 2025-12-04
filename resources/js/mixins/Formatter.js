

export const Formatter = {
    methods: {
        // Data e Hora
        formatarDataHora(dataString) {
            if(!dataString) return '';

            let data = new Date(dataString);
            return data.toLocaleString('pt-BR', {timeZone: 'America/Sao_Paulo'});
        },

        formatarData(dataString) {
            if(!dataString) return '';

            let data = new Date(dataString);
            return data.toLocaleDateString('pt-BR', {timeZone: 'America/Sao_Paulo'});
        }
    }
}