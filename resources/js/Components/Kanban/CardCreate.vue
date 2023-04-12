<template>
    <div class="mt-3">
        <form
            v-if="isCreating"
            @keydown.esc="isCreating = false"
            @submit.prevent="onSubmit"
        >
            <TextArea
                v-model="form.content"
                type="text"
                @keydown.enter.prevent="onSubmit"
                placeholder="Card content ..."
                ref="inputCardContentRef"
                rows="3"
                class="w-full"
            >
            </TextArea>
            <InputError :message="form.errors.content" />
            <div class="mt-2 space-x-2">
                <PrimaryButton :disabled="!form.content" type="submit">
                    Add card
                </PrimaryButton>
                <SecondaryButton @click.prevent="isCreating = false">
                    Cancel
                </SecondaryButton>
            </div>
        </form>
        <button
            v-if="!isCreating"
            @click.prevent="showForm"
            type="button"
            class="flex items-center p-2 text-sm rounded-md font-medium bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-800 text-gray-600 hover:text-black dark:text-gray-400 dark:hover:text-gray-500 w-full"
        >
            <PlusIcon class="w-5 h-5" />
            <span class="ml-1">Add card</span>
        </button>
    </div>
</template>

<script setup>
import { nextTick, ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import { PlusIcon } from "@heroicons/vue/24/solid";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import TextArea from "@/Components/TextArea.vue";
import InputError from "@/Components/InputError.vue";

const props = defineProps({
    column: Object,
});
const emit = defineEmits(["created"]);
const form = useForm({
    content: "",
});
const inputCardContentRef = ref();
const isCreating = ref(false);
const onSubmit = () => {
    form.post(route("columns.cards.store", { column: props?.column?.id }), {
        onSuccess: () => {
            form.reset();
            isCreating.value = false;
            emit("created");
        },
    });
};
const showForm = async () => {
    isCreating.value = true;
    await nextTick();
    inputCardContentRef.value.focus();
};
</script>
