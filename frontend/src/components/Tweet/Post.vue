<template>
    <div id="tweetPostMain" class="grid grid-cols-[70px_auto] items-start gap-2 p-4 m-4 border-b-2">
        <img class="aspect-square rounded-full" :src="`${BASE_URL}${data.user.profile}`" alt="">
        <div class="flex flex-col gap-2">
            <div class="flex justify-between">
                <p class="text-xl flex items-center gap-1 text-placeholder ">
                    <span class="font-bold text-black">
                        {{ data.user.username }}
                    </span>
                    <span class="text-sm">
                        @{{ data.user.username.toLowerCase().replace(' ', '') }} ·
                        {{ posted_at }}
                    </span>
                </p>
                <div id="tweetPostAction" class="flex gap-1 opacity-0">
                    <button v-if="data.user.id == active_user_id"
                        class="btn-primary-outline text-sm rounded-md px-1 aspect-square " type="button"
                        @click="toggleModal()"><i class="fa-solid fa-pen-to-square "></i> </button>

                    <button v-if="data.user.id == active_user_id"
                        class="btn-warning-outline text-sm rounded-md px-1 aspect-square " type="button"
                        @click="deletePost()"><i class="fa-solid fa-trash-can"></i> </button>
                </div>

            </div>
            <div class="flex flex-col gap-2 cursor-pointer" @click="$router.push({ path: `/${data.id}` })">
                <p class="break-all">{{ data.tweet }}</p>
                <div class="media mb-3" v-if="data.media != null">
                    <img class="rounded-xl" :src="`${BASE_URL}${data.media}`" alt="" srcset="">
                </div>
                <div class="flex">
                    <div class="action flex items-center gap-1">
                        <i class="fa-regular fa-message"></i>
                        <span class="text-sm">
                            {{ data.comments.length }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="modal_active" id="modal" class="absolute left-0 top-0 w-full h-full flex items-center justify-center">
            <TweetUpdate @submit.prevent="updatePost($event)" :user="data.user" :data="data" action="PUT"
                class="w-fit p-4 border-2 rounded-md bg-white z-10 max-w-md" />
            <div class="absolute w-full h-full z-1" style="background: rgba(0,0,0,.1);" @click="toggleModal()"></div>
        </div>
    </div>
</template>
<script>
import { BASE_URL, API_URL } from '../../constant';
import TweetUpdate from './UpdatePost.vue';
import axios from 'axios';

function dateDifference(date) {
    let diff = (new Date() - new Date(date)) / 1000; // Difference in Seconds
    let day = 60 * 60 * 24,
        hour = 60 * 60,
        minute = 60;
    if (diff > day) return `${~~(diff / day)} d`
    if (diff > hour) return `${~~(diff / hour)} h`
    if (diff > minute) return `${~~(diff / minute)} m`
    return `${~~(diff)}s`;
}
export default {
    name: "TweetPost",
    data() {
        return {
            BASE_URL,
            modal_active: false,
            active_user_id: localStorage.user_id,
            posted_at: dateDifference(this.data.created_at),
        }
    },
    props: ['data'],
    emits: ['refreshTweet'],
    components: {
        TweetUpdate
    },
    methods: {
        toggleModal: function () {
            this.modal_active = !this.modal_active
        },
        updatePost: async function (event) {
            const media = document.getElementById('updateMedia').files[0];
            const formData = new FormData(event.target);
            formData.set('media', media);

            const response = await axios.post(`${API_URL}/tweet/${this.data.id}`, formData, {
                headers: {
                    "Content-Type": 'multipart/form-data'
                }
            });
            const responseData = response.data.data;

            this.toggleModal()
            this.$emit('refreshTweet');
        },
        deletePost: async function (event) {
            const response = await axios.delete(`${API_URL}/tweet/${this.data.id}`);
            const responseData = response.data.data;
            this.$emit('refreshTweet');
        }
    },
    mounted: function () {
        axios.defaults.headers.common['Authorization'] = localStorage.token;
    },
}
</script>
<style scoped>
#tweetPostMain:hover #tweetPostAction {
    transition: .3s ease-in-out;
    opacity: 100%;
}
</style>