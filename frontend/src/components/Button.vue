<template>
    <button class="rounded-full w-full p-5 text-white font-medium" :type="type">{{ name }}</button>
</template>
<script>
export default {
    name:"Button",
    props:{
        name: String,
        type: String
    }
}
</script>

<style scoped>
button{
    background-color: #66A4FF;
}
</style>