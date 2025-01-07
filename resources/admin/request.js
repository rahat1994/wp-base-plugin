import { ref } from 'vue';

export async function useFetch(args = {}, url = '', method) {
    const data = ref(null);
    const error = ref(null);

    try {
        let param = {
            method: method,
        };
        if ( method !== 'GET') {
            param.body = args
        }
        const response = await fetch(url, param);
        const jsonData = await response.json();
        data.value = jsonData;
    } catch (err) {
        error.value = err;
    }
    return { data, error };
}

export async function $get(args, url = '') {
    args.action = 'wp_base_plugin';
    args.nonce = window.wpbaseplugin.nonce;
    const queryString = new URLSearchParams(args).toString();
    if (!url) {
        url = window.wpbaseplugin.ajaxurl + '?' + queryString;
    }

    return await useFetch('', url, 'GET');
}

export async function $post(args, url = '') {
    const requestData = new FormData();
    requestData.append('action', 'wp_base_plugin');
    requestData.append('nonce', window.wpbaseplugin.nonce);
    Object.keys(args).forEach(function (key) {
        requestData.append(key, args[key]);
    });
    if (url == '') {
        url = window.wpbaseplugin.ajax_url;
    }

    return await useFetch(requestData, url, 'POST');
}