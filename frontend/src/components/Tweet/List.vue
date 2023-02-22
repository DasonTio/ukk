<template lang="">
    <div class="grid grid-cols-[70px_auto] items-start gap-2 p-4 m-4 border-b-2 ">
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
                {{ usedData.tweet ?? usedData.comment }}
            </p>
        </div>
    </div>
</template>
<script>
import { BASE_URL } from '../../constant';
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
            usedData: this.data.tweet ?? this.data.comment,
            posted_at: dateDifference(this.data.tweet.created_at ?? this.data.comment.created_at)
        }
    },
    props: ['data'],
    
}
</script>
<style lang="">
    
</style>