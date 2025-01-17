import { ref } from "vue";

export async function useFetch(args = {}, url = "", method) {
    const data = ref(null);
    const error = ref(null);

    try {
        let param = {
            method: method,
        };
        if (method !== "GET") {
            param.body = args;
        }
        const response = await fetch(url, param);
        const jsonData = await response.json();
        data.value = jsonData;
    } catch (err) {
        error.value = err;
    }
    return { data, error };
}

export async function $get(args, url = "") {
    args.action = "wp_base_plugin_frontend";
    args.nonce = window.wpBasePluginFrontend.nonce;
    const queryString = new URLSearchParams(args).toString();
    if (!url) {
        url = window.wpBasePluginFrontend.ajaxurl + "?" + queryString;
    }

    return await useFetch("", url, "GET");
}

export async function $post(args, url = "") {
    const requestData = new FormData();
    requestData.append("action", "wp_base_plugin_frontend");
    requestData.append("nonce", window.wpBasePluginFrontend.nonce);
    Object.keys(args).forEach(function (key) {
        requestData.append(key, args[key]);
    });
    if (url == "") {
        url = window.wpBasePluginFrontend.ajaxurl;
    }

    return await useFetch(requestData, url, "POST");
}
