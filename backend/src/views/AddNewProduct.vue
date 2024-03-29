<template>
    <TransitionRoot as="template" :show="show">
        <Dialog as="div" class="relative z-10" @close="show = false">
            <TransitionChild
                as="template"
                enter="ease-out duration-300"
                enter-from="opacity-0"
                enter-to="opacity-100"
                leave="ease-in duration-200"
                leave-from="opacity-100"
                leave-to="opacity-0"
            >
                <div
                    class="fixed inset-0 bg-black bg-opacity-70 transition-opacity"
                ></div>
            </TransitionChild>

            <div class="fixed z-10 inset-0 overflow-y-auto">
                <div
                    class="flex items-end sm:items-center justify-center min-h-full p-4 text-center sm:p-0"
                >
                    <TransitionChild
                        as="template"
                        enter="ease-out duration-300"
                        enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        enter-to="opacity-100 translate-y-0 sm:scale-100"
                        leave="ease-in duration-200"
                        leave-from="opacity-100 translate-y-0 sm:scale-100"
                        leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    >
                        <DialogPanel
                            class="relative bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-lg sm:w-full"
                        >
                            <Spinner
                                v-if="loading"
                                class="absolute left-0 top-0 bg-white right-0 bottom-0 flex items-center justify-center"
                            />
                            <header
                                class="py-3 px-4 flex justify-between items-center"
                            >
                                <DialogTitle
                                    as="h3"
                                    class="text-lg leading-6 font-medium text-gray-900"
                                >
                                    {{
                                        product.id
                                            ? `Update product: "${product.title}"`
                                            : "Create new Product"
                                    }}
                                </DialogTitle>
                                <button
                                    @click="closeModal()"
                                    class="w-8 h-8 flex items-center justify-center rounded-full transition-colors cursor-pointer hover:bg-[rgba(0,0,0,0.2)]"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-6 w-6"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"
                                        />
                                    </svg>
                                </button>
                            </header>
                            <form @submit.prevent="onSubmit">
                                <div
                                    class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4"
                                >
                                    <CustomInput
                                        class="mb-2"
                                        v-model="product.title"
                                        label="Product Title"
                                    />
                                    <input
                                        ref="file"
                                        type="file"
                                        @change="handleFileUpload($event)"
                                        accept="image/*"
                                        capture
                                    />
                                    <!-- Image preview section -->
                                    <div v-if="product.image" class="mt-4">
                                        <p class="text-lg font-semibold mb-2">
                                            Selected Image Preview:
                                        </p>
                                        <div class="relative">
                                            <img
                                                :src="imagePreview"
                                                alt="Selected Image"
                                                class="w-full h-auto object-cover rounded-md mb-2"
                                            />
                                            <button
                                                @click="removeImage"
                                                class="absolute top-0 right-0 mt-1 mr-1 p-1 bg-white text-red-500 rounded-full focus:outline-none"
                                            >
                                                <!-- Add an icon or text for the cancel button -->
                                                X
                                            </button>
                                        </div>
                                    </div>

                                    <CustomInput
                                        type="textarea"
                                        class="mb-2"
                                        v-model="product.description"
                                        label="Description"
                                    />
                                    <CustomInput
                                        type="number"
                                        class="mb-2"
                                        v-model="product.price"
                                        label="Price"
                                        prepend = "$"
                                    />
                                    <CustomInput
                                        class="mb-2"
                                        v-model="product.title"
                                        label="Product Title"
                                    />
                                </div>
                                <footer
                                    class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse"
                                >
                                    <button
                                        type="submit"
                                        class="py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 ml-3"
                                    >
                                        Submit
                                    </button>
                                    <button
                                        type="button"
                                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                                        @click="closeModal"
                                        ref="cancelButtonRef"
                                    >
                                        Cancel
                                    </button>
                                </footer>
                            </form>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
</template>

<script setup>
import { computed, onUpdated, ref } from "vue";

import {
    Dialog,
    DialogPanel,
    DialogTitle,
    TransitionChild,
    TransitionRoot,
} from "@headlessui/vue";
import { ExclamationIcon } from "@heroicons/vue/outline";
import CustomInput from "../components/core/CustomInput.vue";
import store from "../store/index.js";
import { PRODUCTS_PER_PAGE } from "../constants";
import Spinner from "../components/core/Spinner.vue";
const perPage = ref(PRODUCTS_PER_PAGE);
const search = ref("");
const sortField = ref("updated_at");
const sortDirection = ref("desc");
const loading = ref(false);
const imagePreview = ref(null);

const props = defineProps({
    modelValue: Boolean,
    product: {
        required: true,
        type: Object
    },
});

const product = ref({
    id: props.product.id,
    title: props.product.title,
    image: props.product.image_url,
    description: props.product.description,
    price: props.product.price,
});

onUpdated(() => {
    product.value = {
        id: props.product.id,
        title: props.product.title,
        image: props.product.image,
        description: props.product.description,
        price: props.product.price,
    };
});

const emit = defineEmits(["update:modelValue", 'close']);
const show = computed({
    get: () => props.modelValue,
    set: (value) => emit("update:modelValue", value),
});
function closeModal() {
    show.value = false;
    emit('close');
}
const file = ref(null);

const handleFileUpload = async ($event) => {
    const file = $event.target.files[0];

    // Convert the file to a data URL for preview
    const reader = new FileReader();
    reader.onload = (e) => {
        imagePreview.value = e.target.result;
    };
    reader.readAsDataURL(file);

    product.value.image = $event.target.files[0];
};
function onSubmit() {
    loading.value = true;
    if(product.value.id){
        store.dispatch('updateProduct', product.value)
        .then(res => {
            if(res.status === 200){
                store.dispatch("getProducts");
                closeModal();
                loading.value = false;
            }
        })
    }else{
    store
        .dispatch("createProduct", product.value)
        .then((response) => {
            loading.value = false;
            if (response.status === 201) {
                store.dispatch("getProducts");
                closeModal()
                product.value = {
                    title: null,
                    image: null,
                    description: null,
                    price: null,
                };
            }
        })
        .catch((err) => {
            loading.value = false;
        });
    }
}

const removeImage = () => {
  product.value.image = null;
  imagePreview.value = null;
};
</script>
