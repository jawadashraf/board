<template>
    <form
        class="form__card"
        @submit.prevent="handleAddNewColumn"
    >
        <div class="form__body">
            <input
                type="text"
                placeholder="Enter a title"
                v-model.trim="newColumn.title"
            />
            <div v-show="errorMessage">
        <span class="text-xs text-red-500">
          {{ errorMessage }}
        </span>
            </div>
        </div>
        <div class="form__footer">
            <button
                @click="$emit('column-canceled')"
                type="reset"
                class="button__cancel"
            >
                cancel
            </button>
            <button
                type="submit"
                class="button__submit"
            >
                Save
            </button>
        </div>
    </form>
</template>

<script>
export default {
    props: {

    },
    data() {
        return {
            newColumn: {
                id:null,
                title: "",
            },
            errorMessage: ""
        };
    },
    mounted() {

    },
    methods: {
        handleAddNewColumn() {

            if (!this.newColumn.title) {
                this.errorMessage = "The title field is required";
                return;
            }

            axios
                .post("/columns", this.newColumn)
                .then(res => {
                    // Tell the parent component we've added a new column
                    this.$emit("column-added", res.data);
                })
                .catch(err => {
                    // Handle the error returned from our request
                    this.handleErrors(err);
                });

        },
        handleErrors(err) {
            if (err.response && err.response.status === 422) {
                // We have a validation error
                const errorBag = err.response.data.errors;
                if (errorBag.title) {
                    this.errorMessage = errorBag.title[0];
                } else if (errorBag.description) {
                    this.errorMessage = errorBag.description[0];
                } else {
                    this.errorMessage = err.response.message;
                }
            } else {
                // We have bigger problems
                console.log(err.response);
            }
        }
    }
};
</script>
