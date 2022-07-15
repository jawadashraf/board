<template>
    <div class="board">
        <div v-for="column in columns"
             :key="column.id"
             class="column__container">
            <button
                @click="handleDeleteColumn(column.id)"
                class="remove__column__button"
            >
                x
            </button>
            <div class="column">
            <div class="column__head">
                <span class="column__title">
                    {{ column.title }}
                </span>
                <button
                    @click="openAddCardForm(column.id)"
                    class="add__card__button"
                >
                    Add Card
                </button>



            </div>
            <div class="column__body">
                <SaveCardForm
                    v-if="newCardForColumn === column.id"
                    :column-id="column.id"
                    v-on:card-added="handleCardAdded"
                    v-on:card-canceled="closeAddCardForm"
                />

                <!-- Cards -->
                <draggable
                    class="draggable__for__card"
                    v-model="column.cards"
                    v-bind="cardDragOptions"
                    @end="handleCardMoved"
                >
                    <transition-group
                        class="transition__group"
                        tag="div"
                    >
                        <div
                            v-for="card in column.cards"
                            :key="card.id"
                            class="card"

                        >
                            <SaveCardForm
                                v-if="oldCardForColumn === column.id && editedCardId === card.id"
                                :column-id="column.id"
                                :old-card="card"
                                v-on:card-updated="handleCardUpdated"
                                v-on:card-canceled="closeUpdateCardForm"
                            />
                <span v-if="!(oldCardForColumn === column.id && editedCardId === card.id)" class="card__title"
                      @click="openUpdateCardForm(column.id, card)"
                >
                  {{ card.title }}
                </span>
                        </div>
                        <!-- ./Cards -->
                    </transition-group>
                </draggable>

            </div>
            </div>
        </div>

<!--        Empty Column-->
        <div class="column__container">
            <div class="column">
                <div class="column__head">
                    <span class="column__title">
                        Add Column
                    </span>
                    <button
                        @click="openAddColumnForm()"
                        class="add__card__button"
                    >
                        Add Column
                    </button>

                </div>
                <div class="column__body">
                    <SaveColumnForm
                        v-if="newColumn === 1"
                        v-on:column-added="handleColumnAdded"
                        v-on:column-canceled="closeAddColumnForm"
                    />
                </div>

            </div>
        </div>


    </div>
</template>

<script>
import draggable from "vuedraggable";
import SaveCardForm from "./SaveCardForm";
import SaveColumnForm from "./SaveColumnForm";

export default {
    components: {draggable, SaveCardForm, SaveColumnForm},

    props: {
        columnsData: Array
    },
    data() {
        return {
            columns: [],
            newCardForColumn: 0,
            oldCardForColumn: 0,
            editedCardId: 0,

            newColumn: 0,
        };
    },
    computed: {
        cardDragOptions() {
            return {
                animation: 200,
                group: "card-list",
                dragClass: "column-drag"
            };
        }
    },
    mounted() {

        this.columns = JSON.parse(JSON.stringify(this.columnsData));
    },

    methods: {
        openAddCardForm(columnId) {
            this.newCardForColumn = columnId;
        },
        closeAddCardForm() {
            this.newCardForColumn = 0;
        },
        openUpdateCardForm(columnId, card) {
            this.oldCardForColumn = columnId;
            this.editedCardId = card.id;

        },
        closeUpdateCardForm() {

            this.oldCardForColumn = 0;
            this.editedCardId = 0;
        },
        handleCardAdded(newCard) {
            // Find the index of the column where we should add the card
            const columnIndex = this.columns.findIndex(
                column => column.id === newCard.column_id
            );


            // Add newly created card to our column
            if(!this.columns[columnIndex].cards) this.columns[columnIndex].cards = []
            this.columns[columnIndex].cards.push(newCard);

            // Reset and close the AddCardForm
            this.closeAddCardForm();
        },

        handleCardUpdated(updatedCard) {
            // // Find the index of the column where we should add the card
            const columnIndex = this.columns.findIndex(
                column => column.id === updatedCard.column_id
            );
            //
            // // Add newly created card to our column
            const cardIndex = this.columns[columnIndex].cards.findIndex(
                card => card.id === updatedCard.id
            );

            this.columns[columnIndex].cards[cardIndex] = updatedCard;


            // Reset and close the AddCardForm
            this.closeUpdateCardForm();
        },

        handleCardMoved(evt) {

            axios.put("/cards/sync", { columns: this.columns })
                .then((response) => {
                    console.log(response);
                })
                .catch(err => {
                console.log(err.response);
            });
        },


        openAddColumnForm() {
            this.newColumn = 1;
        },
        closeAddColumnForm() {
            this.newColumn = 0;
        },

        handleColumnAdded(newColumn) {

            this.columns.push(newColumn);

            this.closeAddColumnForm();
        },

        handleDeleteColumn(id) {


            axios
                .delete(`/columns/${id}`)
                .then(res => {
                    // Tell the parent component we've added a new column

                    const columnIndex = this.columns.findIndex(
                        column => column.id === id
                    );

                    this.columns.splice(columnIndex, 1);
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

<style scoped>
.column-drag {
    transition: transform 0.5s;
    transition-property: all;
}
</style>
