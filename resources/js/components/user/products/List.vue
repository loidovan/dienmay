<template>
    <div>
        <div class="container">
            <div class="row row-cols-5">
                <div class="col" v-for="item in products" :key="item.id">
                    <div class="img-item">
                        <img width="100%" :src="item.image">
                    </div>
                    <div>
                        <h6 style="font-weight: 600">{{ item.name }} {{ item.code }}</h6>
                        <h5 style="font-weight: bolder">{{ formatPrice(item.price) }}₫</h5>
                        <star-rating
                            :read-only="true"
                            :increment="0.01"
                            :star-size="13"
                            active-color="#FF8C00"
                            :show-rating="false"
                            v-model="rating"
                        ></star-rating>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            rating: 5,
            products: [
                {
                    id: 1,
                    name: 'Điện Máy Như Ý',
                    code: '',
                    price: 999999,
                    image: '/storage/images/logo.png'
                },
                {
                    id: 2,
                    name: 'Điện Máy Như Ý',
                    code: '',
                    price: 999999,
                    image: '/storage/images/logo.png'
                },
                {
                    id: 3,
                    name: 'Điện Máy Như Ý',
                    code: '',
                    price: 999999,
                    image: '/storage/images/logo.png'
                },
                {
                    id: 4,
                    name: 'Điện Máy Như Ý',
                    code: '',
                    price: 999999,
                    image: '/storage/images/logo.png'
                },
                {
                    id: 5,
                    name: 'Điện Máy Như Ý',
                    code: '',
                    price: 999999,
                    image: '/storage/images/logo.png'
                },
                {
                    id: 6,
                    name: 'Điện Máy Như Ý',
                    code: '',
                    price: 999999,
                    image: '/storage/images/logo.png'
                },
                {
                    id: 7,
                    name: 'Điện Máy Như Ý',
                    code: '',
                    price: 999999,
                    image: '/storage/images/logo.png'
                },
                {
                    id: 8,
                    name: 'Điện Máy Như Ý',
                    code: '',
                    price: 999999,
                    image: '/storage/images/logo.png'
                },
                {
                    id: 9,
                    name: 'Điện Máy Như Ý',
                    code: '',
                    price: 999999,
                    image: '/storage/images/logo.png'
                },
                {
                    id: 10,
                    name: 'Điện Máy Như Ý',
                    code: '',
                    price: 999999,
                    image: '/storage/images/logo.png'
                }
            ],
        }
    },
    created() {
        this.getProducts();
    },
    methods: {
        getProducts() {
            axios.get('/api/products').then(res => {
                this.products = res.data;
            });
        },
        formatPrice(value) {
            let val = (value / 1).toFixed(0).replace(".", ",");
            return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        },
    }
}
</script>
<style scoped>
    .container {
        min-width: 960px !important;
        padding-left: 22px !important;
        padding-right: 22px !important;
    }
    .row-cols-5 {
        border-left: 1px solid #f1f1f1;
        border-top: 1px solid #f1f1f1;
    }
    .col {
        height: 436px;
        border-right: 1px solid #f1f1f1;
        border-bottom: 1px solid #f1f1f1;
        padding-top: 12px;
        padding-bottom: 12px;
        display: grid;
    }
    .img-item {
        padding: 12px 6px;
        height: 200px;
    }
    .col:hover .img-item {
        padding-top: 4px !important;
        transition-duration: 0.4s;
        cursor: pointer;
    }
    .col:hover {
        box-shadow: 0 2px 16px rgb(0 0 0 / 12%);
        cursor: pointer;
        transition-duration: 0.2s;
    }
    .col:hover h6 {
        color: #288ad6;
    }
    @media only screen and (max-width: 1200px) {
        .col {
            height: 390px;
        }
        .img-item {
            height: 160px;
        }
    }
</style>