<template>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <Breadcrumbs />

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <!-- left column -->
                    <div class="col-md-12">
                        <!-- jquery validation -->
                        <div class="card card-primary" @keyup.enter="submit()">
                            <div class="card-header">
                                <h3 class="card-title mt-2">Thêm Mới</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="exampleInputEmail1"
                                        >Mã sản phẩm</label
                                    >
                                    <input
                                        type="text"
                                        v-model="form.code"
                                        class="form-control"
                                        :class="{
                                            'is-invalid': errors.code,
                                        }"
                                        placeholder="Nhập mã sản phẩm"
                                        required
                                    />
                                    <div
                                        v-if="errors.code"
                                        class="invalid-feedback"
                                    >
                                        {{ errors.code[0] }}
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputEmail1"
                                        >Số lượng nhập</label
                                    >
                                    <input
                                        type="number"
                                        v-model="form.quantity"
                                        class="form-control"
                                        :class="{
                                            'is-invalid': errors.quantity,
                                        }"
                                        placeholder="Nhập số lượng"
                                        required
                                    />
                                    <div
                                        v-if="errors.quantity"
                                        class="invalid-feedback"
                                    >
                                        {{ errors.quantity[0] }}
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputEmail1"
                                        >Đơn giá nhập</label
                                    >
                                    <input
                                        type="number"
                                        v-model="form.price"
                                        class="form-control"
                                        :class="{
                                            'is-invalid': errors.price,
                                        }"
                                        placeholder="Nhập đơn giá nhập"
                                        required
                                    />
                                    <div
                                        v-if="errors.price"
                                        class="invalid-feedback"
                                    >
                                        {{ errors.price[0] }}
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputEmail1"
                                        >Nhà cung cấp</label
                                    >
                                    <input
                                        type="text"
                                        v-model="form.supplier"
                                        class="form-control"
                                        :class="{
                                            'is-invalid': errors.supplier,
                                        }"
                                        placeholder="Nhập nhà cung cấp"
                                        required
                                    />
                                    <div
                                        v-if="errors.supplier"
                                        class="invalid-feedback"
                                    >
                                        {{ errors.supplier[0] }}
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputEmail1"
                                        >Địa chỉ nhà cung cấp</label
                                    >
                                    <input
                                        type="text"
                                        v-model="form.supplier_address"
                                        class="form-control"
                                        :class="{
                                            'is-invalid':
                                                errors.supplier_address,
                                        }"
                                        placeholder="Nhập địa chỉ nhà cung cấp"
                                        required
                                    />
                                    <div
                                        v-if="errors.supplier_address"
                                        class="invalid-feedback"
                                    >
                                        {{ errors.supplier_address[0] }}
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputEmail1"
                                        >SĐT nhà cung cấp</label
                                    >
                                    <input
                                        type="text"
                                        v-model="form.supplier_phone"
                                        class="form-control"
                                        :class="{
                                            'is-invalid': errors.supplier_phone,
                                        }"
                                        placeholder="Nhập SĐT nhà cung cấp"
                                        required
                                    />
                                    <div
                                        v-if="errors.supplier_phone"
                                        class="invalid-feedback"
                                    >
                                        {{ errors.supplier_phone[0] }}
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <button
                                    @click.prevent="submit()"
                                    class="btn btn-primary"
                                >
                                    Xác nhận
                                </button>
                            </div>
                        </div>
                        <!-- /.card -->
                    </div>
                    <!--/.col (left) -->
                    <!-- right column -->
                    <div class="col-md-6"></div>
                    <!--/.col (right) -->
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
export default {
    data() {
        return {
            form: {
                code: "",
                quantity: "",
                price: "",
                supplier: "",
                supplier_address: "",
                supplier_phone: "",
            },
            errors: {},
        };
    },
    methods: {
        submit() {
            axios
                .post("/api/import-products", this.form)
                .then((response) => {
                    this.form.code = "";
                    this.form.quantity = "";
                    this.form.price = "";
                    this.form.supplier = "";
                    this.form.supplier_address = "";
                    this.form.supplier_phone = "";
                    this.errors.code = "";
                    this.errors.quantity = "";
                    this.errors.price = "";
                    this.errors.supplier = "";
                    this.errors.supplier_address = "";
                    this.errors.supplier_phone = "";
                    this.$swal({
                        title: "Thành công",
                        icon: "success",
                        text: "Thêm mới thành công",
                        position: "top-end",
                        timer: 1500,
                        showConfirmButton: false,
                        width: 360,
                    });
                })
                .catch((error) => {
                    this.errors = error.response.data.errors;
                    if (error.response.status == 401) {
                        this.$swal({
                            icon: "error",
                            title: "Lỗi",
                            text: "Bạn không có quyền thực hiện hành động này!",
                        });
                    }
                });
        },
    },
};
</script>

<style></style>
