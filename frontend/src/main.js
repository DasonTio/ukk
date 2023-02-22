import { createApp } from "vue";
import App from "./App.vue";
import router from "./router";

import "./assets/main.css";

const vue = createApp(App);
/**
 * Global Function for Showing Message
 */
vue.mixin({
  methods: {
    popMessage: function (message, err = true) {
      let messageDOM = document.getElementById("vue-global-pop-message");
      messageDOM.innerHTML = message;
      messageDOM.classList.add("in");
      if(err) messageDOM.classList.add('err')
      setTimeout(() => {
        messageDOM.classList.remove("in");
      }, 2000);
    },
  },
});
vue.use(router);

vue.mount("#app");
