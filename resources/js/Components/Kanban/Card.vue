<template>
    <div
        class="cursor-move relative group p-2 bg-white dark:bg-gray-400 shadow rounded-md border border-b border-gray-30 dark:border-gray-400 hover:opacity-90"
    >
        <form
            v-if="isEditing"
            @keydown.esc="onCancel"
            @submit.prevent="onSubmit"
        >
            <TextArea
                v-model="form.content"
                type="text"
                @keydown.enter.prevent="onSubmit"
                placeholder="Card content ..."
                ref="inputCardContentRef"
                rows="3"
                class="mt-1 block w-full"
            >
            </TextArea>
            <div class="mt-2 space-x-2">
                <PrimaryButton :disabled="!form.content" type="submit">
                    Save card
                </PrimaryButton>
                <SecondaryButton @click.prevent="onCancel">
                    Cancel
                </SecondaryButton>
            </div>
        </form>
        <div v-else>
            <p class="text-sm">{{ cardContent }}</p>
            <div
                class="hidden absolute right-1 inset-0 group-hover:flex justify-end space-x-2 items-center"
            >
                <button
                    @click.prevent="showForm"
                    class="w-8 h-8 bg-gray-50 dark:bg-gray-400 text-gray-600 hover:text-black hover:bg-gray-200 dark:hover:bg-gray-500 rounded-md grid place-content-center"
                >
                    <PencilIcon class="w-5 h-5" />
                </button>
                <button
                    @click.prevent="openModal"
                    class="w-8 h-8 bg-gray-50 dark:bg-gray-400 text-red-600 hover:text-red-700 hover:bg-gray-200 dark:hover:bg-gray-500 rounded-md grid place-content-center"
                >
                    <TrashIcon class="w-5 h-5" />
                </button>
            </div>
        </div>
    </div>
    <ConfirmDialog
        :show="isOpenModal"
        @confirm="handleDestroy($event)"
        title="Remove Card"
        message="Are you sure you want to delete this card?"
    />
</template>

<script setup>
import { computed, nextTick, ref } from "vue";
import { router, useForm } from "@inertiajs/vue3";
import { PencilIcon } from "@heroicons/vue/24/solid";
import { TrashIcon } from "@heroicons/vue/24/solid";
import { useEditCard } from "@/Composables/useEditCard";
import ConfirmDialog from "@/Components/Kanban/ConfirmDialog.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import TextArea from "@/Components/TextArea.vue";
import { useModal } from "@/Composables/useModal";

const props = defineProps({
    card: Object,
});

const { isOpenModal, openModal, closeModal } = useModal();

const handleDestroy = (confirm) => {
    closeModal();
    if (confirm) {
        router.delete(
            route("columns.cards.destroy", {
                card: props?.card?.id,
                column: props?.card?.column,
            })
        );
    }
};
const form = useForm({
    content: props?.card?.content,
});
const inputCardContentRef = ref();
const isEditing = computed(
    () => props?.card?.id === useEditCard?.value?.currentCard
);
const cardContent = computed(() => props.card?.content);
const onSubmit = () => {
    form.put(
        route("columns.cards.update", {
            column: props?.card?.column,
            card: props?.card?.id,
        }),
        {
            onSuccess: () => {
                useEditCard.value.currentCard = null;
            },
        }
    );
};
const onCancel = () => {
    useEditCard.value.currentCard = null;
    form.reset();
};
const showForm = async () => {
    useEditCard.value.currentCard = props?.card?.id;
    await nextTick(); // wait for form to be rendered
    inputCardContentRef.value.focus();
};
</script>
