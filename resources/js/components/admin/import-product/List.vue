<template>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <Breadcrumbs />

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title mt-3">Bảng Nhập Hàng</h3>
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

                                        <button
                                            @click="deleteMutiple"
                                            class="btn-danger btn"
                                            v-b-tooltip.hover.v-secondary="
                                                'Xóa các bản ghi đã chọn'
                                            "
                                        >
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        <router-link
                                            :to="{
                                                name: 'import-products.create',
                                            }"
                                            class="btn btn-primary"
                                            v-b-tooltip.hover.v-secondary="
                                                'Thêm mới bản ghi'
                                            "
                                        >
                                            <i class="fas fa-plus"></i>
                                        </router-link>
                                    </b-col>
                                    <b-col md="4" class="my-1">
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
                                    :items="importProducts"
                                    :fields="fields"
                                    :per-page="perPage"
                                    :current-page="currentPage"
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
                                    <template #cell(action)="row">
                                        <span
                                            @click="
                                                deleteImportProduct(row.item.id)
                                            "
                                        >
                                            <i
                                                class="fas fa-trash-alt fa-lg"
                                                v-b-tooltip.hover.v-secondary="
                                                    'Xóa bản ghi'
                                                "
                                            ></i>
                                        </span>
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
import html2PDF from "jspdf-html2canvas";

