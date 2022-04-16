<template>
    <div class="header">
        <!-- Header -->
        <div class="header-top border-bottom">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-2 text-center border-right">Giới thiệu</div>
                            <div class="col-md-2 text-center border-right">Tin tức</div>
                            <div class="col-md-2 text-center">Liên hệ</div>
                        </div>
                    </div>
                 </div>
            </div>
        </div>

        <div class="header-mid container">
            <div class="row">
                <div class="col-md-6">
                    <router-link to="">
                        <img src="/storage/images/logo.png" alt="Logo" width="60px" height="60px"> <span style="font-size:21px;font-weight:700;color:#ed1c24">Điện Máy Như Ý</span>
                    </router-link>
                </div>
                <div class="col-md-5 d-flex align-items-center">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Nhập từ khóa tìm kiếm" aria-label="Recipient's username" aria-describedby="basic-addon2">
                        <div class="input-group-append">
                            <button class="btn" type="button"><i class="fas fa-search"> </i> </button>
                        </div>
                    </div>
                </div>
                <div class="col-md-1 d-flex align-items-center rou">
                    <span><i class="fas fa-user mr-2"></i></span>
                    <span><i class="fas fa-shopping-cart"></i></span>
                </div>
            </div>
        </div>
       
        <div class="header-bottom">
            <div class="container">
                <ul>
                    <li v-b-toggle.sidebar-category><a style="pointer-events: none;" href="">Tất cả danh mục | </a></li>
                    <b-sidebar id="sidebar-category" title="Danh Mục Sản Phẩm" shadow>
      <div class="px-3 py-2">
        <p>
          Cras mattis consectetur purus sit amet fermentum. Cras justo odio, dapibus ac facilisis
          in, egestas eget quam. Morbi leo risus, porta ac consectetur ac, vestibulum at eros.
        </p>
        <b-img src="https://picsum.photos/500/500/?image=54" fluid thumbnail></b-img>
      </div>
    </b-sidebar>
                    <li><a href="">Ti vi</a></li>
                    <li><a href="">Tủ lạnh</a></li>
                    <li><a href="">Máy lạnh</a></li>
                    <li><a href="">Máy giặt</a></li>
                    <li><a href="">Gia dụng</a></li>
                    <li><a href="">Máy nước nóng</a></li>
                    <li><a href="">Máy lọc nước</a></li>
                </ul>
            </div>
        </div>

        <!-- Slider -->
        <b-carousel
            id="carousel-fade"
            style="text-shadow: 0px 0px 2px #000"
            fade
            :interval="4000"
            indicators
            controls
            img-width="1024"
            img-height="480"
        >
            <b-carousel-slide
                img-src="/storage/images/banner/banner_dienmay2.png"
            ></b-carousel-slide>
            <b-carousel-slide
                img-src="/storage/images/banner/banner_dienmay1.png"
            ></b-carousel-slide>
            <b-carousel-slide
                img-src="/storage/images/banner/banner_dienmay3.png"
            ></b-carousel-slide>
            
        </b-carousel>
        <!-- END Slider -->
        
    </div>
</template>
<script>
export default {
    data() {
        return {

        }
    }
}
</script>
<style scoped>
    .header {
        background-color: white;
    }
    .header-top .col-md-2 {
        line-height: 38px;
    }
    .header-top .col-md-2:hover {
        background: #f2f2f2;
        cursor: pointer;
    }
    .header-mid .col-md-6 {
        line-height: 80px;
    }
    .header-mid .col-md-5 input {
        border-top-left-radius: 25px;
        border-bottom-left-radius: 25px;
    }
    .header-mid .col-md-5 button {
        border-top-right-radius: 25px;
        border-bottom-right-radius: 25px;
        background-color: #ed1c24;
        color: white;
    }
    .header-mid .col-md-5 button:hover {
        background-color: #ea0008be;
    }
    .header-mid .col-md-1 span {
        color: #ed1c24;
        font-size: 20px;
    }
    .header-mid .col-md-1 span:hover>i {
        color: #ea00089d;
        cursor: pointer;
    }
    .header-bottom {
        background-color: #ed1c24;
    }
    .header-bottom .container ul {
        list-style-type: none;
        margin: 0;
        padding: 0;
        overflow: hidden;
    }
    .header-bottom .container li {
        float: left;
    }
    .header-bottom .container li>a {
        display: block;
        color: white;
        text-align: center;
        padding: 14px 16px;
        text-decoration: none;
        font-weight: 600;
    }
    .header-bottom .container li>a:hover {
        background-color: rgba(0, 0, 0, .07)
    }
    
</style>