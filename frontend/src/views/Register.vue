<template>
    <main class="flex justify-center items-center w-1/1 h-[100vh]">
        <form @submit.prevent="submitForm($event)" class="w-1/5 h-fit flex flex-col gap-1 text-center">
            <div class="header">
                <h1 class="text-3xl font-extrabold">Hi, Welcome 👋</h1>
                <p class="text-1xl font-normal ">Be ready for you first tweet, it’ll be fun</p>
            </div>

            <div v-show="!toggle" class="grid gap-y-2">
                <InputBox name="Email" type="email" id="input-email" />
                <InputBox name="Password" type="password" id="input-pass" />
                <InputBox name="Confirm Password" type="password" id="input-confirmpass" />
            </div>
            <button @click="toggleRegisterProfile()" v-show="!toggle" class="rounded-full w-full p-5 text-white font-medium"
                type="button" style="background-color: #66A4FF;">Sign Up</button>


            <div v-show="toggle" class="grid gap-y-2">
                <div class="inputbox-file">
                    <input @change="previewImage($event)" type="file" name="profile" accept="image/*" id="input-file"
                        hidden>
                    <label for="input-file" class="flex py-20 items-center justify-center border-2 rounded-md bg-cover"
                        id="input-file-label">
                        <i class="fa-solid fa-image "></i></label>
                </div>
                <InputBox name="Username" type="text" id="input-name" />
            </div>
            <Button v-show="toggle" name="Complete" type="submit" />

            <p class="text-sm">
                Already have an account? Sign In
                <RouterLink :to="{ name: 'login' }" class="font-bold">Sign In</RouterLink>
            </p>
        </form>
    </main>
</template>
<script>
import InputBox from '../components/InputBox.vue';
import Button from '../components/Button.vue';
import { RouterLink } from 'vue-router';
import { API_URL } from '../constant';
import axios from 'axios';

export default {
    name: "Login",
    data() {
        return {
            toggle: false
        }
    },
    components: {
        InputBox,
        Button
    },
    methods: {
        /**
         * Checking the password and confirm password 
         * if (its the same) then it'll continue the process 
         * else it'll pop a Message and user need to change the data
         */
        toggleRegisterProfile: function () {
            const pass = document.getElementById('input-pass').value;
            const confirmPass = document.getElementById('input-confirmpass').value;
            if (pass != confirmPass) {
                this.popMessage("Password isn't the same");
            } else this.toggle = true

        },
        previewImage: function (e) {
            const ImageLabel = document.getElementById('input-file-label');
            const file = e.target.files[0];
            const reader = new FileReader();
            reader.onload = (e) => {
                ImageLabel.style.backgroundImage = `url('${e.target.result}')`;
                ImageLabel.innerHTML = ""
            }
            reader.readAsDataURL(file);
        },
        submitForm: async function (event) {
            const image = document.getElementById('input-file').files[0];
            const form = new FormData(event.target);
            form.append("image", image);

            this.popMessage("Completed", false)
            const response = await axios.post(`${API_URL}/auth/register`, form, {
                headers: {
                    "Content-Type": "multipart/form-data"
                },
            }).catch((err) => {
                this.popMessage(err)
            });

            const responseData = response.data.data;
            if (response.status == 200) {
                localStorage.setItem('token', responseData.remember_token);
                localStorage.setItem('user_id', responseData.id);
                this.$router.push({ name: 'home' });
            }
        }
    }
}
</script>
<style scoped>
main {
    background: #FFFFFF;
}

.gap-1 {
    gap: 2rem;
}

p {
    color: #A0A0A0;
}

input,
label {
    background-color: #F3F7F9;
}

input::placeholder,
label {
    color: #B3BABD;
}
</style>