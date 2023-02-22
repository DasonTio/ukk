<template lang="">
    <!-- Side Bar -->
    <section class="flex flex-col items-center py-20 gap-5 w-full px-12 bg-white border-l-2" >
        <input @change="fetchFilter($event)" class="rounded-full p-4 border-2 w-full" placeholder="#searchhashtag">
        <div class="overflow-y-auto w-full max-h-full flex flex-col">
            <List v-for="tagTweet in tagTweets" :usedData="{
                created_at:tagTweet.tweet.created_at,
                content:tagTweet.tweet.tweet,
                tweet_id:tagTweet.tweet.id,
                user: tagTweet.tweet.user,
            }" />

            <List v-for="tagComment in tagComments" :usedData="{
                created_at:tagComment.comment.created_at,
                content:tagComment.comment.comment,
                tweet_id:tagComment.comment.id,
                user: tagComment.comment.user,
            }" />
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
        /**
         * Function for filtering the tweet and comment with specific tag
         */
        fetchFilter: async function (event) {
            const name = event.target.value;
            const response = await axios.get(`${API_URL}/filter?name=${encodeURIComponent(name)}`).catch((err) => {
                this.tagTweets = []
                this.tagComments = []
            });
            const responseData = response.data.data;
            this.tagTweets = responseData.tag_tweets;
            this.tagComments = responseData.tag_comments;
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