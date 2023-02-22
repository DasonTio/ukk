<template>
    <main class="flex justify-center items-center w-1/1 h-[100vh]">
        <form @submit.prevent="submitForm($event)" class="w-1/5 h-fit flex flex-col gap-1 text-center">
            <div class="header">
                <h1 class="text-3xl font-extrabold">Hello Again 👋</h1>
                <p class="text-1xl font-normal ">Welcome back, you’ve been missed</p>
            </div>
            <div class="grid gap-y-2">
                <InputBox name="Email" type="email" id="input-email" />
                <InputBox name="Password" type="password" id="input-pass" />
            </div>
            <Button name="Sign In" type="submit" />
            <p class="text-sm">
                Don’t have account yet?
                <RouterLink :to="{ name: 'register' }" class="font-bold">Sign Up</RouterLink>
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
    components: {
        InputBox,
        Button
    },
    methods: {
        submitForm: async function (event) {
            const form = new FormData(event.target);
            const data = Object.fromEntries(form.entries());
            const response = await axios.post(`${API_URL}/auth/login`, data).catch((err) => {
                this.popMessage("Login Failed");
            })

            const responseData = response.data.data;
            if (response.status == 200) {
                this.popMessage("Login Success", false);
                localStorage.setItem('token', responseData.remember_token);
                localStorage.setItem('user_id', responseData.id);
                this.$router.push({ name: 'home' });
            } else { alert(response.data.mesesage) }
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
</style>