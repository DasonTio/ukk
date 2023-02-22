<template lang="">
    <form class="grid grid-cols-[50px_auto] items-start gap-2 p-4 m-4">
        <img class="rounded-full aspect-square" :src="`${BASE_URL}${user.profile}`" alt="">
        <input type="text" name="_method" :value="action" hidden />
        <div class="flex flex-col w-full gap-y-2">
            <textarea class="border-none bg-transparent resize-none w-full p-3" placeholder="Reply the tweet"
                rows="2" name="comment" id="comment">{{data.comment}}</textarea>
            <div class="flex justify-end">
                <button type="submit" class="rounded-full py-2 px-6 btn-primary-outline">Update</button>
            </div>
        </div>
    </form>
</template>
<script>
import { BASE_URL } from '../../constant';
export default {
    name: "TweetCommentUpdate",
    data() {
        return {
            BASE_URL
        }
    },
    props: ['user', 'data', 'action'],
}
</script>