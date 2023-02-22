<template lang="">
    <main class="grid grid-cols-[2fr_3fr_2fr] w-full h-[100vh]">
        <NavBar />

        <section class="content  w-full h-full overflow-auto" v-if="user.username">
            <header class="p-8 h-[10vh] bg-white border-b-2 flex items-center">
                <p class="font-bold text-2xl">Profile</p>
            </header>
            <section class="">
                <div class="bg-slate-300 h-[20vh] relative p-12 mb-32">
                    <label class="absolute aspect-square w-32 border-white border-[3px] rounded-full bottom-0" :style="`background:url('${BASE_URL}${user.profile}'); background-size:cover; transform:translateY(50%)`" ></label>
                    <button @click="toggleModal()" class="btn-primary-outline rounded-full px-2 py-1 absolute bottom-0 right-12 translate-y-14">Edit Profile</button>
                </div>
                <section class="px-12 flex flex-col gap-3">
                    <div class="flex flex-col">
                        <p class="text-xl ">
                            <span class="font-bold">
                                {{user.username}} 
                            </span>
                            <span class="text-sm">
                                @{{ user.username.toLowerCase() }}
                            </span>
                        </p>
                        <p class="break-all">{{user.bio ?? 'There is no bio set by user'}}</p>
                    </div>

                <div class="flex flex-col overflow-auto">
                        <h1 class="text-2xl font-bold">Tweets</h1>
                        <TweetPost @refreshTweet="fetchUserTweets()" v-for="tweet in tweets" :data="tweet" />
                        
                        <h1 class="text-2xl font-bold">Comment</h1>
                        <TweetComment @refreshTweet="fetchUserTweets()" v-for="comment in comments" :data="comment" class="grid grid-cols-[50px_auto] pl-[70px] items-start gap-2 p-4 m-4" />
                    </div>
                </section>
            </section>
        </section>
        <div v-if="modal_active" id="modal" class="absolute left-0 top-0 w-full h-full flex items-center justify-center">
            <UserUpdate :user="user" action="PUT" @refreshTweet="refreshTweet()"
                class="w-96 p-4 border-2 rounded-md bg-white z-10 max-w-6xl" />
            <div class="absolute w-full h-full z-1" style="background: rgba(0,0,0,.1);" @click="toggleModal()"></div>
        </div>
        <SideBar />
    </main>
</template>
<script>
import NavBar from '../components/NavBar.vue';
import SideBar from '../components/SideBar.vue';
import axios from 'axios';
import { BASE_URL, API_URL } from '../constant';
import TweetPost from '../components/Tweet/Post.vue';
import TweetComment from '../components/Tweet/Comment.vue';
import UserUpdate from '../components/UserUpdate.vue';

export default {
    name: "UserProfile",
    data() {
        return {
            BASE_URL,
            user: {},
            tweets: [],
            comments: [],
            modal_active: false,
        }
    },
    components: {
        NavBar,
        SideBar,
        TweetPost,
        TweetComment,
        UserUpdate
    },
    methods: {
        fetchMe: async function () {
            /**
             * Getting User (Client) Detail
             */
            const response = await axios.get(`${API_URL}/auth/me`);
            const responseData = response.data.data;
            this.user = responseData;
        },
        fetchUserTweets: async function () {
            const response = await axios.get(`${API_URL}/tweet/user`);
            const responseData = response.data.data;

            this.tweets = responseData.tweets;
            this.comments = responseData.comments;
            console.log(responseData.comments)
        },
        refreshTweet: function () {
            this.toggleModal()
            this.fetchMe()
            this.fetchUserTweets()
        },
        toggleModal: function () {
            this.modal_active = !this.modal_active
        },
    },
    mounted: async function () {
        axios.defaults.headers.common["Authorization"] = localStorage.token
        await this.fetchMe();
        await this.fetchUserTweets();
    }
}
</script>