export default {
    data() {
        return {
            importProducts: [],
            selected: [],
            allSelected: false,
            totalRows: 1,
            currentPage: 1,
            perPage: 10,
            pageOptions: [10, 25, 50, 100, 500],
            filter: null,
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
                    key: "product.code",
                    label: "Mã sản phẩm",
                    class: "text-center align-middle",
                    sortable: true,
                },
                {
                    key: "product.name",
                    label: "Tên Sản phẩm",
                    class: "text-center align-middle",
                    sortable: true,
                },
                {
                    key: "product.name",
                    label: "Tên Sản phẩm",
                    class: "text-center align-middle",
                    sortable: true,
                },
                {
                    key: "quantity",
                    label: "SL nhập",
                    class: "text-center align-middle",
                    sortable: true,
                },
                {
                    key: "price",
                    label: "Đơn giá",
                    class: "text-center align-middle",
                    sortable: true,
                },
                {
                    key: "supplier",
                    label: "Nhà cung cấp",
                    class: "text-center align-middle",
                    sortable: true,
                },
                {
                    key: "supplier_address",
                    label: "Địa chỉ nhà cung cấp",
                    class: "text-center align-middle",
                    sortable: true,
                },
                {
                    key: "supplier_phone",
                    label: "SĐT nhà cung cấp",
                    class: "text-center align-middle",
                    sortable: true,
                },
                {
                    key: "created_at",
                    label: "Ngày tạo",
                    class: "text-center align-middle",
                    sortable: true,
                },
                {
                    key: "action",
                    label: "Hành động",
                    class: "text-center align-middle",
                    sortable: false,
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
        this.getImportProducts();
    },
    methods: {
        getImportProducts() {
            axios
                .get("/api/import-products")
                .then((response) => {
                    this.importProducts = response.data;
                    this.totalRows = this.importProducts.length;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        deleteImportProduct(id) {
            this.$swal({
                title: "Bạn chắc chắn muốn xóa?",
                text: "Bạn sẽ không thể hoàn tác!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Đồng ý!",
                cancelButtonText: "Hủy",
                width: 480,
            })
                .then(async (result) => {
                    if (result.isConfirmed) {
                        axios
                            .delete("/api/import-products/" + id)
                            .then((response) => {
                                this.getImportProducts();
                            })
                            .catch((error) => {
                                console.log(error);
                            });
                        this.$swal({
                            title: "Đã xóa!",
                            icon: "success",
                            position: "top-end",
                            showConfirmButton: false,
                            timer: 1500,
                            width: 360,
                        });
                        this.getImportProducts();
                    } else {
                        this.$swal({
                            title: "Hủy xóa!",
                            icon: "info",
                            position: "top-end",
                            showConfirmButton: false,
                            timer: 1500,
                            width: 360,
                        });
                    }
                })
                .catch((error) => {
                    if (error.response.status == 401) {
                        this.$swal({
                            icon: "error",
                            title: "Lỗi",
                            text: "Bạn không có quyền thực hiện hành động này!",
                        });
                    }
                });
        },
        selectAll(checked) {
            this.selected = checked
                ? this.importProducts.map((item) => item.id)
                : [];
            this.json_data = checked
                ? this.importProducts.map((item, index) => {
                      return {
                          STT: index + 1,
                          "Mã sản phẩm": item.product.code,
                          "Tên sản phẩm": item.product.name,
                          "Số lượng nhập": item.quantity,
                          "Đơn giá nhập": item.price,
                          "Nhà cung cấp": item.supplier,
                          "Địa chỉ nhà cung cấp": item.supplier_address,
                          "SĐT nhà cung cấp": item.supplier_phone,
                          "Ngày tạo": item.created_at,
                          "Ngày sửa": item.updated_at,
                      };
                  })
                : [];
            this.pdf_data = checked
                ? this.importProducts.map((item, index) => {
                      return [
                          index + 1,
                          item.product.code,
                          item.product.name,
                          item.quantity,
                          item.price,
                          item.supplier,
                          item.supplier_address,
                          item.supplier_phone,
                          item.created_at,
                          item.updated_at,
                      ];
                  })
                : [];
        },
        selectOne(checked) {
            if (checked) {
                this.json_data = this.importProducts
                    .filter((item) => this.selected.includes(item.id))
                    .map((item, index) => {
                        return {
                            STT: index + 1,
                            "Mã sản phẩm": item.product.code,
                            "Tên sản phẩm": item.product.name,
                            "Số lượng nhập": item.quantity,
                            "Đơn giá nhập": item.price,
                            "Nhà cung cấp": item.supplier,
                            "Địa chỉ nhà cung cấp": item.supplier_address,
                            "SĐT nhà cung cấp": item.supplier_phone,
                            "Ngày tạo": item.created_at,
                            "Ngày sửa": item.updated_at,
                        };
                    });
                this.pdf_data = this.importProducts
                    .filter((item) => this.selected.includes(item.id))
                    .map((item, index) => {
                        return [
                            index + 1,
                            item.product.code,
                            item.product.name,
                            item.quantity,
                            item.price,
                            item.supplier,
                            item.supplier_address,
                            item.supplier_phone,
                            item.created_at,
                            item.updated_at,
                        ];
                    });
            }
            this.allSelected =
                this.selected.length === this.importProducts.length;
        },
        deleteMutiple() {
            this.$swal({
                title: "Bạn chắc chắn muốn xóa?",
                text: "Bạn sẽ không thể hoàn tác!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Đồng ý!",
                cancelButtonText: "Hủy",
                width: 480,
            })
                .then(async (result) => {
                    if (result.isConfirmed) {
                        axios
                            .delete("/api/import-products/" + this.selected)
                            .then((response) => {
                                this.getImportProducts();
                            })
                            .catch((error) => {
                                console.log(error);
                            });
                        this.$swal({
                            title: "Đã xóa!",
                            icon: "success",
                            position: "top-end",
                            showConfirmButton: false,
                            timer: 1500,
                            width: 360,
                        });
                        this.getImportProducts();
                    } else {
                        this.$swal({
                            title: "Hủy xóa!",
                            icon: "info",
                            position: "top-end",
                            showConfirmButton: false,
                            timer: 1500,
                            width: 360,
                        });
                    }
                })
                .catch((error) => {
                    if (error.response.status == 401) {
                        this.$swal({
                            icon: "error",
                            title: "Lỗi",
                            text: "Bạn không có quyền thực hiện hành động này!",
                        });
                    }
                });
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
