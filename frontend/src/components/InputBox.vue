<template>
    <div class="flex">
        <!-- <label :id="id" class="capitalize">{{ name }}</label> -->
        <input :id="id" class="p-4 border-2 rounded-md w-full" :type="type" :name="name.toLowerCase().replace(' ', '_')"
            :placeholder="name" :value="value ?? ''">
    </div>
</template>
<script>
export default {
    name: "InputBox",
    props: {
        name: String,
        type: String,
        id: String,
        value: String,
    },
}
</script>
<style scoped>
input {
    background-color: #F3F7F9;
}

input::placeholder {
    color: #B3BABD;
}
</style>