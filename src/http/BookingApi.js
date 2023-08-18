import { ajax } from "./ajax";
import { HttpResponseData } from "./HttpResponseData";

class BookingApi {

    static async createBooking({productId, slots}){
        let body = {
            action: 'onsbks_cart_action',
            _wpnonce: reactObj.nonce,
            product_id: productId || 0,
            slots: slots || "test"
        }
        const bookingRes = await ajax.post(reactObj.ajax_url, {...body});
        return new HttpResponseData(bookingRes.data);
    }
}


// class ProductResponseData extends HttpResponseData {
//     constructor(data=null){
//         super(data);
//         this.result = Product.List(this.result);
//     }
// }

export { BookingApi }
