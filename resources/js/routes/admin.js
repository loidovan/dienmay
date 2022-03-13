const routes = [
    {
        path: "/admin",
        component: require("../components/admin/Index.vue").default,
        meta: {
            requiresAuth: true,
            requiresRole: 'admin',
            breadcrumb: 'Tổng Quan'
        },
        children: [
            {
                path: "",
                name: "admin.dashboard",
                component: require("../components/admin/Dashboard.vue").default,
                meta: {
                    title: "Trang Quản Trị",
                }
            },
            {
                path: "categories",
                name: "categories",
                component: require("../components/admin/category/List.vue")
                    .default,
                meta: {
                    title: "Danh Mục Sản Phẩm",
                    breadcrumb: "Sản Phẩm"
                },
            },
            {
                path: "categories/create",
                name: "categories.create",
                component: require("../components/admin/category/Create.vue")
                    .default,
                meta: {
                    title: "Thêm Mới",
                    breadcrumb() {
                        return {
                            label: this.$route.meta.title,
                            parent: "categories",
                        }
                    },
                },
            },
            {
                path: "categories/edit/:id",
                name: "categories.edit",
                component: require("../components/admin/category/Edit.vue")
                    .default,
                meta: {
                    title: "Chỉnh Sửa",
                    breadcrumb() {
                        return {
                            label: this.$route.meta.title,
                            parent: "categories",
                        }
                    },
                },
            },
        ],
    },
    {
        path: "/admin/login",
        name: "admin.login",
        component: require("../components/admin/login/Login.vue").default,
        meta: {
            title: "Đăng Nhập",
            requiresAuth: false,
        },
        beforeEnter: (to, from, next) => {
            if (localStorage.getItem("token") || sessionStorage.getItem("token")) {
                if (localStorage.getItem("role") === "admin" || localStorage.getItem("role") === "superadmin" || sessionStorage.getItem("role") === "admin" || sessionStorage.getItem("role") === "superadmin") {
                    next({
                        path: "/admin",
                    });
                }
                else {
                    next({
                        path: "/",
                    });
                }
            }
            else {
                next();
            }
        },
    },
];

export default routes;
