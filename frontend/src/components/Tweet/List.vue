<template lang="">
    <!-- Comment or Tweet List -->
    <div @click="$router.push({path:`/${usedData.tweet_id}`})" class="cursor-pointer grid grid-cols-[70px_auto] items-start gap-2 p-4 w-full border-b-2">
        <img class="aspect-square rounded-full" :src="`${BASE_URL}${usedData.user.profile}`" alt="">
        <div class="flex flex-col">
            <p class="text-xl flex items-center gap-1 text-placeholder ">
                <span class="font-bold text-black">
                    {{ usedData.user.username }}
                </span>
                <span class="text-sm">
                    @{{ usedData.user.username .toLowerCase().replace(' ', '') }} ·
                    {{ posted_at }}
                </span>
            </p>
            <p class="break-all">
                {{ usedData.content }}
            </p>
        </div>
    </div>
</template>
<script>
import { BASE_URL } from '../../constant';
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
    name: "List",
    data() {
        return {
            BASE_URL,
            posted_at: dateDifference(this.usedData.created_at  )
        }
    },
    props: ['usedData'],

}
</script>
<style lang="">
    
</style>