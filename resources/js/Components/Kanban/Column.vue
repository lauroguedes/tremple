<template>
    <div
        class="w-72 max-h-full bg-gray-200 dark:bg-gray-700 flex flex-col rounded-md"
    >
        <div class="flex items-center justify-between px-3 py-2">
            <h3 class="font-semibold text-md text-gray-700 dark:text-gray-300">
                {{ columnTitle }}
            </h3>
            <MenuDropDown :menuItems="menuItems" />
        </div>
        <div class="pb-3 flex-1 flex flex-col overflow-hidden">
            <div class="px-3 overflow-y-auto" ref="cardsRef">
                <Draggable
                    v-model="cards"
                    group="cards"
                    item-key="id"
                    tag="ul"
                    drag-class="drag"
                    ghost-class="ghost"
                    class="space-y-3"
                    @change="onReorderCards"
                    @end="onReorderEnds"
                >
                    <template #item="{ element }">
                        <li>
                            <Card :card="element" />
                        </li>
                    </template>
                </Draggable>
                <CardCreate :column="column" @created="onCardCreated" />
            </div>
        </div>
    </div>
    <ConfirmDialog
        :show="isOpenModal"
        @confirm="handleDestroy($event)"
        title="Remove Column"
        message="Are you sure you want to delete this column and all its cards?"
    />
</template>

<script setup>
import { computed, nextTick, ref, watch } from "vue";
import { router } from "@inertiajs/vue3";
import cloneDeep from "lodash.clonedeep";
import Draggable from "vuedraggable";
import Card from "@/Components/Kanban/Card.vue";
import CardCreate from "@/Components/Kanban/CardCreate.vue";
import ConfirmDialog from "@/Components/Kanban/ConfirmDialog.vue";
import MenuDropDown from "@/Components/Kanban/MenuDropDown.vue";
import { useModal } from "@/Composables/useModal";

const props = defineProps({
    board: Number,
    column: Object,
});
const emit = defineEmits(["reorder-commit", "reorder-change"]);
const columnTitle = computed(() => props.column?.title);
const cards = ref(props?.column?.cards);
const cardsRef = ref();
// Keep the cards up-to-date
watch(
    () => props?.column,
    () => (cards.value = props?.column?.cards)
);

const { isOpenModal, openModal, closeModal } = useModal();

const handleDestroy = (confirm) => {
    closeModal();
    if (confirm) {
        router.delete(route("columns.destroy", { column: props?.column?.id }));
    }
};

const onCardCreated = async () => {
    // scroll to the bottom of the list
    await nextTick();
    cardsRef.value.scrollTop = cardsRef.value.scrollHeight;
};
const menuItems = [
    {
        name: "Delete column",
        action: () => openModal(),
    },
];
const onReorderCards = () => {
    const cloned = cloneDeep(cards?.value);
    const cardsWithOrder = [
        ...cloned?.map((card, index) => ({
            id: card.id,
            position: index * 1000 + 1000,
        })),
    ];

    emit("reorder-change", {
        id: props?.column?.id,
        cards: cardsWithOrder,
    });
};
const onReorderEnds = () => {
    emit("reorder-commit");
};
</script>

<style scoped>
.ghost {
    @apply opacity-25;
}
</style>
