import { HttpResponseData } from "./HttpResponseData";
import { http, prepareUrl } from "./http";
import { Settings } from "../components/settings/Settings.type";

class SettingsApi {

    static async getSettings({signal}) {
        const response = await http.get(prepareUrl(`/settings`), {signal});
        return new SettingsResponseData(response.data);
    }

    static async saveSettings(settings) {
        console.log({settings})
        const response = await http.post(prepareUrl(`/settings`), settings);
        return new SettingsResponseData(response.data);
    }
}


class SettingsResponseData extends HttpResponseData{
    constructor(data=null){
        super(data);
        this.result = new Settings(this.result);
    }
}

export { SettingsApi }
