<template>
    <div>
        <div class="container">
            <div class="row cols-md-12 mt-3 breadcrumb">
                <div class="col">
                    <router-link
                        class="mr-1"
                        :to="{
                            name: 'user.products',
                            query: { category_id: product.category_id },
                        }"
                        >{{ product.category.name }}
                    </router-link>
                    >
                    <router-link
                        class="ml-1"
                        :to="{
                            name: 'user.products',
                            query: {
                                category_id: product.category_id,
                                brand_id: product.brand_id,
                            },
                        }"
                    >
                        {{ product.category.name }}
                        {{ product.brand.name }}</router-link
                    >
                </div>
            </div>
            <div class="row cols-md-12">
                <div class="col">
                    <h5 class="font-weight-bold">
                        {{ product.name }} {{ product.code }}
                    </h5>
                </div>
            </div>
            <hr class="m-0 mt-1" />

            <div class="row mt-3">
                <div class="col-md-7">
                    <vueper-slides
                        class="no-shadow"
                        slide-multiple
                        :bullets="false"
                        :touchable="false"
                        fixedHeight="420px"
                    >
                        <vueper-slide>
                            <template v-slot:content>
                                <LazyYoutube
                                    maxWidth="100%"
                                    :src="product.post.path"
                                />
                            </template>
                        </vueper-slide>
                        <vueper-slide
                            v-for="slide in product.images"
                            :key="slide.id"
                        >
                            <template v-slot:content>
                                <img
                                    class="w-100"
                                    style="max-height: 420px"
                                    :src="`/storage/images/products/${slide.name}`"
                                />
                            </template>
                        </vueper-slide>
                    </vueper-slides>

                    <div class="policy mt-3">
                        <div class="row">
                            <div class="col-md-6 py-2">
                                <i class="fas fa-truck-loading"></i> Hư gì đổi
                                nấy 12 tháng tận nhà (miễn phí tháng đầu)
                            </div>
                            <div class="col-md-6 py-2">
                                <i class="fas fa-check-circle"></i> Bảo hành
                                chính hãng 2 năm, có người đến tận nhà
                            </div>
                        </div>
                        <hr class="m-0" />
                        <div class="row">
                            <div class="col-md-6 py-2">
                                <i class="fas fa-box"></i> Thùng có: Sách hướng
                                dẫn, phụ kiện
                            </div>
                            <div class="col-md-6 py-2">
                                <i class="fas fa-check-circle"></i> Bảo hành phụ
                                kiện 1 năm
                            </div>
                        </div>
                    </div>

                    <h4 class="mt-4 mb-4 font-weight-bold">
                        Thông tin sản phẩm
                    </h4>
                    <div
                        class="info-product"
                        :class="{ 'show-more': showInfoProduct }"
                        v-html="product.post.info_product"
                    ></div>
                    <div
                        class="row cols-md-12"
                        style="
                            padding-left: 12px;
                            padding-right: 12px;
                            height: 80px;
                        "
                    >
                        <div
                            class="col text-center"
                            style="box-shadow: 0px -40px 24px 10px #dee2e691"
                        >
                            <button
                                @click="showInfoProduct = false"
                                class="btn btn-outline-primary w-50 mt-2"
                            >
                                Xem thêm <i class="fas fa-angle-down"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-md-5 pl-3">
                    <h5>Giá</h5>
                    <h4 class="font-weight-bold text-danger">
                        {{ formatPrice(product.price) }}₫
                    </h4>
                    <img
                        width="100%"
                        src="/storage/images/banner/detail_product1.png"
                    />
                    <div
                        class="row mt-3 rounded"
                        style="padding-left: 12px; padding-right: 12px"
                    >
                        <div class="rounded border p-0">
                            <div
                                class="col-md-12 py-1 border-bottom"
                                style="
                                    background-color: #f6f6f6;
                                    height: 38px;
                                    display: flex;
                                    align-items: center;
                                "
                            >
                                <h6 class="font-weight-bold m-0">Khuyến mãi</h6>
                            </div>
                            <div class="col-md-12 py-2">
                                <ol class="p-2 m-0">
                                    <li>Miễn phí công lắp đặt</li>
                                    <li>Miễn phí công bảo trì</li>
                                    <li>Tặng bộ phụ kiện</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                    <button
                        class="btn w-100 font-weight-bold mt-3"
                        style="
                            background-color: #fb6e2e;
                            color: white;
                            height: 46px;
                        "
                        @click="setCookie()"
                    >
                        MUA NGAY
                    </button>
                    <h6 class="mt-3 text-center">
                        Gọi đặt mua <a href="tel:12345678">1234.5678</a> (7:30 -
                        22:00)
                    </h6>
                    <h5
                        style="font-size: 20px"
                        class="mt-5 mb-3 font-weight-bold"
                    >
                        Thông số kỹ thuật {{ product.name }} {{ product.code }}
                    </h5>
                    <div v-html="product.description"></div>
                    <div class="col-md-12 text-center">
                        <button class="btn btn-outline-primary mt-2">
                            Xem chi tiết thông số >
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="row mt-5">
                <div class="col-md-7 border rounded p-4 mr-3 ml-2">
                    <div>
                        <h3>Đánh giá {{ product.name }}</h3>
                        <div class="d-flex align-items-center">
                            <div
                                class="fs-3 fw-bold mr-3 mt-2"
                                style="color: #ff8c00"
                            >
                                {{ rate_avg.toFixed(1) }}
                            </div>
                            <star-rating
                                v-model="rate_avg"
                                :increment="0.01"
                                active-color="#FF8C00"
                                :show-rating="false"
                                :star-size="28"
                                :read-only="true"
                            ></star-rating>
                            <div style="color: #0071e3" class="mt-2 ml-3">
                                {{ totalReviews }} đánh giá
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex w-100 align-items-center">
                                5★
                                <b-progress :max="100" class="w-50 ml-2" wi>
                                    <b-progress-bar
                                        variant="warning"
                                        :value="rate_avg_5"
                                        :label="`${(
                                            (rate_avg_5 / 100) *
                                            100
                                        ).toFixed(0)}%`"
                                    ></b-progress-bar>
                                </b-progress>
                            </div>
                            <div class="d-flex w-100 align-items-center">
                                4★
                                <b-progress :max="100" class="w-50 ml-2">
                                    <b-progress-bar
                                        variant="warning"
                                        :value="rate_avg_4"
                                        :label="`${(
                                            (rate_avg_4 / 100) *
                                            100
                                        ).toFixed(0)}%`"
                                    ></b-progress-bar>
                                </b-progress>
                            </div>
                            <div class="d-flex w-100 align-items-center">
                                3★
                                <b-progress :max="100" class="w-50 ml-2">
                                    <b-progress-bar
                                        variant="warning"
                                        :value="rate_avg_3"
                                        :label="`${(
                                            (rate_avg_3 / 100) *
                                            100
                                        ).toFixed(0)}%`"
                                    ></b-progress-bar>
                                </b-progress>
                            </div>
                            <div class="d-flex w-100 align-items-center">
                                2★
                                <b-progress :max="100" class="w-50 ml-2">
                                    <b-progress-bar
                                        variant="warning"
                                        :value="rate_avg_2"
                                        :label="`${(
                                            (rate_avg_2 / 100) *
                                            100
                                        ).toFixed(0)}%`"
                                    ></b-progress-bar>
                                </b-progress>
                            </div>
                            <div class="d-flex w-100 align-items-center">
                                1★
                                <b-progress :max="100" class="w-50 ml-2">
                                    <b-progress-bar
                                        variant="warning"
                                        :value="rate_avg_1"
                                        :label="`${(
                                            (rate_avg_1 / 100) *
                                            100
                                        ).toFixed(0)}%`"
                                    ></b-progress-bar>
                                </b-progress>
                            </div>
                        </div>
                        <hr />
                        <div v-for="(review, index) in reviews" :key="index">
                            <div>
                                <p class="fw-bold m-0">
                                    {{ review.customer_name }}
                                    <span
                                        class="fw-normal ml-3"
                                        style="font-size: 10px"
                                        >{{ review.created_at }}</span
                                    >
                                </p>
                                <star-rating
                                    v-model="review.rating"
                                    :increment="0.01"
                                    active-color="#FF8C00"
                                    :show-rating="false"
                                    :star-size="13"
                                    :read-only="true"
                                ></star-rating>
                                <p>{{ review.content }}</p>
                                <div class="d-flex">
                                    <div
                                        class="mr-3"
                                        style="cursor: pointer"
                                        @click="
                                            handleLike('like_qty', review.id)
                                        "
                                    >
                                        <i class="fas fa-thumbs-up mr-1"></i
                                        >{{ review.like_qty }}
                                    </div>
                                    <div
                                        style="cursor: pointer"
                                        @click="
                                            handleLike('unlike_qty', review.id)
                                        "
                                    >
                                        <i class="fas fa-thumbs-down mr-1"></i
                                        >{{ review.unlike_qty }}
                                    </div>
                                </div>

                                <hr />
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="d-flex">
                            <b-button
                                variant="outline-warning"
                                class="w-50 mr-3"
                                @click="loadMoreReviews"
                                >Xem thêm</b-button
                            >
                            <b-button
                                variant="primary"
                                class="w-50"
                                v-b-modal.modal-rating
                                >Viết đánh giá</b-button
                            >
                        </div>

                        <b-modal
                            ref="modal-rating"
                            id="modal-rating"
                            :title="`Đánh giá ${product.name}`"
                        >
                            <b-form @submit="onSubmitRating">
                                <div
                                    class="w-100 d-flex justify-content-center flex-column align-items-center"
                                >
                                    <img :src="product.image" width="180px" />
                                    <star-rating
                                        v-model="rating"
                                        active-color="#FF8C00"
                                        :show-rating="false"
                                        :star-size="40"
                                    ></star-rating>

                                    <b-form-textarea
                                        class="mt-5"
                                        id="textarea"
                                        v-model="content_rating"
                                        placeholder="Mời bạn chia sẻ thêm cảm nhận..."
                                        rows="6"
                                        max-rows="6"
                                    ></b-form-textarea>

                                    <b-container fluid class="mt-4">
                                        <b-row>
                                            <b-col class="p-0 mr-2"
                                                ><b-form-input
                                                    placeholder="Họ tên (bắt buộc)"
                                                    v-model="customer_rating"
                                                    required
                                                ></b-form-input
                                            ></b-col>
                                            <b-col class="p-0"
                                                ><b-form-input
                                                    placeholder="Số điện thoại (bắt buộc)"
                                                    v-model="phone_rating"
                                                    required
                                                    type="number"
                                                ></b-form-input
                                            ></b-col>
                                        </b-row>
                                    </b-container>

                                    <b-button
                                        class="w-100 mt-4"
                                        variant="primary"
                                        type="submit"
                                    >
                                        Gửi đánh giá
                                    </b-button>
                                </div>
                            </b-form>
                            <template #modal-footer><div></div></template>
                        </b-modal>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="row mt-5">
                <div class="col-md-12">
                    <h4 class="font-weight-bold mb-4">
                        <router-link
                            tag="span"
                            :to="{
                                name: 'user.products',
                                query: { category_id: product.category_id },
                            }"
                            class="view-more-products"
                            >Xem thêm {{ product.category.name }}</router-link
                        >
                    </h4>
                    <vueper-slides
                        class="no-shadow"
                        :visible-slides="5"
                        slide-multiple
                        :slide-ratio="1 / 4"
                        :bullets="false"
                        :touchable="false"
                        fixedHeight="436px"
                    >
                        <vueper-slide
                            v-for="(slide, i) in otherProducts"
                            :key="slide.id"
                        >
                            <template v-slot:content>
                                <router-link
                                    tag="span"
                                    :to="`/products/${slide.id}`"
                                >
                                    <div
                                        v-if="[1, 3, 6, 8].includes(i)"
                                        class="h-100 border-top border-bottom cate"
                                    >
                                        <div class="item-img py-3">
                                            <img
                                                :src="slide.image"
                                                width="100%"
                                            />
                                        </div>
                                        <div>
                                            <h6 style="font-weight: 500">
                                                {{ slide.name }}
                                                {{ slide.code }}
                                            </h6>
                                            <h5 style="font-weight: bolder">
                                                {{ formatPrice(slide.price) }}₫
                                            </h5>
                                            <star-rating
                                                :read-only="true"
                                                :increment="0.01"
                                                :star-size="13"
                                                active-color="#FF8C00"
                                                :show-rating="false"
                                                v-model="slide.rating"
                                            ></star-rating>
                                        </div>
                                    </div>
                                    <div v-else class="h-100 border cate">
                                        <div class="item-img py-3">
                                            <img
                                                :src="slide.image"
                                                width="100%"
                                            />
                                        </div>
                                        <div>
                                            <h6 style="font-weight: 500">
                                                {{ slide.name }}
                                                {{ slide.code }}
                                            </h6>
                                            <h5 style="font-weight: bolder">
                                                {{ formatPrice(slide.price) }}₫
                                            </h5>
                                            <star-rating
                                                :read-only="true"
                                                :increment="0.01"
                                                :star-size="13"
                                                active-color="#FF8C00"
                                                :show-rating="false"
                                                v-model="slide.rating"
                                            ></star-rating>
                                        </div>
                                    </div>
                                </router-link>
                            </template>
                        </vueper-slide>
                    </vueper-slides>
                </div>
            </div>
        </div>

        <div class="container mb-5">
            <div class="row mt-4">
                <div class="col-md-7 border rounded p-4 mr-3 ml-2">
                    <h4 class="font-weight-bold mb-4">Bình luận</h4>

                    <div>
                        <b-button
                            variant="primary"
                            class="w-25 mb-4"
                            v-b-modal.modal-comment
                            >Tạo bình luận</b-button
                        >
                        <b-modal
                            ref="modal-comment"
                            id="modal-comment"
                            :title="`Bình luận về ${product.name}`"
                        >
                            <b-form @submit="onSubmitComment">
                                <div
                                    class="w-100 d-flex justify-content-center flex-column align-items-center"
                                >
                                    <b-form-textarea
                                        id="textarea"
                                        v-model="content_comment"
                                        placeholder="Mời bạn bình luận..."
                                        rows="6"
                                        max-rows="6"
                                    ></b-form-textarea>

                                    <b-container fluid class="mt-4">
                                        <b-row>
                                            <b-col class="p-0 mr-2"
                                                ><b-form-input
                                                    placeholder="Họ tên (bắt buộc)"
                                                    v-model="customer_comment"
                                                    required
                                                ></b-form-input
                                            ></b-col>
                                            <b-col class="p-0"
                                                ><b-form-input
                                                    placeholder="Số điện thoại (bắt buộc)"
                                                    v-model="phone_comment"
                                                    required
                                                    type="number"
                                                ></b-form-input
                                            ></b-col>
                                        </b-row>
                                    </b-container>

                                    <b-button
                                        class="w-100 mt-4"
                                        variant="primary"
                                        type="submit"
                                    >
                                        Gửi bình luận
                                    </b-button>
                                </div>
                            </b-form>
                            <template #modal-footer><div></div></template>
                        </b-modal>
                    </div>

                    <div v-for="(comment, index) in comments" :key="index">
                        <div>
                            <p class="fw-bold m-0">
                                {{ comment.customer_name }}
                            </p>
                            <div>
                                {{ comment.content }}
                            </div>
                            <span style="color: #8f9bb3; font-size: 12px">{{
                                comment.created_at
                            }}</span>

                            <div
                                class="p-3 mt-2 position-relative"
                                style="
                                    background-color: #f7f7f7;
                                    border-radius: 10px;
                                "
                                v-if="comment.comment_details.length"
                            >
                                <div
                                    style="
                                        width: 0px;
                                        height: 0px;
                                        border-left: 12px solid transparent;
                                        border-right: 12px solid transparent;
                                        border-bottom: 10px solid #f7f7f7;
                                        position: absolute;
                                        top: -10px;
                                        left: 16px;
                                    "
                                ></div>
                                <p class="fw-bold m-0">
                                    {{ comment.comment_details[0].user.name }}
                                    <span
                                        style="
                                            margin-left: 5px;
                                            background-color: #eebc49;
                                            font-size: 10px;
                                            padding: 3px 5px;
                                            border-radius: 2px;
                                        "
                                        >QTV</span
                                    >
                                </p>
                                <div>
                                    {{ comment.comment_details[0].content }}
                                </div>
                                <span style="color: #8f9bb3; font-size: 12px">{{
                                    comment.comment_details[0].created_at
                                }}</span>
                            </div>
                            <hr />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <loading
            :active.sync="showLoading"
            :can-cancel="true"
            :is-full-page="true"
        ></loading>
    </div>
</template>
<script>
import { VueperSlides, VueperSlide } from "vueperslides";
import "vueperslides/dist/vueperslides.css";
import { LazyYoutube } from "vue-lazytube";
import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/vue-loading.css";

export default {
    components: {
        VueperSlides,
        VueperSlide,
        LazyYoutube,
        Loading,
    },
    data() {
        return {
            product: {
                category_id: "",
                name: "",
                code: "",
                brand: {
                    name: "",
                },
                post: {
                    info_product: "",
                    path: "https://www.youtube.com/watch?v=qeUfDaYAcsg",
                },
                price: 0,
                description: "",
                category: {
                    name: "",
                },
            },
            showLoading: true,
            showInfoProduct: true,
            otherProducts: [
                {
                    name: "Tivi",
                    price: "0",
                    category_id: 0,
                },
            ],
            reviews: [],
            moreReviews: [],
            totalReviews: 0,
            rating: 0,
            content_rating: "",
            customer_rating: "",
            phone_rating: "",
            rate_avg: 5,
            rate_avg_5: 0,
            rate_avg_4: 0,
            rate_avg_3: 0,
            rate_avg_2: 0,
            rate_avg_1: 0,

            content_comment: "",
            customer_comment: "",
            phone_comment: "",
            comments: [],
            moreComments: [],
        };
    },
    created() {
        this.getCurrentProduct();
        this.getOtherProducts();
        this.getReviews();
        this.getComments();
    },
    methods: {
        async getCurrentProduct() {
            await axios
                .get("/api/products/" + this.$route.params.id)
                .then((res) => {
                    this.product = res.data;
                    this.showLoading = false;
                });
        },
        formatPrice(value) {
            let val = (value / 1).toFixed(0).replace(".", ",");
            return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        },
        async getOtherProducts() {
            await axios.get("/api/products").then((res) => {
                this.otherProducts = res.data
                    .filter((item) => {
                        return (
                            item.category_id == this.product.category_id &&
                            item.id != this.product.id
                        );
                    })
                    .slice(0, 10);
            });
        },
        async getReviews() {
            await axios
                .get("/api/reviews/" + this.$route.params.id)
                .then((res) => {
                    this.moreReviews = res.data;
                    this.totalReviews = res.data?.length || 0;

                    var total = 0;
                    var count = 0,
                        count_5 = 0,
                        count_4 = 0,
                        count_3 = 0,
                        count_2 = 0,
                        count_1 = 0;

                    res?.data?.map((item) => {
                        total += Number(item.rating);
                        count++;
                        switch (item.rating) {
                            case 5:
                                count_5++;
                                break;
                            case 4:
                                count_4++;
                                break;
                            case 3:
                                count_3++;
                                break;
                            case 2:
                                count_2++;
                                break;
                            case 1:
                                count_1++;
                                break;
                            default:
                                break;
                        }
                    });
                    this.rate_avg = total / count || 0;
                    this.rate_avg_5 = (count_5 / count) * 100;
                    this.rate_avg_4 = (count_4 / count) * 100;
                    this.rate_avg_3 = (count_3 / count) * 100;
                    this.rate_avg_2 = (count_2 / count) * 100;
                    this.rate_avg_1 = (count_1 / count) * 100;

                    this.reviews = this.moreReviews.splice(0, 2);
                });
        },
        loadMoreReviews() {
            if (this.moreReviews.length > 0) {
                this.showLoading = true;
                setTimeout(() => {
                    this.showLoading = false;
                }, 400);
            }
            var next = this.moreReviews.splice(0, 2);
            this.reviews.push(...next);
        },
        async getComments() {
            await axios
                .get("/api/comments/" + this.$route.params.id)
                .then((res) => {
                    this.moreComments = res.data;

                    this.comments = this.moreComments.splice(0, 5);
                });
        },
        loadMoreComments() {
            if (this.moreComments.length > 0) {
                this.showLoading = true;
                setTimeout(() => {
                    this.showLoading = false;
                }, 400);
            }
            var next = this.moreComments.splice(0, 2);
            this.comments.push(...next);
        },
        setCookie() {
            if (this.checkExistCookie()) {
                axios
                    .post("/api/carts", {
                        product_id: this.product.id,
                        cart_id: this.getCookie("DienMay_cart_id"),
                    })
                    .then((res) => {
                        this.$router.push({
                            name: "user.cart",
                        });
                    })
                    .catch((error) => {
                        console.log(error);
                        this.$swal({
                            icon: "error",
                            title: "Lỗi",
                            text: "Sản phẩm hết hàng, xin mời chọn sản phẩm khác!",
                        });
                    });
            } else {
                const d = new Date();
                d.setTime(d.getTime() + 365 * 24 * 60 * 60 * 1000); // 365 ngày
                let expires = "expires=" + d.toUTCString();
                let randomId = this.makeid(16);
                document.cookie =
                    "DienMay_cart_id" +
                    "=" +
                    randomId +
                    ";" +
                    expires +
                    ";path=/";
                axios
                    .post("/api/carts", {
                        product_id: this.product.id,
                        cart_id: randomId,
                    })
                    .then((res) => {
                        this.$router.push({
                            name: "user.cart",
                        });
                    })
                    .catch((error) => {
                        console.log(error);
                        this.$swal({
                            icon: "error",
                            title: "Lỗi",
                            text: "Sản phẩm hết hàng, xin mời chọn sản phẩm khác!",
                        });
                    });
            }
        },
        makeid(length) {
            var result = "";
            var characters =
                "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
            var charactersLength = characters.length;
            for (var i = 0; i < length; i++) {
                result += characters.charAt(
                    Math.floor(Math.random() * charactersLength)
                );
            }
            return result;
        },
        checkExistCookie() {
            let cart = this.getCookie("DienMay_cart_id");
            if (cart != "") {
                return true;
            } else {
                return false;
            }
        },
        getCookie(cookieName) {
            let name = cookieName + "=";
            let decodedCookie = decodeURIComponent(document.cookie);
            let ca = decodedCookie.split(";");
            for (let i = 0; i < ca.length; i++) {
                let c = ca[i];
                while (c.charAt(0) == " ") {
                    c = c.substring(1);
                }
                if (c.indexOf(name) == 0) {
                    return c.substring(name.length, c.length);
                }
            }
            return "";
        },
        onSubmitRating(e) {
            e.preventDefault();
            axios
                .post("/api/reviews", {
                    product_id: this.product.id,
                    rating: this.rating,
                    content: this.content_rating,
                    customer_name: this.customer_rating,
                    customer_phone: this.phone_rating,
                })
                .then((res) => {})
                .catch((error) => {
                    console.log(error);
                });
            this.$refs["modal-rating"].hide();
            this.$swal({
                icon: "success",
                title: "Thành công",
                text: "Đánh giá đang chờ quản trị viên phê duyệt!",
            });
        },
        onSubmitComment(e) {
            e.preventDefault();
            axios
                .post("/api/comments", {
                    product_id: this.product.id,
                    content: this.content_comment,
                    customer_name: this.customer_comment,
                    customer_phone: this.phone_comment,
                })
                .then((res) => {})
                .catch((error) => {
                    console.log(error);
                });
            this.$refs["modal-comment"].hide();
            this.$swal({
                icon: "success",
                title: "Thành công",
                text: "Bình luận đang chờ quản trị viên phê duyệt!",
            });
        },
        handleLike(string = "like_qty" | "unlike_qty", reviewId) {
            this.showLoading = true;
            axios
                .put("/api/reviews/" + reviewId, {
                    type: string,
                })
                .then((res) => {
                    var review = this.reviews.find(
                        (item) => item.id === reviewId
                    );
                    if (review) {
                        review[string] = review[string] + 1;
                    }
                    this.showLoading = false;
                })
                .catch((error) => {
                    this.showLoading = false;
                    console.log(error);
                });
        },
    },
    watch: {
        $route() {
            window.scrollTo(0, 0);
            this.getCurrentProduct();
            this.getOtherProducts();
            this.getReviews();
        },
    },
};
</script>
<style scoped>
.breadcrumb {
    font-size: 14px;
    font-weight: bolder;
    background-color: white;
}
.policy {
    font-size: 16px;
    padding: 0 8px;
}
.policy i {
    font-size: 20px;
    color: #2f80ed;
}
hr {
    opacity: 0.15;
}
.show-more {
    height: 1000px;
    overflow: hidden;
}
ol {
    list-style: none;
    counter-reset: item;
}
ol li {
    counter-increment: item;
    margin-bottom: 5px;
}
ol li::before {
    margin-right: 10px;
    content: counter(item);
    background: #4a90e2;
    border-radius: 100%;
    color: white;
    width: 1.3em;
    text-align: center;
    display: inline-block;
    font-size: 12px;
}
.h-100.cate {
    padding: 10px 15px 20px;
    display: grid;
}
.h-100.cate:hover {
    box-shadow: 0 2px 16px rgb(0 0 0 / 12%);
    cursor: pointer;
    transition-duration: 0.2s;
}
.h-100.cate:hover .item-img {
    padding-top: 0.5rem !important;
}
.h-100.cate:hover h6 {
    color: #288ad6;
}
.item-img {
    height: 189px;
    transition: 0.3s ease-in-out;
}
.view-more-products:hover {
    color: #288ad6;
    transition-duration: 0.2s;
    cursor: pointer;
}
</style>
