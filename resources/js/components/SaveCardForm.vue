<template>
    <form
        class="form__card"
        @submit.prevent="handleAddNewCard"
    >
        <div class="form__body">
            <input
                type="text"
                placeholder="Enter a title"
                v-model.trim="newCard.title"
            />
            <textarea

                rows="2"
                placeholder="Add a description (optional)"
                v-model.trim="newCard.description"
            ></textarea>
            <div v-show="errorMessage">
        <span class="text-xs text-red-500">
          {{ errorMessage }}
        </span>
            </div>
        </div>
        <div class="form__footer">
            <button
                @click="$emit('card-canceled')"
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
        columnId: Number,
        oldCard: Object
    },
    data() {
        return {
            newCard: {
                id:null,
                title: "",
                description: "",
                column_id: null
            },
            errorMessage: ""
        };
    },
    mounted() {
        this.newCard.column_id = this.columnId;
        if(this.oldCard != null){
            this.newCard.title = this.oldCard.title;
            this.newCard.description = this.oldCard.description;
            this.newCard.id = this.oldCard.id;
        }

    },
    methods: {
        handleAddNewCard() {

            if (!this.newCard.title) {
                this.errorMessage = "The title field is required";
                return;
            }

            // Send new card to server
            if(this.oldCard == null){
                axios
                    .post("/cards", this.newCard)
                    .then(res => {
                        // Tell the parent component we've added a new card and include it
                        this.$emit("card-added", res.data);
                    })
                    .catch(err => {
                        // Handle the error returned from our request
                        this.handleErrors(err);
                    });
            } else {
                axios
                    .put("/cards/update", { card: this.newCard })
                    .then(res => {
                        // Tell the parent component we've added a new card and include it
                        this.$emit("card-updated", res.data);
                    })
                    .catch(err => {
                        // Handle the error returned from our request
                        this.handleErrors(err);
                    });
            }

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
