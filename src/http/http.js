import axios from "axios";

const baseURL = `${reactObj.base_url}/wp-json`;
const sUrl = "/onsbks/v2";

const prepareUrl = (params) =>  sUrl + params;

const http = axios.create({
    withCredentials: true,
    baseURL: baseURL
});

http.interceptors.request.use(request => {
    // add auth header with jwt if account is logged in and request is to the api url
    // const token = localStorage.getItem("Authorization");
    const token = reactObj.jwt;
    console.log({request});
    // if (request.url.startsWith('/api') && token) {
        // request.headers.common.Authorization = `Bearer ${token}`;
    // }
    request.headers['jwt'] = `${token}`;

    if(request.url.includes('bookings') || request.url.includes('tickets/find')){
        request.headers['fingerprint'] = `${localStorage.getItem('fingerprint')}`;
    }

    return request;
});
  
http.interceptors.response.use(
    response => {
    //   if(ENVIRONMENT.label === 'dev'){
        console.log({url: response.config.url, params: response.config.params, responseData: response.data});
    //   }
      return response;
    },
    errorInterceptor => {
        errorInterceptor.response.data.success = false;
        console.log({errorInterceptor})
        if(errorInterceptor.response.status === 401){
            errorInterceptor.response.data.result = null;
        }
        return errorInterceptor.response;
    }
);

export { http, prepareUrl };