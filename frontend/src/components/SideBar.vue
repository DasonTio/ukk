<template lang="">
    <section class="flex flex-col items-center py-20 gap-5 w-full px-12 bg-white border-l-2" >
        <input @change="fetchFilter($event)" class="rounded-full p-4 border-2 w-full" placeholder="#searchhashtag">
        <div class="overflow-y-auto max-h-full">
            <List v-for="tagTweet in tagTweets" :data="tagTweet" />
            <List v-for="tagComment in tagComments" :data="tagComment" />
        </div>
    </section>
</template>
<script>
import List from '../components/Tweet/List.vue';
import Button from './Button.vue';
import axios from 'axios';
import { API_URL, BASE_URL } from '../constant';
export default {
    name: "SideBar",
    data() {
        return {
            tagTweets: [],
            tagComments: [],
        }
    },
    components: {
        Button,
        List
    },
    methods: {
        fetchFilter: async function (event) {
            const name = event.target.value;
            await axios.get(`${API_URL}/filter?name=${encodeURIComponent(name)}`).then((response) => {
                const responseData = response.data.data;
                this.tagTweets = responseData.tag_tweets;
                this.tagComments = responseData.tag_comments;
            }).catch((err) => {
                this.tagTweets = []
                this.tagComments = []
            });
        }
    }
}
</script>


<style scoped>
input {
    border-color: #d0e3ffcc;
    transition: .3s ease-in-out;
}

input::placeholder {
    color: #d0e3ffcc;
}

input:active,
input:focus {
    outline: none;
    border-color: #66A4FF;
}

input:active::placeholder,
input:focus::placeholder {
    color: #66A4FF;
}
</style>