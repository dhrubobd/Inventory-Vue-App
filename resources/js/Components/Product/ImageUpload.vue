<script setup>
import { ref } from "vue";
const props = defineProps({
    productImage: String,
})
const dummyImage = ref('https://placehold.co/100x100?text=Product');
const currentImage = props.productImage ? props.productImage : dummyImage
const preview = ref(currentImage)

const emit = defineEmits(['image'])

const imageSelected = (e) => {
    const file = e.target.files[0]
    if(file){
        preview.value = URL.createObjectURL(file)
        emit('image', file)
    }
}

</script>
<template>
    <div>
        <label for="image">
            <img :src="(preview || currentImage) ?? dummyImage" class="img-thumbnail" height="50px" width="50px" />
        </label>
        <input
            @input="imageSelected($event)"
            type="file"
            name="image"
            id="image" />
    </div>
</template>
