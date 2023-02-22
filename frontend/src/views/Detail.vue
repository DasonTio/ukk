<template>
    <main class="grid grid-cols-[2fr_3fr_2fr] w-full h-[100vh] overflow-hidden">
        <NavBar />
        <section class="content  w-full h-full overflow-auto">
            <TweetPost v-if="!isLoading" :data="data" @refreshTweet="fetchSingleTweet()" />
            <form @submit.prevent="postComment($event)" class="flex pl-[70px] items-start gap-2 p-4 m-4 border-b-2 ">
                <img class="rounded-full w-[50px] aspect-square" :src="`${BASE_URL}${user.profile}`" alt="">
                <div class="flex flex-col w-full gap-y-2">
                    <textarea class="border-none bg-transparent resize-none w-full p-3" placeholder="Reply the tweet"
                        rows="2" name="comment" id="comment"></textarea>
                    <div class="flex justify-end">
                        <button type="submit" class="rounded-full py-2 px-6 btn-primary-outline">Reply</button>
                    </div>
                </div>
            </form>
            <TweetComment class="grid grid-cols-[50px_auto] pl-[70px] items-start gap-2 p-4 m-4" @refreshTweet="fetchSingleTweet()" v-for="comment in data.comments" :data="comment" />
        </section>
        <SideBar />
    </main>
</template>
<script>
import NavBar from '../components/NavBar.vue';
import SideBar from '../components/SideBar.vue';
import TweetPost from '../components/Tweet/Post.vue';
import TweetComment from '../components/Tweet/Comment.vue';

import axios from 'axios';
import { API_URL, BASE_URL } from '../constant';

export default {
    name: "Detail",
    components: {
        NavBar,
        SideBar,
        TweetPost,
        TweetComment
    },
    data() {
        return {
            BASE_URL,
            isLoading: true,

            data: [],
            user: {},
            route: this.$route.params,
        }
    },
    methods: {
        fetchMe: async function () {
            /**
             * Getting User (Client) Detail
             */
            const response = await axios.get(`${API_URL}/auth/me`, {
                headers: {}
            });
            const responseData = response.data.data;
            ;
            this.user = responseData;
        },
        fetchSingleTweet: async function () {
            /**
             * Getting Single Tweet
             */

            let id = this.route.tweetId
            const response = await axios.get(`${API_URL}/tweet/${id}`);
            const responseData = response.data.data;
            this.data = responseData
        },
        postComment: async function (event) {
            const comment = document.getElementById('comment');
            const form = new FormData(event.target);
            form.append('tweet_id', this.route.tweetId);

            const response = await axios.post(`${API_URL}/comment`, form);
            const responseData = response.data;

            await this.fetchSingleTweet();
            comment.value = "";
        }
    },
    mounted: async function () {
        axios.defaults.headers.common['Authorization'] = localStorage.token
        await this.fetchSingleTweet()
        await this.fetchMe();

        this.isLoading = false;
    }
}
</script>