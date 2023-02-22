<template>
    <!-- Nav Bar -->
    <nav class="flex flex-col justify-between py-20 border-r-2 bg-white">
        <ul class="list-none flex flex-col items-center" style="gap:2rem;">
            <li class="grid grid-cols-[80px_auto] ">
                <i :class="(path == 'home' ? 'text-primary' : 'text-secondary') + ' fa-solid fa-house text-3xl'"></i>
                <RouterLink :to="{ name: 'home' }"
                    :class="(path == 'home' ? 'text-primary' : 'text-secondary') + '  text-2xl font-bold'">
                    Home
                </RouterLink>
            </li>
            <li class="grid grid-cols-[80px_auto] ">
                <i :class="(path == 'profile' ? 'text-primary' : 'text-secondary') + ' fa-solid fa-user text-3xl pl-1'"></i>
                <RouterLink :to="{ name: 'profile' }"
                    :class="(path == 'profile' ? 'text-primary' : 'text-secondary') + ' text-2xl font-bold'">Profile
                </RouterLink>
            </li>
        </ul>
        <div class="flex items-center justify-center">
            <button class="rounded-xl btn-primary w-16 h-16" @click="logout()">
                <i class="fa-solid fa-right-from-bracket text-2xl p-4"></i>
            </button>
        </div>
    </nav>
</template>
<script>

import axios from 'axios';
import { API_URL } from '../constant';
import { RouterLink } from 'vue-router';
export default {
    name: "NavBar",
    data() {
        return {
            path: this.$route.name
        }
    },
    methods: {
        /**
         * Function For Logout
         */
        logout: async function () {
            axios.defaults.headers.common['Authorization'] = localStorage.token
            const response = await axios.post(`${API_URL}/auth/logout`).catch(err=>this.popMessage('Something went wrong'))
            localStorage.clear();
            this.popMessage("Logout Success", false);
            this.$router.push({ name: 'login' });
        }
    }
}
</script>