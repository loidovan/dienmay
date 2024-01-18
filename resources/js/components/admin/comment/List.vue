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
                                <h3 class="card-title mt-3">Bảng Bình Luận</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body" style="overflow-x: auto">
                                <b-row>
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
                                        <div class="mt-3">
                                            <button
                                                @click="handleStatus(1)"
                                                class="btn-success btn"
                                                v-b-tooltip.hover.v-secondary="
                                                    'Đã xử lý'
                                                "
                                            >
                                                <i
                                                    class="fas fa-vote-yea text-white"
                                                ></i>
                                            </button>
                                            <button
                                                @click="handleStatus(0)"
                                                class="btn-danger btn"
                                                v-b-tooltip.hover.v-secondary="
                                                    'Chờ xử lý'
                                                "
                                            >
                                                <i class="fas fa-spinner"></i>
                                            </button>
                                        </div>
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
                                        <div class="float-right">
                                            <input
                                                class="form-check-input"
                                                type="checkbox"
                                                id="check1"
                                                v-model="selectedFilter"
                                                @change="filterData()"
                                                value="0"
                                            />
                                            <label
                                                class="form-check-label mr-4"
                                                for="check1"
                                                >Chờ xử lý</label
                                            >

                                            <input
                                                class="form-check-input"
                                                type="checkbox"
                                                id="check2"
                                                v-model="selectedFilter"
                                                @change="filterData()"
                                                value="1"
                                            />
                                            <label
                                                class="form-check-label mr-4"
                                                for="check2"
                                                >Đã xử lý</label
                                            >
                                        </div>
                                    </b-col>
                                </b-row>

                                <b-table
                                    :items="comments"
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
                                    <template #cell(status)="row">
                                        <span v-if="row.item.status == 0">
                                            <span class="badge badge-danger">
                                                Chờ xử lý
                                            </span>
                                        </span>
                                        <span v-else-if="row.item.status == 1">
                                            <span class="badge badge-success">
                                                Đã xử lý
                                            </span>
                                        </span>
                                    </template>
                                    <template #cell(action)="row">
                                        <span @click="row.toggleDetails">
                                            <i
                                                class="fas fa-eye fa-lg"
                                                v-b-tooltip.hover.v-secondary="
                                                    'Xem chi tiết'
                                                "
                                            ></i>
                                        </span>
                                        <span
                                            @click="deleteCategory(row.item.id)"
                                        >
                                            <i
                                                class="fas fa-trash-alt fa-lg"
                                                v-b-tooltip.hover.v-secondary="
                                                    'Xóa bản ghi'
                                                "
                                            ></i>
                                        </span>
                                    </template>
                                    <template #row-details="row">
                                        <b-card>
                                            <div
                                                v-if="
                                                    row.item.comment_details
                                                        .length
                                                "
                                            >
                                                <p class="fw-bold">
                                                    {{
                                                        row.item
                                                            .comment_details[0]
                                                            .user.name
                                                    }}
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
                                                    <span
                                                        style="
                                                            font-size: 10px;
                                                            font-weight: 100;
                                                            margin-left: 10px;
                                                        "
                                                        >{{
                                                            row.item
                                                                .comment_details[0]
                                                                .created_at
                                                        }}</span
                                                    >
                                                </p>
                                                <b-card>
                                                    {{
                                                        row.item
                                                            .comment_details[0]
                                                            .content
                                                    }}
                                                </b-card>
                                            </div>
                                            <div
                                                class="d-flex align-items-center"
                                            >
                                                <b-form-input
                                                    v-model="replyContent"
                                                    placeholder="Trả lời..."
                                                ></b-form-input>
                                                <b-button
                                                    class="w-25 ml-3"
                                                    variant="primary"
                                                    @click="
                                                        handleReply(
                                                            replyContent,
                                                            row.item.id
                                                        )
                                                    "
                                                    >Xác nhận</b-button
                                                >
                                            </div>
                                        </b-card>
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
            comments: [],
            orderDetails: [],
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
                    label: "Mã SP",
                    class: "text-center align-middle",
                    sortable: true,
                },
                {
                    key: "product.name",
                    label: "Sản phẩm",
                    class: "text-center align-middle",
                    sortable: true,
                },
                {
                    key: "customer_name",
                    label: "Họ tên",
                    class: "text-center align-middle",
                    sortable: true,
                },
                {
                    key: "customer_phone",
                    label: "SĐT",
                    class: "text-center align-middle",
                    sortable: true,
                },
                {
                    key: "content",
                    label: "Nội dung",
                    class: "text-center align-middle",
                    sortable: true,
                },
                {
                    key: "created_at",
                    label: "Ngày bình luận",
                    class: "text-center align-middle",
                    sortable: true,
                },
                {
                    key: "status",
                    label: "Trạng thái",
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
            selectedFilter: ["0", "1"],
            optionsFilter: [
                {
                    text: "Chờ xử lý",
                    value: 0,
                },
                {
                    text: "Đã xử lý",
                    value: 1,
                },
            ],
            allComments: [],
            replyContent: "",
        };
    },
    created() {
        this.getComments();
    },
    methods: {
        getComments() {
            axios
                .get("/api/comments")
                .then((response) => {
                    this.comments = response.data;
                    this.allComments = response.data;
                    this.totalRows = this.comments.length;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        deleteCategory(id) {
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
                            .delete("/api/comments/" + id)
                            .then((response) => {
                                this.getComments();
                                this.$swal({
                                    title: "Đã xóa!",
                                    icon: "success",
                                    position: "top-end",
                                    showConfirmButton: false,
                                    timer: 1500,
                                    width: 360,
                                });
                            })
                            .catch((error) => {
                                console.log(error);
                                if (error.response.status == 401) {
                                    this.$swal({
                                        icon: "error",
                                        title: "Lỗi",
                                        text: "Bạn không có quyền thực hiện hành động này!",
                                    });
                                }
                            });
                        this.getComments();
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
            this.selected = checked ? this.comments.map((item) => item.id) : [];
            this.json_data = checked
                ? this.comments.map((item, index) => {
                      return {
                          STT: index + 1,
                          "Mã SP": item.product.code,
                          "Sản phẩm": item.product.name,
                          "Họ tên": item.customer_name,
                          SĐT: item.customer_phone,
                          "Nội dung": item.content,
                          "Ngày bình luận": item.created_at,
                          "Trạng thái": item.status,
                      };
                  })
                : [];
            this.pdf_data = checked
                ? this.comments.map((item, index) => {
                      return [
                          index + 1,
                          item.product.code,
                          item.product.name,
                          item.customer_name,
                          item.customer_phone,
                          item.content,
                          item.created_at,
                          item.status,
                      ];
                  })
                : [];
        },
        selectOne(checked) {
            if (checked) {
                this.json_data = this.comments
                    .filter((item) => this.selected.includes(item.id))
                    .map((item, index) => {
                        return {
                            STT: index + 1,
                            "Mã SP": item.product.code,
                            "Sản phẩm": item.product.name,
                            "Họ tên": item.customer_name,
                            SĐT: item.customer_phone,
                            "Nội dung": item.content,
                            "Ngày bình luận": item.created_at,
                            "Trạng thái": item.status,
                        };
                    });
                this.pdf_data = this.comments
                    .filter((item) => this.selected.includes(item.id))
                    .map((item, index) => {
                        return [
                            index + 1,
                            item.product.code,
                            item.product.name,
                            item.customer_name,
                            item.customer_phone,
                            item.rating,
                            item.content,
                            item.created_at,
                            item.status,
                        ];
                    });
            }
            this.allSelected = this.selected.length === this.comments.length;
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
                            .delete("/api/comments/" + this.selected)
                            .then((response) => {
                                this.getComments();
                                this.$swal({
                                    title: "Đã xóa!",
                                    icon: "success",
                                    position: "top-end",
                                    showConfirmButton: false,
                                    timer: 1500,
                                    width: 360,
                                });
                            })
                            .catch((error) => {
                                console.log(error);
                                if (error.response.status == 401) {
                                    this.$swal({
                                        icon: "error",
                                        title: "Lỗi",
                                        text: "Bạn không có quyền thực hiện hành động này!",
                                    });
                                }
                            });
                        this.getComments();
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
                cols[i].style.display = "none";
                cols[k].style.display = "none";
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
                cols[i].style.display = "table-cell";
                cols[k].style.display = "table-cell";
            }
        },
        onFiltered(filteredItems) {
            // Trigger pagination to update the number of buttons/pages due to filtering
            this.totalRows = filteredItems.length;
            this.currentPage = 1;
        },
        filterData() {
            this.comments = this.allComments;
            switch (this.selectedFilter.length) {
                case 1:
                    if (this.selectedFilter[0] == 0) {
                        this.comments = this.comments.filter((item) => {
                            return item.status == 0;
                        });
                    } else if (this.selectedFilter[0] == 1) {
                        this.comments = this.comments.filter((item) => {
                            return item.status == 1;
                        });
                    } else if (this.selectedFilter[0] == 2) {
                        this.comments = this.comments.filter((item) => {
                            return item.status == 2;
                        });
                    }
                    break;
                case 2:
                    if (
                        this.selectedFilter[0] == 0 &&
                        this.selectedFilter[1] == 1
                    ) {
                        this.comments = this.comments.filter(
                            (item) => item.status == 0 || item.status == 1
                        );
                    } else if (
                        this.selectedFilter[0] == 0 &&
                        this.selectedFilter[1] == 2
                    ) {
                        this.comments = this.comments.filter(
                            (item) => item.status == 0 || item.status == 2
                        );
                    } else if (
                        this.selectedFilter[0] == 1 &&
                        this.selectedFilter[1] == 0
                    ) {
                        this.comments = this.comments.filter(
                            (item) => item.status == 1 || item.status == 0
                        );
                    } else if (
                        this.selectedFilter[0] == 1 &&
                        this.selectedFilter[1] == 2
                    ) {
                        this.comments = this.comments.filter(
                            (item) => item.status == 1 || item.status == 2
                        );
                    } else if (
                        this.selectedFilter[0] == 2 &&
                        this.selectedFilter[1] == 0
                    ) {
                        this.comments = this.comments.filter(
                            (item) => item.status == 2 || item.status == 0
                        );
                    } else if (
                        this.selectedFilter[0] == 2 &&
                        this.selectedFilter[1] == 1
                    ) {
                        this.comments = this.comments.filter(
                            (item) => item.status == 2 || item.status == 1
                        );
                    }
                    break;
                case 0:
                    this.comments = [];
                    break;
                default:
                    this.getComments();
                    break;
            }
        },
        handleStatus(status) {
            axios
                .put("/api/comments/" + this.selected[0], {
                    ids: this.selected,
                    status: status,
                })
                .then((response) => {
                    this.getComments();
                    this.$swal({
                        title: "Đã cập nhật!",
                        icon: "success",
                        position: "top-end",
                        showConfirmButton: false,
                        timer: 1500,
                        width: 360,
                    });
                })
                .catch((err) => {
                    console.log({ err });
                    this.$swal({
                        icon: "error",
                        title: "Lỗi",
                        text: "Bạn không có quyền thực hiện hành động này!",
                    });
                });
        },
        handleReply(content, commentId) {
            axios
                .put("/api/comments/" + commentId, {
                    content,
                })
                .then((response) => {
                    this.getComments();
                    this.$swal({
                        title: "Đã cập nhật!",
                        icon: "success",
                        position: "top-end",
                        showConfirmButton: false,
                        timer: 1500,
                        width: 360,
                    });
                })
                .catch((err) => {
                    console.log({ err });
                    this.$swal({
                        icon: "error",
                        title: "Lỗi",
                        text: "Bạn không có quyền thực hiện hành động này!",
                    });
                });
        },
    },
};
</script>

<style></style>
