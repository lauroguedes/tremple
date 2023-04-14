import { ref } from "vue";

export const useModal = () => {
    const isOpenModal = ref(false);

    function openModal() {
        isOpenModal.value = true;
    }

    function closeModal() {
        isOpenModal.value = false;
    }

    return { isOpenModal, openModal, closeModal };
};
