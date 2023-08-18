import axios from "axios";

const baseURL = reactObj.ajax_url;

const ajax = axios.create({
    // withCredentials: true,
    // baseURL: baseURL
});

ajax.interceptors.request.use(request => {
    console.log({request});
    request.headers['Content-Type'] = 'application/x-www-form-urlencoded;';
    return request;
});
  
ajax.interceptors.response.use(
    response => {
    //   if(ENVIRONMENT.label === 'dev'){
        console.log({url: response.config.url, params: response.config.params, responseData: response.data});
    //   }
      return response;
    },
    errorInterceptor => console.log({errorInterceptor})
);

export { ajax };