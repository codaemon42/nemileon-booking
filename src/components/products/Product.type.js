export class Product {
    #default = {
        id: 0,
        key: 0,
        name: '',
        slug: '',
        status: '',
        featured: true,
        short_description: '',
        description: '',
        price: '',
        regular_price: '',
        sale_price: '',
        date_on_sale_from: '',
        date_on_sale_to: '',
        imageUrl: '',
        gallery_images: [],        
        rating_counts: [],
        average_rating: "0",
        review_count: 0,
        date_created: '',
    }

    constructor(data=null) {
        if(!data) data = this.#default;
        this.id = data?.id;
        this.key = data?.id;
        this.name = data?.name;
        this.slug = data?.slug;
        this.status = data?.status;
        this.featured = data?.featured;
        this.short_description = data?.short_description;
        this.description = data?.description;
        this.price = data?.price;
        this.regular_price = data?.regular_price;
        this.sale_price = data?.sale_price;
        this.date_on_sale_from = data?.date_on_sale_from;
        this.date_on_sale_to = data?.date_on_sale_to;
        this.imageUrl = data?.imageUrl;
        this.gallery_images = data?.gallery_images;
        this.rating_counts = data?.rating_counts;
        this.average_rating = data?.average_rating;
        this.review_count = data?.review_count;
        this.date_created = data?.date_created?.date;
    }


    static List(initialValue = []) {
            const arr = [new this()];
            arr.shift();
            if(initialValue && initialValue.length > 0) initialValue.map(v => arr.push(new this(v)));
            return arr;
    }
}