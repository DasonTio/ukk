<template>
    <!-- Tweet Comment -->
    <div id="tweetCommentMain">
        <img class="rounded-full aspect-square" :src="`${BASE_URL}${user.profile}`" alt="">
        <div>
            <div class="flex justify-between">
                <p class="flex items-center gap-1 text-placeholder text-sm">
                    <span class="font-bold text-black">{{ user.username }}</span>
                    <span class="text-sm">@{{ user.username.toLowerCase().replace(' ', '') }} · {{ posted_at }}</span>
                </p>

                <!-- Tweet Comment Action (Edit and Delete) -->
                <div id="tweetCommentAction" class="flex gap-1 opacity-0">
                    <button v-if="user.id == active_user_id"
                        class="btn-primary-outline text-sm rounded-md px-1 aspect-square " type="button"
                        @click="toggleModal()"><i class="fa-solid fa-pen-to-square "></i> </button>

                    <button v-if="user.id == active_user_id"
                        class="btn-warning-outline text-sm rounded-md px-1 aspect-square " type="button"
                        @click="deleteComment()"><i class="fa-solid fa-trash-can"></i> </button>
                </div>
            </div>
            <!-- Tweet Comment Content -->
            <p class="break-all">{{ data.comment }}</p>
        </div>
        <!-- Tweet Comment Edit Modal -->
        <div v-if="modal_active" id="modal" class="absolute left-0 top-0 w-full h-full flex items-center justify-center">
            <TweetCommentUpdate @submit.prevent="updateComment($event)" :user="user" :data="data" action="PUT"
                class="w-fit p-4 border-2 rounded-md bg-white z-10 max-w-md" />
            <div class="absolute w-full h-full z-1" style="background: rgba(0,0,0,.1);" @click="toggleModal()"></div>
        </div>
    </div>
</template>
<script>
import TweetCommentUpdate from '../Tweet/UpdateComment.vue';
import { BASE_URL, API_URL } from '../../constant';
import axios from 'axios';


/**
 * Checking the date Difference between Create post date and Current date
 */
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
    name: "TweetComment",
    data() {
        return {
            BASE_URL,
            user: this.data.user,
            active_user_id: localStorage.user_id,
            posted_at: dateDifference(this.data.created_at),
            modal_active: false,
        }
    },
    props: ['data'],
    methods: {
        /**
         * Toggle Modal
         */
        toggleModal: function () {
            this.modal_active = !this.modal_active
        },
        /**
         * Update Comment and triggering Parent For Refresh the Data
         */
        updateComment: async function (e) {
            const form = new FormData(e.target);
            const response = await axios.post(`${API_URL}/comment/${this.data.id}`, form).catch((err)=>this.popMessage("Something Went Wrong"));
            const responseData = response.data

            this.popMessage("Update Comment Success", false)
            this.toggleModal();
            this.$emit('refreshTweet');
        },
        /**
         * Delete Comment and triggering Parent for Refresh the Data
         */
        deleteComment: async function (e) {
            const response = await axios.delete(`${API_URL}/comment/${this.data.id}`).catch((err)=>this.popMessage("Something Went Wrong"));
            const responseData = response.data
            
            this.popMessage("Delete Comment Success", false);
            this.$emit('refreshTweet');
        }
    },
    /**
     * Watching the data.user and updating this.user
     */
    updated() {
        this.user = this.data.user;
    },
    components: {
        TweetCommentUpdate
    }
}
</script>
<style scoped>
#tweetCommentMain:hover #tweetCommentAction {
    transition: .3s ease-in-out;
    opacity: 100%;
}
</style>