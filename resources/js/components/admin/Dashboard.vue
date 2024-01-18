<template>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Tổng Quan</h1>
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">
                                <a href="#">Tổng Quan</a>
                            </li>
                        </ol>
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Small boxes (Stat box) -->
                <div class="row">
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>{{ categories.length }}</h3>

                                <p>Danh Mục Sản Phẩm</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-bag"></i>
                            </div>
                            <router-link
                                :to="{ name: 'categories' }"
                                tag="a"
                                class="small-box-footer"
                                >Xem chi tiết
                                <i class="fas fa-arrow-circle-right"></i
                            ></router-link>
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3>{{ products.length }}</h3>

                                <p>Sản Phẩm</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-stats-bars"></i>
                            </div>
                            <router-link
                                :to="{ name: 'products' }"
                                tag="a"
                                class="small-box-footer"
                                >Xem chi tiết
                                <i class="fas fa-arrow-circle-right"></i
                            ></router-link>
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3>{{ orders.length }}</h3>

                                <p>Đơn đặt hàng</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-person-add"></i>
                            </div>
                            <router-link
                                :to="{ name: 'orders' }"
                                tag="a"
                                class="small-box-footer"
                                >Xem chi tiết
                                <i class="fas fa-arrow-circle-right"></i
                            ></router-link>
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-danger">
                            <div class="inner">
                                <h3>{{ customers.length }}</h3>

                                <p>Khách hàng</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-pie-graph"></i>
                            </div>
                            <a href="#" class="small-box-footer"
                                >Xem chi tiết
                                <i class="fas fa-arrow-circle-right"></i
                            ></a>
                        </div>
                    </div>
                    <!-- ./col -->
                </div>
                <!-- /.row -->
                <!-- Main row -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mt-2">
                                    Biểu đồ cột thống kê doanh thu
                                </h5>
                            </div>
                            <div class="card-body">
                                <BarChart />
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mt-2">
                                    Biểu đồ đường thống kê doanh thu
                                </h5>
                            </div>
                            <div class="card-body">
                                <LineChart />
                            </div>
                        </div>
                    </div>
                    <!-- right col -->
                </div>
                <!-- /.row (main row) -->
            </div>
            <!-- /.container-fluid -->
        </section>
        <!-- /.content -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title mt-3">
                                    Thống Kê Tồn Kho Sản Phẩm
                                </h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body" style="overflow-x: auto">
                                <b-row class="mb-3">
                                    <b-col>
                                        <b-form-select
                                            id="per-page-select"
                                            style="max-width: 100px"
                                            v-model="perPage"
                                            :options="pageOptions"
                                            v-b-tooltip.hover.v-secondary="
                                                'Số bản ghi trên một trang'
                                            "
                                        ></b-form-select>
                                        <span>
                                            <export-excel
                                                :data="json_data"
                                                class="btn btn-success"
                                                name="Danh sách.xls"
                                                id="export-excel"
                                                v-b-tooltip.hover.v-secondary="
                                                    'Xuất Excel các bản ghi đã chọn'
                                                "
                                            >
                                                <i class="fa fa-file-excel"></i>
                                            </export-excel>
                                        </span>
                                        <span>
                                            <button
                                                class="btn btn-warning"
                                                v-b-tooltip.hover.v-secondary="
                                                    'Xuất PDF các bản ghi đã chọn'
                                                "
                                            >
                                                <i
                                                    class="fas fa-file-pdf"
                                                    @click="exportPdf"
                                                ></i>
                                            </button>
                                        </span>
                                    </b-col>
                                    <b-col md="4" class="my-1">
                                        <b-form-group
                                            label-align-sm="right"
                                            label-size="sm"
                                            class="mb-0 mb-2"
                                            v-slot="{ ariaDescribedby }"
                                        >
                                            <b-form-checkbox-group
                                                v-model="filterOn"
                                                :aria-describedby="
                                                    ariaDescribedby
                                                "
                                                class="mt-1"
                                            >
                                                <b-form-checkbox
                                                    value="quantityTotal"
                                                    >Số lượng</b-form-checkbox
                                                >
                                                <b-form-checkbox
                                                    value="quantitySold"
                                                    >Đã bán</b-form-checkbox
                                                >
                                                <b-form-checkbox
                                                    value="remaining"
                                                    >Tồn kho</b-form-checkbox
                                                >
                                            </b-form-checkbox-group>
                                        </b-form-group>
                                        <b-form-group>
                                            <b-input-group>
                                                <b-form-input
                                                    id="filter-input"
                                                    v-model="filter"
                                                    type="search"
                                                    placeholder="Nhập từ khóa tìm kiếm"
                                                ></b-form-input>
                                            </b-input-group>
                                        </b-form-group>
                                    </b-col>
                                </b-row>
                                <b-table
                                    :items="inventory"
                                    :fields="fields"
                                    :per-page="perPage"
                                    :current-page="currentPage"
                                    :filter-included-fields="filterOn"
                                    :filter="filter"
                                    @filtered="onFiltered"
                                    id="table"
                                >
                                    <template #head(checkbox)="">
                                        <b-form-checkbox
                                            @change="selectAll"
                                            v-model="allSelected"
                                            v-b-tooltip.hover.v-secondary
                                            title="Chọn tất cả"
                                        ></b-form-checkbox>
                                    </template>
                                    <template #cell(checkbox)="row">
                                        <b-form-group>
                                            <b-form-checkbox
                                                v-model="selected"
                                                :value="row.item.id"
                                                @change="selectOne"
                                            ></b-form-checkbox>
                                        </b-form-group>
                                    </template>

                                    <template #cell(image)="row">
                                        <div>
                                            <center>
                                                <span>
                                                    <img
                                                        style="
                                                            border-radius: 5px;
                                                            width: 100px;
                                                            height: 80px;
                                                            box-shadow: 0px 0px
                                                                10px #ccc;
                                                        "
                                                        :src="row.item.image"
                                                    />
                                                </span>
                                            </center>
                                        </div>
                                    </template>
                                </b-table>
                                <b-row class="mt-3">
                                    <b-col md="8"></b-col>
                                    <b-col md="4">
                                        <b-pagination
                                            v-model="currentPage"
                                            :total-rows="totalRows"
                                            :per-page="perPage"
                                            align="fill"
                                            class="my-0"
                                        ></b-pagination>
                                    </b-col>
                                </b-row>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
