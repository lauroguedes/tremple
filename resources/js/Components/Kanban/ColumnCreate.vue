<template>
    <div>
        <form
            v-if="isCreating"
            @keydown.esc="isCreating = false"
            @submit.prevent="onSubmit"
            class="p-3 bg-gray-200 dark:bg-gray-700 rounded-md"
        >
            <TextInput
                v-model="form.title"
                type="text"
                placeholder="Column name ..."
                ref="inputColumnNameRef"
                class="w-full"
            />
            <div class="mt-2 space-x-2">
                <PrimaryButton :disabled="!form.title" type="submit">
                    Add column
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
            <span class="ml-1">Add another list</span>
        </button>
    </div>
</template>

<script setup>
import { nextTick, ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import { PlusIcon } from "@heroicons/vue/24/solid";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import TextInput from "@/Components/TextInput.vue";

const props = defineProps({
    board: Object,
});
const form = useForm({
    title: "",
});
const inputColumnNameRef = ref();
const isCreating = ref(false);
const onSubmit = () => {
    form.post(route("boards.columns.store", { board: props?.board }), {
        onSuccess: () => {
            form.reset();
            isCreating.value = false;
        },
    });
};
const showForm = async () => {
    isCreating.value = true;
    await nextTick(); // wait for form to be rendered
    inputColumnNameRef.value.focus();
};
</script>
