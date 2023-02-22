<template lang="">
    <form @submit.prevent="updateUser($event)">
        <input type="hidden" name="_method" :value="action">
        <input type="hidden" name="update_status" v-model="update">
        <div class="flex flex-col justify-center gap-2">
            <div class="inputbox-file">
                <input @change="previewImage($event)" type="file" name="profile" accept="image/*" id="input-file"
                    hidden>
                <label for="input-file" class="flex py-20 items-center justify-center border-2 rounded-md bg-cover"
                    id="input-file-label">
                    <i class="fa-solid fa-image "></i></label>
            </div>
                <InputBox name="Username" type="text" id="input-username" :value="user.username" />
                <textarea name="bio" id="" rows="3" class="p-4 border-2 rounded-md resize-none" placeholder="Type your bio here">{{user.bio}}</textarea>
                <Button type="submit" name="Update" />
        </div>
    </form> 
</template>
<script>
import InputBox from './InputBox.vue';
import Button from './Button.vue';
import { BASE_URL, API_URL } from '../constant';
import axios from 'axios';

export default {
    name: "UserUpdate",
    props: ['user', 'action'],
    data() {
        return {
            update: false,
        }
    },
    components: {
        InputBox,
        Button
    },
    methods: {
        previewImage: function (e) {
            const ImageLabel = document.getElementById('input-file-label');
            const file = e.target.files[0];
            const reader = new FileReader();
            reader.onload = (e) => {
                ImageLabel.style.backgroundImage = `url('${e.target.result}')`;
                ImageLabel.innerHTML = ""
                this.update = true;
            }
            reader.readAsDataURL(file);
        },
        previewImageFromUrl: function () {
            const ImageLabel = document.getElementById('input-file-label');
            ImageLabel.style.backgroundImage = `url('${BASE_URL}${this.user.profile}')`;
        },
        updateUser: async function (e) {
            const ImageFile = document.getElementById("input-file").files[0];
            const form = new FormData(e.target);
            form.append('image', ImageFile);

            const response = await axios.post(`${API_URL}/auth/update`, form, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })

            this.$emit('refreshTweet');
        },
    },
    mounted: function () {
        this.previewImageFromUrl()
    }
}
</script>