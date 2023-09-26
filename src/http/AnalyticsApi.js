import { HttpResponseData } from "./HttpResponseData";
import { http, prepareUrl } from "./http";
import { Analytics } from "../components/charts/Analytics.type";

class AnalyticsApi {

    static async getBookingsAnalyticsByDate({signal}) {
        const analyticsResponse = await http.get(prepareUrl(`/analytics`, {signal}));
        return new AnalyticsResponseData(analyticsResponse.data);
    }

    static async getBookingsAnalyticsByDateAndStatus({signal}) {
        const analyticsResponse = await http.get(prepareUrl(`/analytics-status`, {signal}));
        return new AnalyticsResponseData(analyticsResponse.data);
    }
}


class AnalyticsResponseData extends HttpResponseData{
    constructor(data=null){
        super(data);
        this.result = Analytics.List(this.result);
    }
}

export { AnalyticsApi }
