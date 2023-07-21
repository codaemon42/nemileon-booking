import axios from "axios";

const baseURL = "http://localhost/sports/wp-json";
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
    return request;
});
  
http.interceptors.response.use(
    response => {
    //   if(ENVIRONMENT.label === 'dev'){
        console.log({url: response.config.url, params: response.config.params, responseData: response.data});
    //   }
      return response;
    },
    errorInterceptor => console.log({errorInterceptor})
);

export { http, prepareUrl };