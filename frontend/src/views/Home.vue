<template>
    <main class="grid grid-cols-[2fr_3fr_2fr] w-full h-[100vh] overflow-hidden">
        <NavBar />
        <section class="content  w-full h-full">
            <header class="px-8 h-[10vh] bg-white border-b-2 flex items-center">
                <p class="font-bold text-2xl">Home</p>
            </header>

            <section class="h-full max-h-[90vh] overflow-auto">
                <!-- Create Tweet -->
                <form @submit.prevent="postTweet($event)"
                    class="grid grid-cols-[70px_auto] items-start gap-2 p-4 m-4 border-b-2 ">
                    <img class="aspect-square rounded-full" :src="`${BASE_URL}${user.profile}`" alt="">
                    <div class="flex flex-col gap-2">
                        <div id="preview" :class="(this.post.active ? 'active' : 'normal') + ' relative'">
                            <img id="attachment-preview" class="rounded-md w-full" :src="this.post.preview" alt="">
                            <span class="mix-blend-difference text-white absolute top-1 right-1"
                                @click="closePostPreview()">X</span>
                        </div>
                        <textarea class="resize-none bg-transparent" name="tweet" id="tweet" cols="30" rows="4"
                            placeholder="Write something here.."></textarea>
                        <input @change="postPreviewImage($event)" type="file" name="media" id="media" hidden>
                        <div class="flex justify-between items-center">
                            <label for="media">
                                <i class="text-1xl fa-solid fa-paperclip text-secondary"></i>
                            </label>
                            <div class="w-36 flex justify-end">
                                <button type="submit"
                                    class="rounded-full btn-primary-outline font-bold px-6 py-2">Tweet</button>
                            </div>
                        </div>
                    </div>
                </form>

                <!-- Tweets Belongs Here -->
                <TweetPost @refreshTweet="fetchTweets()" v-for="tweet in (tweets)" :data="tweet" />
            </section>
        </section>
        <SideBar />
    </main>
</template>
<script>
import NavBar from '../components/NavBar.vue';
import SideBar from '../components/SideBar.vue';
import TweetPost from '../components/Tweet/Post.vue';
import TweetUpdate from '../components/Tweet/UpdatePost.vue';
import Button from '../components/Button.vue';

import axios from 'axios';
import { API_URL, BASE_URL } from '../constant';

export default {
    name: "Home",
    data() {
        return {
            user: {},
            tweets: [],
            post: {
                active: false,
                preview: ''
            },
            BASE_URL: BASE_URL,
        }
    },
    components: {
        NavBar,
        SideBar,
        TweetPost,
        TweetUpdate,
        Button
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
            this.user = responseData;
        },
        fetchTweets: async function () {
            /**
             * Getting All Tweets
             */
            const response = await axios.get(`${API_URL}/tweet`);
            const responseData = response.data.data;

            this.tweets = responseData;
            console.log(responseData);
        },
        postTweet: async function (event) {
            /**
             * Getting files in element with id named media
             * and append it into media
             */
            const media = document.getElementById('media').files[0];
            const data = new FormData(event.target);
            data.append("media", media);

            const response = await axios.post(`${API_URL}/tweet`, data, {
                header: {
                    'Content-Type': 'multipart/form-data'
                }
            });

            if (response.status == 200) {
                this.fetchTweets()
                this.clearForm();
            };
        },
        clearForm: function () {
            /**
             * Clear Create Post Form
             */
            const preview = document.getElementById('preview');
            document.getElementById('media').value = null;
            preview.classList.remove('active');
            document.getElementById('tweet').value = "";
        },

        // Toggling Post Preview (On)
        addPostPreview: function () {
            this.post.active = true
        },
        // Toggling Post Preview (Off)
        closePostPreview: function () {
            document.getElementById('media').value = null;
            this.post.active = false
        },
        postPreviewImage: function (e) {
            /**
             * Place Image Preview to the preview slot
             */
            const file = e.target.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = (e) => {
                this.post.preview = e.target.result;
            }
            reader.readAsDataURL(file);
            this.addPostPreview();
        },
    },
    mounted: function () {
        /**
         * Make axios have a token for authentication in middleware laravel
         */
        axios.defaults.headers.common['Authorization'] = localStorage.token;
        this.fetchMe();
        this.fetchTweets();
    }
}
</script>
<style scoped>
textarea:focus {
    outline: none;
}

.normal {
    color: #b8c0c4ab;
}
</style>