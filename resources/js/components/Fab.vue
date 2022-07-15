<template>
    <div class="fab__wrapper">
        <button class="fab__button"
                @click="handleExport()">
            Export
        </button>
    </div>
</template>
<script>


export default {

    mounted() {


    },

    methods: {



        handleExport() {
            axios.post("/home/dump")
                .then((response) => {
                    const url = window.URL.createObjectURL(new Blob([response.data]));
                    const link = document.createElement('a');
                    link.href = url;
                    link.setAttribute('download', 'dump.sql'); //or any other extension
                    document.body.appendChild(link);
                    link.click();
                })
                .catch(err => {
                    console.log(err.response);
                });
        },


        handleErrors(err) {
            if (err.response && err.response.status === 422) {
                // We have a validation error
                const errorBag = err.response.data.errors;
                if (errorBag.title) {
                    // this.errorMessage = errorBag.title[0];
                } else if (errorBag.description) {
                    // this.errorMessage = errorBag.description[0];
                } else {
                    // this.errorMessage = err.response.message;
                }
            } else {
                // We have bigger problems
                console.log(err.response);
            }
        }
    }
}

</script>