</template>

<script>
import BarChart from "./charts/Bar.vue";
import LineChart from "./charts/Line.vue";
import html2PDF from "jspdf-html2canvas";
export default {
    components: {
        BarChart,
        LineChart,
    },
    data() {
        return {
            categories: [],
            products: [],
            orders: [],
            customers: [],

            inventory: [],
            selected: [],
            allSelected: false,
            totalRows: 1,
            currentPage: 1,
            perPage: 10,
            pageOptions: [10, 25, 50, 100, 500],
            filter: null,
            filterOn: [],
            fields: [
                {
                    key: "checkbox",
                    class: "text-center align-middle",
                    thClass: "mt-5",
                    sortable: false,
                },
                {
                    key: "stt",
                    label: "STT",
                    class: "text-center align-middle",
                    sortable: true,
                },
                {
                    key: "code",
                    label: "Mã",
                    class: "text-center align-middle",
                    sortable: true,
                },
                {
                    key: "name",
                    label: "Tên",
                    class: "text-center align-middle",
                    sortable: true,
                },
                {
                    key: "image",
                    label: "Ảnh",
                    class: "text-center align-middle",
                    sortable: true,
                },
                {
                    key: "quantityTotal",
                    label: "Số lượng",
                    class: "text-center align-middle",
                    sortable: true,
                },
                {
                    key: "quantitySold",
                    label: "Số lượng bán",
                    class: "text-center align-middle",
                    sortable: true,
                },
                {
                    key: "remaining",
                    label: "Tồn kho",
                    class: "text-center align-middle",
                    sortable: true,
                },
            ],
            json_data: [],
            json_meta: [
                [
                    {
                        key: "charset",
                        value: "utf-8",
                    },
                ],
            ],
            pdf_data: [],
        };
    },
    created() {
        this.getCategories();
        this.getProducts();
        this.getOrders();
        this.getCustomers();
        this.getInventory();
    },
    methods: {
        getCategories() {
            axios
                .get("/api/categories")
                .then((response) => {
                    this.categories = response.data;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        getProducts() {
            axios
                .get("/api/products")
                .then((response) => {
                    this.products = response.data;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        getOrders() {
            axios
                .get("/api/orders")
                .then((response) => {
                    this.orders = response.data;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        getCustomers() {
            axios
                .get("/api/customers")
                .then((response) => {
                    this.customers = response.data;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        getInventory() {
            axios
                .get("/api/getInventory")
                .then((response) => {
                    this.inventory = response.data;
                    this.totalRows = this.inventory.length;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        selectAll(checked) {
            this.selected = checked
                ? this.inventory.map((item) => item.id)
                : [];
            this.json_data = checked
                ? this.inventory.map((item, index) => {
                      return {
                          STT: index + 1,
                          "Mã SP": item.code,
                          "Tên SP": item.name,
                          "Số lượng": item.quantityTotal,
                          "Số lượng bán": item.quantitySold,
                          "Tồn kho": item.remaining,
                      };
                  })
                : [];
        },
        selectOne(checked) {
            if (checked) {
                this.json_data = this.inventory
                    .filter((item) => this.selected.includes(item.id))
                    .map((item, index) => {
                        return {
                            STT: index + 1,
                            "Mã SP": item.code,
                            "Tên SP": item.name,
                            "Số lượng": item.quantityTotal,
                            "Số lượng bán": item.quantitySold,
                            "Tồn kho": item.remaining,
                        };
                    });
            }
            this.allSelected = this.selected.length === this.inventory.length;
        },
        exportPdf() {
            let page = document.getElementById("table");
            // Getting the rows in table.
            var row = page.rows;

            // index các cột cần ẩn
            var i = 0,
                k = 9;
            for (var j = 0; j < row.length; j++) {
                var cols = row[j].cells;
                // ẩn đi
                // cols[i].style.display = "none";
                // cols[k].style.display = "none";
            }
            html2PDF(page, {
                jsPDF: {
                    format: "a4",
                },
                imageType: "image/jpeg",
                output: "Danh sách.pdf",
            });
            for (var j = 0; j < row.length; j++) {
                var cols = row[j].cells;
                // hiển thị lại
                // cols[i].style.display = "table-cell";
                // cols[k].style.display = "table-cell";
            }
        },
        onFiltered(filteredItems) {
            // Trigger pagination to update the number of buttons/pages due to filtering
            this.totalRows = filteredItems.length;
            this.currentPage = 1;
        },
    },
};
</script>

<style></style>
