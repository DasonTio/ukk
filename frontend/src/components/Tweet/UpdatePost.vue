<template >
    <!-- Update Tweet Form -->
    <form id="updateTweetForm"
        class="grid grid-cols-[70px_auto] items-start gap-2 p-4 m-4 border-b-2 ">
        <input type="text" v-model="update" name="update_status" hidden>
        <input type="text" :value="action" name="_method" hidden>

        <img class="aspect-square rounded-full" :src="`${BASE_URL}${user.profile}`" alt="">
        <div class="flex flex-col gap-2">
            <!-- Tweet Preview -->
            <div id="preview" :class="(active ? 'active' : 'normal') + ' relative'">
                <img id="attachment-preview" class="rounded-md w-full" :src="preview" alt="">
                <span class="mix-blend-difference text-white absolute top-1 right-1" @click="closePreview()">X</span>
            </div>

            <!-- Tweet Content -->
            <textarea class="resize-none bg-transparent" name="tweet" id="tweet" cols="30" rows="4"
                placeholder="Write something here..">{{ data.tweet }}</textarea>
            <input @change="previewImage($event)" type="file" name="media" id="updateMedia" hidden>
            
            <!-- Tweet Action -->
            <div class="flex justify-between items-center">
                <label for="updateMedia">
                    <i class="text-1xl fa-solid fa-paperclip text-secondary"></i>
                </label>
                <div class="w-36 flex justify-end">
                    <button type="submit" class="rounded-full btn-primary-outline font-bold px-6 py-2">Tweet</button>
                </div>
            </div>
        </div>
    </form>
</template>
<script>
import { BASE_URL, API_URL } from '../../constant';
export default {
    name: "TweetUpdate",
    data() {
        return {
            BASE_URL,
            preview: "",
            active: false,
            update: false,
        }
    },
    props: ['user', 'data', 'action'],
    methods: {
        addPreview: function () {
            this.active = true
        },
        closePreview: function () {
            document.getElementById('media').value = null;
            this.active = false
            this.update = true;
        },
        previewImage: function (e) {
            const file = e.target.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = (e) => {
                this.preview = e.target.result;
            }
            reader.readAsDataURL(file);
            this.update = true;

            this.addPreview();
        },
        previewImageFromUrl: async function () {
            this.preview = `${BASE_URL}${this.data.media}`
            this.addPreview();
        },
    },
    mounted() {
        if (!this.data.media) return;
        this.previewImageFromUrl();
    }
}
</script>
<style>
#preview {
    display: none;
}

#preview.active {
    display: block;
}
</style>